import Utils from "umbrella_core/utils/Utils";
import Component from "umbrella_core/core/Component";

export default class Select2 extends Component {

    constructor($view) {
        super($view);

        if ($.select2) {
            console.error("Can't use Select2, select2 plugin not loaded");
            return;
        }

        this.init();
    }

    configureOptions() {
        let data_options = this.$view.data('options');

        this.options = data_options ? JSON.parse(Utils.decode_html(data_options)) : {};
        this.s2_options = this.options['select2'] ? this.options['select2'] : {};

        // templating
        let mustacheTemplate = null;

        if (this.options['template_selector']) {
            const $template = $(this.options['template_selector']);
            if ($template.length === 0) {
                console.error("No template found with selector " + this.options['template_selector']);
            } else {
                mustacheTemplate = $template.html();
            }
        }

        if (this.options['template_html']) {
            mustacheTemplate = this.options['template_html'];
        }

        if (mustacheTemplate) {
            this.s2_options['templateResult'] = (state) => {
                if (!state.id) {
                    return state.text;
                }

                let data = state;

                // add data retrieve from vanilla option element
                if (state.element) {
                    const exposedData = $(state.element).data('json') || {};
                    data = {...exposedData,...data}
                }

                return $('<span>' + mustache.render(mustacheTemplate, data) + '</span>');

            };
        }

        // ajax loading
        if (this.options['ajax_url']) {
            this.s2_options['ajax'] = {
                url: this.options['ajax_url'],
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {q: params.term, page: params.page}
                },
                processResults: function (data, params) {
                    let more = data.more || false;
                    return {
                        results: data.items,
                        pagination: {
                            more: more
                        }
                    }
                },
                cache: true
            }
        }
    }

    open() {
        this.$view.select2('open');
    }

    init() {
        this.configureOptions();
        this.$view.select2(this.s2_options);
        this.$view.show();
    }
}