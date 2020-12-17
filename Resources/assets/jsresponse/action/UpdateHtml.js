import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class UpdateHtml extends JsResponseAction {
    eval(params) {
        const $view = $(params.selector);
        $view.html(params.value);
        app.mount($view);
    }

}