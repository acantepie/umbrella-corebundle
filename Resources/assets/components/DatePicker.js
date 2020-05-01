import Component from "umbrella_core/core/Component";

export default class DatePicker extends Component {

    constructor($view) {
        super($view);

        if ($.datepicker) {
            console.error("Can't use AsyncSelect2, datepicker plugin not loaded");
            return;
        }

        this.options = {
            format: this.$view.data('format') ? this.$view.data('format') : null,
            language: 'fr',
            autoclose: true
        };
        this.init();
    }

    init() {
        this.$view.datepicker(this.options);
    }
}