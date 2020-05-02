import AjaxHandler from "umbrella_core/core/AjaxHandler";

export default class AjaxCallbackHandler extends AjaxHandler{

    constructor(success, error = () => {}, complete = () => {}) {
        super();

        this._success = success;
        this._error = error;
        this._complete = complete;
    }

    success(response) {
        this._success(response);
    }

    error(requestObject, error, errorThrown) {
        this._error(requestObject, error, errorThrown);
    }

    complete() {
        this._complete();
    }

}