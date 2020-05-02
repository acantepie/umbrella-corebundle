import ConfirmModal from "umbrella_core/plugins/ConfirmModal";
import Spinner from "umbrella_core/plugins/Spinner";

export default class AjaxUtils {

    static handleLink($view) {
        const options = {
            url: $view.data('xhr'),
            confirm: $view.data('confirm') || false,
            spinner: $view.data('spinner') || false,
            method: 'get'
        };
        return this.request(options);
    }

    static handleForm($view) {
        const options = {
            url: $view.data('xhr'),
            confirm: $view.data('confirm') || false,
            spinner: $view.data('spinner') || false,
            method: $view.attr('method') || 'post',
            data: $view.serializeFormToFormData()
        };
        return this.request(options);
    }

    static request(options = {}, handler = null) {
        if (handler === null) {
            handler = app.getAjaxHandler();
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
            console.log('hide');
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