import Utils from "umbrella_core/utils/Utils";
import Component from "umbrella_core/core/Component";

export default class FileUpload extends Component {

    constructor($view) {
        super($view);

        this.$inputFile = this.$view.find('input.js-file');
        this.$inputEntity = this.$view.find('input.js-entity');
        this.$inputTxt = this.$view.find('input.js-text');
        this.$inputDelete = this.$view.find('input.js-delete');

        this.$removeBtn = this.$view.find('.js-remove');
        this.$downloadBtn = this.$view.find('.js-download');
        this.$browseBtn = this.$view.find('.js-browse');

        this.init();
        this.bind();
    }

    init() {

        if (this.$view.data('initialized')) {
            this.$removeBtn.removeClass('d-none');
        } else {
            this.$removeBtn.addClass('d-none');
        }
    }

    bind() {
        this.$browseBtn.on('click', () => {
           this.$inputFile.click();
        });

        this.$inputFile.on('change', () => {
            this.refresh();
        });

        this.$removeBtn.on('click', () => {
           this.clear();
        });
    }

    clear() {
        this.$inputFile.replaceWith(this.$inputFile.val('').clone(true));
        this.$inputFile = this.$view.find('input[type="file"]');
        this.$inputDelete.prop('checked', true);
        this.$downloadBtn.addClass('d-none');
        this.refresh();
    }

    refresh() {
        let files = this.$inputFile[0].files;
        if (files.length > 0) {
            let file = files[0];
            this.$inputTxt.val(file.name + ' - ' + Utils.bytes_to_size(file.size));
            this.$removeBtn.removeClass('d-none');
        } else {
            this.$inputTxt.val('');
            this.$removeBtn.addClass('d-none');
        }
    }

}