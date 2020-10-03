import ConfirmModal from "umbrella_core/plugins/ConfirmModal";
import Spinner from "umbrella_core/plugins/Spinner";

export default class AjaxUtils {

    static xhrPendingRegistryIds = [];

    static handleLink($view, options = []) {
        options = {...{
            url: $view.data('xhr'),
            xhr_id: $view.data('xhr-id') || null,
            confirm: $view.data('confirm') || false,
            spinner: $view.data('spinner') || false,
            method: $view.data('method') || 'get'
        },...options};

        const handler = app.getAjaxHandler($view.data('handler') || null);
        this.request(options, handler);
    }

    static handleForm($view, options = []) {
        options = {...{
            url: $view.data('xhr'),
            xhr_id: $view.data('xhr-id') || null,
            confirm: $view.data('confirm') || false,
            spinner: $view.data('spinner') || false,
            method: $view.attr('method') || 'post',
            data: $view.serializeFormToFormData(),
        },...options};

        const handler = app.getAjaxHandler($view.data('handler') || null);
        this.request(options, handler);
    }

    static request(options = {}, handler = null) {
        if (handler === null) {
            handler = app.getAjaxHandler();
        }

        if ('xhr_id' in options && options['xhr_id']) {
            if (AjaxUtils.xhrPendingRegistryIds.includes(options['xhr_id'])) {
                console.warn(`Request prevented : request with id ${options['xhr_id']} is pending.`);
                return;
            } else {
                AjaxUtils.xhrPendingRegistryIds.push(options['xhr_id']);
            }
        }

        if ('data' in options && options['data'] instanceof FormData) {
            options['contentType'] = false;
            options['processData'] = false;
        }

        if ('spinner' in options && false !== options['spinner']) {
            Spinner.show({text: options['spinner']});
        }

        options['success'] = (response) => {
            handler.success(response);
        };
        options['error'] = (requestObject, error, errorThrown) => {
            handler.error(requestObject, error, errorThrown);
        };
        options['complete'] = () => {

            if ('xhr_id' in options && options['xhr_id']) {
                AjaxUtils.xhrPendingRegistryIds = $.grep(AjaxUtils.xhrPendingRegistryIds, (id) => {
                    return id !== options['xhr_id'];
                });
            }

            Spinner.hide();
            handler.complete();
        };

        if ('confirm' in options && false !== options['confirm']) {
            ConfirmModal.show({
                'text': options['confirm'],
                'confirm': () => $.ajax(options)
            });
        } else {
            return $.ajax(options);
        }


    }

    static get(options = {}, handler = null) {
        options['method'] = 'get';
        return AjaxUtils.request(options, handler);
    }

    static post(options = {}, handler = null) {
        options['method'] = 'post';
        return AjaxUtils.request(options, handler);
    }
}