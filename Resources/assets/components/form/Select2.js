import Utils from "umbrella_core/utils/Utils";

export default class Select2 {

    constructor($elt) {
        this.$view = $elt;
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

                let mustacheData = state;

                // add data retrieve from vanilla option element
                if (state.element) {
                    mustacheData['data'] = $(state.element).data();
                    mustacheData['extra'] = mustacheData['data'];  // keep for legacy usage
                }
                return $('<span>' + mustache.render(mustacheTemplate, mustacheData) + '</span>');

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