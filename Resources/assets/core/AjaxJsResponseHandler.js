import AjaxHandler from "umbrella_core/core/AjaxHandler";

export default class AjaxJsResponseHandler extends AjaxHandler {

    static actions = {

        execute_js(params) {
            eval(params.value);
        },

        reload(params) {
            window.location.reload();
        },

        redirect(params) {
            window.location = params.value;
        },

        toast(params) {
            $.toast(params);
        },

        open_modal(params) {
            let $modal = $(params.value);
            let $opened_modal = $('.js-umbrella-modal.show');

            if ($opened_modal.length) {
                $opened_modal.html($modal.find('.modal-dialog'));
                app.mount($opened_modal);

            } else {

                // HACK : bs 4 modal doesn't execute script
                $modal.on('shown.bs.modal', (e) => {
                    app.mount($(e.target));
                    const $scripts = $(e.target).find('script');
                    $.each($scripts, (i, s) => {
                       eval($(s).html());
                    });
                });
                $modal.on('hidden.bs.modal', (e) => {
                    $(e.target).data('bs.modal', null);
                    $(e.target).remove();
                });

                $modal.modal('show');

            }
        },

        close_modal(params) {
            let $opened_modal = $('.js-umbrella-modal.show');
            if ($opened_modal.length) {
                $opened_modal.modal('hide');
            }
        },

        reload_table(params) {
            let selector = '';
            if (params.ids && params.ids.length > 0) {
                selector = params.ids.map((id) => '#' + id).join(', ');
            } else {
                selector = '[data-mount=DataTable]';
            }
            AjaxJsResponseHandler.actions.component_call(selector, 'reload');
        },


        component_call(selector, method, args = []) {
            for (let component of app.getComponents(selector)) {
                component[method](...args);
            }
        },

        update(params) {
            const $view = $(params.selector);
            $view.html(params.value);
            app.mount($view);
        },

        remove(params) {
            $(params.selector).remove();
        },

    };

    success(response) {
        if (Array.isArray(response)) {
            for (const message of response) {
                this.doAction(message);
            }
        } else {
            console.error('JsResponseHandler : invalid response, expected json array.');
        }
    }

    error(requestObject, error, errorThrown)
    {
        if (requestObject.status === 401) {
            $.toast({
                icon: 'warning',
                text: "Vous n'etes plus connecté. Veuillez rafraichir la page pour vous authentifier"
            });
        } else {
            $.toast({
                icon: 'error',
                text: "Une erreur est survenue"
            });
        }
    }

    doAction(message) {
        const action = AjaxJsResponseHandler.actions[message.action];
        if (!action) {
            console.error('AjaxHandler : invalid action ' + message.action);
        } else {
            action(message.params);
        }
    }
}
