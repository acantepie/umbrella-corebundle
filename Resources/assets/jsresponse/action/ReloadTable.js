import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class ReloadTable extends JsResponseAction {

    eval(params) {
        let selector = '';
        if (params.ids && params.ids.length > 0) {
            selector = params.ids.map((id) => '#' + id).join(', ');
        } else {
            selector = '[data-mount=DataTable]';
        }

        for (let component of app.getComponents(selector)) {
            component.reload();
        }
    }
}