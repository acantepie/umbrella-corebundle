import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class Eval extends JsResponseAction {
    eval(params) {
        eval(params.value);
    }
}