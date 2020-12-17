import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";
import Callback from "umbrella_core/jsresponse/action/Callback";

export default class JsResponseHandler {

    constructor() {
        this.actionRegistry = {};
    }

    registerAction(id, obj) {
        if (obj instanceof JsResponseAction) {
            this.actionRegistry[id] = obj;
            return;
        }

        if (typeof(obj) === 'function') {
            this.actionRegistry[id] = new Callback(obj);
            return;
        }

        console.error(`Can\'t register action ${obj}, obj must be a function or extends JsResponseAction class`);
    }

    removeAction(id) {
        delete this.actionRegistry[id];
    }

    clearActions() {
        this.actionRegistry = {};
    }

    success(response) {
        if (!Array.isArray(response)) {
            console.error('[JsReponseHandler] invalid response, expected json array have :', response);
            return;
        }

        for (const message of response) {
            if (!message.hasOwnProperty('action')) {
                console.error('[JsReponseHandler] missing action property on message :', message);
                continue;
            }

            if (!message.hasOwnProperty('params')) {
                console.error('[JsReponseHandler] missing params property on message :', message);
                continue;
            }

            if (!this.actionRegistry.hasOwnProperty(message.action)) {
                console.error(`[JsReponseHandler] Action "${message.action}" not found on regsitry. have you register it using app.jsResponseHandler.registerAction(new MyAction()) ?`);
                continue;
            }

            this.actionRegistry[message.action].eval(message.params);
        }
    }

    error(requestObject, error, errorThrown)
    {
        if (requestObject.status === 401) {
            $.toast({
                icon: 'warning',
                text: "Vous n'etes plus connecté. Veuillez rafraichir la page pour vous authentifier"
            });
        } else if (requestObject.status === 404) {
            $.toast({
                icon: 'warning',
                text: "404 - Impossible de contacter"
            });
        } else {
            $.toast({
                icon: 'error',
                text: "Une erreur est survenue"
            });
        }
    }
}
