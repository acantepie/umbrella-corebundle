
/**
 * Custom event :
 * tb:submit
 */
export default class Toolbar {

    constructor($view, options = {}) {
        
        this.$view = $view;
        this.$form = this.$view.find('.js-toolbar-form');
        
        this.options = options;

        this.init();
        this.bind();
    }

    init() {
        const defaultOptions = {
            submitOnChange: false,
            onSubmit: (e, toolbar) => {}
        };

        this.options = {...defaultOptions, ...this.options};
    }

    bind() {

        if (this.options['submitOnChange'] === true) { // reload on change
            this.$view.on('change', 'select, input[type=checkbox], input[type=radio], .js-datepicker', () => {
                this.$view.trigger('submit');
            });

            let timer = null;
            this.$view.on('keyup', 'input[type=text], input[type=search]', (e) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    this.$view.trigger('submit');
                }, 200);
            });
        }

        this.$view.on('submit', (e) => {
            this.options['onSubmit'](e, this);
        });
    }

    submit() {
        if (this.$form) {
            this.$form.trigger('submit');
        }
    }

    getData() {
        return this.$form.length
            ? this.$form.serializeFormToJson()
            : [];
    }
}