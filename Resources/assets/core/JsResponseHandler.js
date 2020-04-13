import KernelAjaxHandler from "umbrella_core/core/KernelAjaxHandler";

export default class JsResponseHandler extends KernelAjaxHandler {

    static handlers = {

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
            toastr[params.level](params.value);
        },

        open_modal(params) {
            let $modal = $(params.value);
            let $opened_modal = $('.js-umbrella-modal.show');

            if ($opened_modal.length) {
                $opened_modal.html($modal.find('.modal-dialog'));
                Kernel.mountComponents($opened_modal);

            } else {

                // HACK : bs 4 modal doesn't execute script
                $modal.on('shown.bs.modal', (e) => {
                    Kernel.mountComponents($(e.target));
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
            const components = params.ids.length > 0 ? Kernel.findComponentsByCssIds(params.ids) : Kernel.findComponents('DataTable');
            console.log(components.length + ' datatable(s) reloaded');
            for (let component of components) {
                component.reload(false);
            }
        },

        reload_tree(params) {
            const components = params.ids.length > 0 ? Kernel.findComponentsByCssIds(params.ids) : Kernel.findComponents('Tree');
            console.log(components.length + ' tree(s) reloaded');
            for (let component of components) {
                component.reload();
            }
        },

        update(params) {
            const $node = $(params.selector);
            $node.html(params.value);
            Kernel.mountComponents($node);
        },

        remove(params) {
            $(params.selector).remove();
        },

    };

    handleSuccess(response) {
        if (Array.isArray(response)) {
            for (const message of response) {
                this.handleMessage(message);
            }
        } else {
            console.error('JsResponseHandler : invalid response, expected json array.');
        }
    }

    handleError(requestObject, error, errorThrown)
    {
        if (requestObject.status === 401) {
            toastr.warning("Vous n'etes plus connecté. Veuillez rafraichir la page pour vous authentifier");
        } else {
            toastr.error('Une erreur est survenue');
        }
    }

    handleMessage(message) {
        let handler = JsResponseHandler.handlers[message.action];
        if (!handler) {
            console.warning('JsResponseHandler : invalid action ' + message.action);
        } else {
            handler(message.params);
        }
    }
}
