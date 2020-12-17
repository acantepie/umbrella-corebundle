import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class Callback extends JsResponseAction {

    constructor(callback) {
        super();
        this.callback = callback;
    }

    eval(params) {
        this.callback(params);
    }
}