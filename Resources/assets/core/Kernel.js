import KernelComponent from "umbrella_core/core/KernelComponent";
import KernelAjaxHandler from "umbrella_core/core/KernelAjaxHandler";
import AjaxUtils from "umbrella_core/utils/AjaxUtils";

export default class Kernel {

    constructor() {
        this.$container = $('html');
        this.registry = {};
        this.ajaxHandlers = {};
    }

    init() {
        this.bind();
        this.mountComponents(this.$container);
    }


    bind() {
        this.$container.on('click', '[data-xhr-href]', (e) => {
            e.preventDefault();
            this.handleAjaxLink($(e.currentTarget));
        });

        // bind xhr form
        this.$container.on('submit', 'form[data-xhr-action]', (e) => {
            e.preventDefault();
            this.handleAjaxForm($(e.currentTarget));
        });

        // bind xhr buttons
        this.$container.on('click', ':submit', (e) => {
            this.$submitButton = e.currentTarget;
        });

        // bind bootstrap tooltips
        if ($.tooltip) {
            this.$container.find('[data-toggle="tooltip"]').tooltip();
        }

        // bind popover
        if ($.popover) {
            $('[data-toggle="popover"]').popover({
                container: 'body'
            });
        }
    }

    handleAjaxLink($link) {
        const options = {
            url: $link.data('xhr-href')
        };

        const confirm = $link.data('confirm');
        if (confirm) {
            $.confirm({
                'text': confirm,
                'confirm' : () => {
                    AjaxUtils.get(options);
                }
            });
        } else {
            AjaxUtils.get(options);
        }
    }

    handleAjaxForm($form) {
        let formData = $form.serializeFormToFormData();

        if (this.$submitButton !== undefined && this.$submitButton.name) {
            formData.append(this.$submitButton.name, this.$submitButton.value);
        }

        const options = {
            url: $form.data('xhr-action'),
            method: $form.attr('method'),
            data: formData
        };

        AjaxUtils.ajax(options);
    }



    // --- Component

    registerComponent(id, definition) {
        if (!definition instanceof KernelComponent) {
            console.error(`Can'register component ${id}, he must extends KernelComponent`);
        } else {
            this.registry[id] = definition;
        }
    }

    allComponents() {
        return this.findComponentsByCssSelector('[data-mount]');
    }

    findComponents(id) {
        return this.findComponentsByCssSelector('[data-mount=' + id + ']');
    }

    findComponentsByCssIds(ids = []) {
        const cssIds = ids.map(id => '#' + id);
        const cssSelector = cssIds.join(', ');
        return this.findComponentsByCssSelector(cssSelector);
    }

    findComponentsByCssSelector(cssSelector) {
        let components = [];
        $(cssSelector).each((i, e) => {
            const component = this.findComponentByView($(e));
            if (null !== component) {
                components.push(component);
            }
        });
        return components;
    }

    findComponentByView($view) {
        if ($view.length === 0) {
            return null;
        }

        if (!$view.data('component')) { // not mount
            return null;
        }

        return $view.data('component');
    }

    mountComponent(id, $view) {
        if ($view.data('component')) { // already mount
            return;
        }

        if (!id in this.registry) {
            console.error(`Can't find component ${id} on registry. Maybe you forget ro register it ?`);
            return;
        }

        const definition = this.registry[id];
        $view.data('component', new definition($view));
    }

    mountComponents($container) {
        $container.find('[data-mount]').each((i, e) => {
            const $e = $(e);
            this.mountComponent($e.data('mount'), $e);
        });
    }

    // --- Ajax handler

    registerAjaxHandler(id, definition) {
        if (!definition instanceof KernelAjaxHandler) {
            console.error(`Can'register ajax handler ${id}, he must extends KernelAjaxHandler`);
        } else {
            this.ajaxHandlers[id] = new definition();
        }
    }

    getAjaxHandler(id) {
        return id in this.ajaxHandlers ? this.ajaxHandlers[id] : null;
    }
}