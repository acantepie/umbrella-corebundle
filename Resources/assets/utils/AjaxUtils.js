export default class AjaxUtils {

    static handleLink($view) {
        const options = {
            url: $view.data('xhr')
        };

        const confirm = $view.data('confirm');
        if (confirm) {
            $.confirm({
                'text': confirm,
                'confirm': () => {
                    AjaxUtils.get(options);
                }
            });
        } else {
            AjaxUtils.get(options);
        }
    }

    static handleForm($view) {
        let formData = $view.serializeFormToFormData();

        const options = {
            url: $view.data('xhr'),
            method: $view.attr('method'),
            data: formData
        };

        AjaxUtils.request(options);
    }

    static request(options = {}, handler = null) {
        if (handler === null) {
            handler = app.getAjaxHandler();
        }

        const defaultOptions = {};
        options = {...defaultOptions, ...options};

        if ('data' in options && options['data'] instanceof FormData) {
            options['contentType'] = false;
            options['processData'] = false;
        }

        options['success'] = (response) => handler.success(response); // keep ctx hack
        options['error'] = (requestObject, error, errorThrown) => handler.error(requestObject, error, errorThrown);  // keep ctx hack
        options['complete'] = () => handler.complete();  // keep ctx hack
        return $.ajax(options);
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