import DatePicker from "./DatePicker";
import DateTimePicker from "./DateTimePicker";
import Select2 from "./Select2";
import AsyncSelect2 from "./AsyncSelect2";
import FileUpload from "./FileUpload";
import Collection from "./Collection";

export default class Form {

    constructor($elt) {
        this.$view = $elt;
        this.init();
    }

    init() {
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
                new Select2($(e));
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
