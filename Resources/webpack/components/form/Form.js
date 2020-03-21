const DatePicker = require('./DatePicker');
const DateTimePicker = require('./DateTimePicker');
const Fileupload = require('./FileUpload');
const Select2 = require('./Select2');
const AsyncSelect2 = require('./AsyncSelect2');
const Collection = require('./Collection');
const Interval = require('./Interval');

class Form {

    constructor($elt) {
        this.$view = $elt;
        this.init();
    }

    init() {

        if (jQuery().minicolors) {
            this.$view.find('.js-colorpicker').minicolors({
                theme: 'bootstrap'
            });
        }
        if (jQuery().datepicker) {
            this.$view.find('.js-datepicker').each((i, e) => {
                new DatePicker($(e));
            });
        }
        if (jQuery().datetimepicker) {
            this.$view.find('.js-datetimepicker').each((i, e) => {
                new DateTimePicker($(e));
            });
        }


        if (jQuery().tagsinput) {
            this.$view.find('.js-umbrella-tag').tagsinput();
        }

        if (jQuery().select2) {
            this.$view.find('.js-select2').each((i, e) => {
                if ($(e).parents(".togglable-entity2").length == 0) {
                    new Select2($(e));
                }
            });

            this.$view.find('.js-async-select2').each((i, e) => {
                new AsyncSelect2($(e));
            });
        }

        this.$view.find('.js-umbrella-fileupload').each((i, e) => {
            new Fileupload($(e));
        });

        if (window.CKEDITOR) {
            this.$view.find('.js-ckeditor').each((i, e) => {
                let data_config = Utils.decode_html($(e).data('config')) || {};
                let config = typeof data_config === "object" ? data_config : $.parseJSON(data_config);
                CKEDITOR.replace(e, config);
            });

            this.$view.find('.js-ckeditor-inline').each((i, e) => {
                let data_config = Utils.decode_html($(e).data('config')) || {};
                let config = typeof data_config === "object" ? data_config : $.parseJSON(data_config);
                CKEDITOR.disableAutoInline = true;
                CKEDITOR.inline(e, config);
            });
        }

        this.$view.find('.js-umbrella-collection').each((i, e) => {
            new Collection($(e));
        });

        this.$view.find('.js-interval').each((i, e) => {
            new Interval($(e));
        });
    }
}

module.exports = Form;
