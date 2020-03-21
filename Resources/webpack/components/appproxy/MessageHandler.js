
class MessageHandler {

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
            let component = Kernel.getComponent(params.id);
            if (component){
                component.reload(false);
            } else {
                console.warn('No component found with id ' + params.id);
            }
        },

        reload_tree(params) {
            let component = Kernel.getComponent(params.id);
            if (component){
                component.reload();
            } else {
                console.warn('No component found with id ' + params.id);
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

    static handle(message) {
        let handler = MessageHandler.handlers[message.action];
        if (!handler) {
            console.error('App message handler : no handler found for message ', message);
        } else {
            handler(message.params);
        }
    }
}

module.exports = MessageHandler;
