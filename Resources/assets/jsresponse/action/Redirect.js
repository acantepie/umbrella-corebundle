import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class Redirect extends JsResponseAction {
    eval(params) {
        window.location = params.value;
    }
}