import './confirmModal.scss';

export default class ConfirmModal {

    static template = '<div class="modal confirm-modal fade" id="confirm-modal" tabindex="-1">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
            '<div class="modal-body">__msg__</div>' +
        '<div class="modal-footer">' +
            '<button type="button" class="btn btn-outline-light btn-cancel" data-dismiss="modal">__cancel__</button>' +
            '<button type="button" class="btn btn-outline-light btn-confirm">__confirm__</button></div></div></div></div>';

    constructor(message = '', onConfirm = null, onHide = null) {
        this.message = message;
        this.onConfirm = onConfirm;
        this.onHide = onHide;

        this.$modal = null;
        this.show();
    }

    show() {
        let html = ConfirmModal.template.replace('__msg__', this.message);
        html = html.replace('__cancel__', 'Annuler');
        html = html.replace('__confirm__', 'Confirmer');

        this.$modal = $(html);

        this.$modal.on('keypress', (e) => {
            if(e.which === 13) {
                this.confirm(e);
            }
        });
        this.$modal.on('click', '.btn-confirm', (e) => {
            this.confirm(e);
        });
        this.$modal.modal('show');


        if (this.onHide) {
            this.$modal.on('hidden.bs.modal', (e) => {
                this.onHide(e);
            });
        }

    }

    confirm(e) {
        if (this.onConfirm) {
            this.onConfirm(e);
        }
        this.hide();
    }

    hide() {
        this.$modal.modal('hide');
    }
}