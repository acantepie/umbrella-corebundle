export default class DateTimePicker {

    constructor($elt) {
        this.$view = $elt;
        this.options = {
            format: this.$view.data('format') ? this.$view.data('format') : null,
            locale: 'fr',
            sideBySide: true,
            keepOpen: true,
            // inline: true,
            // debug: true,
            toolbarPlacement: 'bottom',
            showClear: this.$view.data('show-clear') ? this.$view.data('show-clear') : false ,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'content-prev',
                next: 'content-next',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        };

        if (this.$view.data('min-date')) {
            this.options.minDate = this.$view.data('min-date');
        }

        if (this.$view.data('max-date')) {
            this.options.maxDate = this.$view.data('max-date');
        }

        this.init();
    }

    init() {
        this.$view.datetimepicker(this.options);
    }
}
