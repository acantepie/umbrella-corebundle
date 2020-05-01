export default class DatePicker {

    constructor($elt) {
        this.$view = $elt;
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