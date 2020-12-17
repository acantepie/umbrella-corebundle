import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class ShowToast extends JsResponseAction {
    eval(params) {
        $.toast(params);
    }
}