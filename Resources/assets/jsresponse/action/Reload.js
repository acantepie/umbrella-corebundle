import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class Reload extends JsResponseAction {
    eval(params) {
        window.location.href = window.location.href.split('#')[0];
    }
}