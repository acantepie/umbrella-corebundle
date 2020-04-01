import MessageHandler from "umbrella_core/components/appproxy/MessageHandler";

export default class Api {

    static ajax(method, url, data, successCb, errorCb, completeCb) {
        let options = {
            url: url,
            method: method,
            data: data,
            success: (response) => {
                Api.handleResponse(response);
                if (successCb) {
                    successCb(response);
                }
            },
            error: (requestObject, error, errorThrown) => {
                if (requestObject.status == 401) {
                    toastr.warning("Vous n'etes plus connecté. Veuillez rafraichir la page pour vous authentifier");
                } else {
                    toastr.error('Internal server error');
                    if (errorCb) {
                        errorCb(requestObject, error, errorThrown);
                    }
                }
            },
            complete: () => {
                if (completeCb) {
                    completeCb();
                }
            }
        };

        if (data instanceof FormData) {
            options['contentType'] = false;
            options['processData'] = false;
        }

        return $.ajax(options);
    }

    static GET(url, data, successCb, errorCb, completeCb) {
        return Api.ajax('GET', url, data, successCb, errorCb, completeCb);
    }

    static POST(url, data, successCb, errorCb, completeCb) {
        return Api.ajax('POST', url, data, successCb, errorCb, completeCb);
    };

    static handleResponse(response) {
        if (Array.isArray(response)) {
            for (const message of response) {
                MessageHandler.handle(message);
            }
        } else {
            console.error('Api : invalid response, response must be an array.');
            // console.error(response);
        }
    };
}