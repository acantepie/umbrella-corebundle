
/**
 * Custom event :
 * tb:submit
 */
class Toolbar {

    constructor($elt, options = {}) {
        this.$view = $elt;
        this.options = options;

        this.init();
        this.bind();
    }

    init() {
        this.configureOptions();
    }

    bind() {

        if (this.options['submitOnChange'] === true) { // reload on change

            this.$view.on('change', 'select, input[type=checkbox], input[type=radio], .js-interval, .js-datepicker', () => {
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
            // avoid default action
            e.preventDefault();
            e.stopPropagation();
            this.$view.trigger('tb:submit');
        });
    }

    configureOptions() {
        const defaultOptions = {
            submitOnChange: false
        };

        this.options = {...defaultOptions, ...this.options};
    }
}

module.exports = Toolbar;