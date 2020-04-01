import KernelComponent from "umbrella_core/core/KernelComponent";
import DatePicker from "umbrella_core/components/form/DatePicker";
import DateTimePicker from "umbrella_core/components/form/DateTimePicker";
import Select2 from "umbrella_core/components/form/Select2";
import AsyncSelect2 from "umbrella_core/components/form/AsyncSelect2";
import FileUpload from "umbrella_core/components/form/FileUpload";
import Collection from "umbrella_core/components/form/Collection";

export default class Form extends KernelComponent {

    constructor($elt) {
        super();
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
            new FileUpload($(e));
        });

        this.$view.find('.js-umbrella-collection').each((i, e) => {
            new Collection($(e));
        });
    }
}
