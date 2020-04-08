export default class AjaxUtils {

    static ajax(options = {}, ajaxHandlerIds = ['jsresponse']) {

        const defaultOptions = {};
        options = {...defaultOptions, ...options};

        if ('data' in options && options['data'] instanceof FormData) {
            options['contentType'] = false;
            options['processData'] = false;
        }

        let handlers = [];
        for (let handlerId of ajaxHandlerIds) {
            const handler = Kernel.getAjaxHandler(handlerId);
            if (handler) {
                handlers.push(handler);
            }
        }


        const defaultSuccessCb = options['success'];
        options['success'] = (response) => {
            for(let handler of handlers) {
                handler.handleSuccess(response);
            }
            if (defaultSuccessCb) {
                defaultSuccessCb(response);
            }
        };

        const defaultErrorCb = options['error'];
        options['error'] = (requestObject, error, errorThrown) => {
            for(let handler of handlers) {
                handler.handleError(requestObject, error, errorThrown);
            }
            if (defaultErrorCb) {
                defaultErrorCb(requestObject, error, errorThrown);
            }
        };

        const defaultCompleteCb = options['complete'];
        options['complete'] = () => {
            for(let handler of handlers) {
                handler.handleComplete();
            }

            if (defaultCompleteCb) {
                defaultCompleteCb();
            }
        };

        return $.ajax(options);
    }

    static get(options = {}) {
        options['method'] = 'get';
        return AjaxUtils.ajax(options);
    }

    static post(options = {}) {
        options['method'] = 'post';
        return AjaxUtils.ajax(options);
    }
}