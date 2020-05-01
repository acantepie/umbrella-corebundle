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
                time: 'mdi mdi-clock-outline',
                date: 'mdi mdi-calendar',
                up: ' mdi mdi-chevron-up',
                down: ' mdi mdi-chevron-down',
                previous: 'content-prev',
                next: 'content-next',
                today: 'mdi mdi-check',
                clear: 'mdi mdi-close',
                close: 'mdi mdi-close'
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
