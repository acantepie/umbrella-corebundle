class ConfirmModal {

    static template = '<div class="modal confirm-modal fade" id="confirm-modal" tabindex="-1">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-body">__text__</div>' +
        '<div class="modal-footer">' +
        '<button type="button" class="btn btn-outline-light btn-cancel" data-dismiss="modal">__cancel__</button>' +
        '<button type="button" class="btn btn-outline-light btn-confirm">__confirm__</button></div></div></div></div>';

    constructor(options) {

        const defaultOptions = {
            text: '',
            cancel_text: 'Annuler',
            confirm_text: 'Confirmer'
        };

        this.options = {...defaultOptions, ...options}
        this.$modal = null;
    }

    show() {
        let html = ConfirmModal.template.replace('__text__', this.options['text']);
        html = html.replace('__cancel__', this.options['cancel_text']);
        html = html.replace('__confirm__', this.options['confirm_text']);

        this.$modal = $(html);

        this.$modal.on('keypress', (e) => {
            if (e.which === 13) {
                this.confirm(e);
            }
        });
        this.$modal.on('click', '.btn-confirm', (e) => {
            this.confirm(e);
        });
        this.$modal.modal('show');
    }

    hide() {
        this.$modal.modal('hide');
    }

    confirm() {
        if (this.options['confirm']) {
            this.options['confirm']();
        }
        this.hide();
    }
}

(function ($) {
    $.confirm = function (options) {
        const instance = new ConfirmModal(options);
        instance.show();
    }

})(jQuery);