const Utils = require('umbrella_core/utils/Utils.js');

const noUiSlider = require('nouislider');
require('nouislider/distribute/nouislider.min.css');
require('./Interval.scss');

class Interval {

    constructor($elt) {
        this.$view = $elt;
        this.init();
    }

    init() {
        this.configureOptions();
        this.buildDOM();
        this.bind();
        this.renderValues();
    }

    configureOptions() {
        let data_options = this.$view.data('options');
        this.options = data_options ? JSON.parse(Utils.decode_html(data_options)) : {};

        let value = this.$view.val();
        if (value) {
            let values = value.split(',');
            this.minValue = parseFloat(values[0].trim());
            this.maxValue = parseFloat(values[1].trim());
        }
        else {
            this.minValue = this.options['min'];
            this.maxValue = this.options['max'];
        }
    }

    bind() {

        noUiSlider.create(this.widget[0], {
            start: [this.minValue, this.maxValue],
            step: (this.options['step']) ? this.options['step'] : 1,
            connect: true,
            range: {
                'min': this.options['min'],
                'max': this.options['max']
            }
        });

        this.widget[0].noUiSlider.on('change.one', () => {
            this.$view.val( this.widget[0].noUiSlider.get() );
            this.renderValues();
            this.$view.trigger('change');
        });

    }

    buildDOM() {

        this.$view.hide();

        this.widgetContainer = $('<div class="js-interval-container"></div>');
        this.widget = $('<div class="js-interval-widget"></div>');
        this.widgetContainer.append(this.widget);

        this.widgetContainer.insertAfter( this.$view );

        this.valueMinContainer = $('<div class="js-min-value">0</div>');
        this.valueMaxContainer = $('<div class="js-max-value">0</div>');
        this.valueMinContainer.insertBefore(this.widget);
        this.valueMaxContainer.insertAfter(this.widget);

    }

    renderValues() {

        this.minValue = this.widget[0].noUiSlider.get()[0];
        this.maxValue = this.widget[0].noUiSlider.get()[1];

        if (this.options['type'] === 'integer') {
            this.minValue = parseInt(this.minValue);
            this.maxValue = parseInt(this.maxValue);
        }

        // Ajout des préfixes
        this.minValue = (this.options['prefix']) ? this.options['prefix'] + this.minValue : this.minValue;
        this.minValue = (this.options['suffix']) ? this.minValue + this.options['suffix'] : this.minValue;

        // Ajout des suffixes
        this.maxValue = (this.options['prefix']) ? this.options['prefix'] + this.maxValue : this.maxValue;
        this.maxValue = (this.options['suffix']) ? this.maxValue + this.options['suffix'] : this.maxValue;

        this.valueMinContainer.html(this.minValue);
        this.valueMaxContainer.html(this.maxValue);
    }

}

module.exports = Interval;