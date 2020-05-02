export default class Spinner {

    static template = '<div class="modal confirm-modal fade" data-keyboard="false" data-backdrop="static" tabindex="-1" id="spinner-modal">' +
        '<div class="modal-dialog modal-dialog-centered" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-body">' +
        '<div class="d-flex align-items-center justify-content-center"><div class="spinner-border avatar-sm text-primary m-2" role="status"></div> __text__</div></div>' +
        '</div></div></div>';

    static $modal = null;

    static show(options = {}) {

        const defaultOptions = {
            text: '',
        };

        options = {...defaultOptions, ...options};

        Spinner.hide();

        let html = Spinner.template.replace('__text__', options['text']);
        Spinner.$modal = $(html);

        Spinner.$modal.on('hidden.bs.modal', () => Spinner.remove());
        Spinner.$modal.modal('show');
    }

    static hide() {
        if (Spinner.$modal) {
            Spinner.$modal.modal('hide');
        }
    }

    static remove() {
        $('#spinner-modal').remove();
    }
}
