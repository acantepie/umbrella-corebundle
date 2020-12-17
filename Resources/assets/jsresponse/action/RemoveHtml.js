import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class RemoveHtml extends JsResponseAction {
    eval(params) {
        $(params.selector).remove();
    }
}