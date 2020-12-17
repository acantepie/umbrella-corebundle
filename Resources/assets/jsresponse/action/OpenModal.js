import JsResponseAction from "umbrella_core/jsresponse/JsResponseAction";

export default class OpenModal extends JsResponseAction {
    eval(params) {
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
    }
}