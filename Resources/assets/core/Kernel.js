import KernelAjaxHandler from "./KernelAjaxHandler";
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
        this.$container.on('click', '[data-xhr]:not(form)', (e) => {
            e.preventDefault();
            this.handleXhr($(e.currentTarget));
        });

        // bind xhr form
        this.$container.on('submit', 'form[data-xhr]', (e) => {
            e.preventDefault();
            this.handleFormXhr($(e.currentTarget));
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

        // bind toast
        if ($.toast) {
            $('[data-toggle="toast"]').each((i, e) => {
                $.toast($(e).data('options'));
            })
        }
    }

    handleXhr($e) {
        const options = {
            url: $e.data('xhr')
        };

        const confirm = $e.data('confirm');
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

    handleFormXhr($form) {
        let formData = $form.serializeFormToFormData();

        const options = {
            url: $form.data('xhr'),
            method: $form.attr('method'),
            data: formData
        };

        AjaxUtils.ajax(options);
    }



    // --- Component

    registerComponent(id, definition) {
        this.registry[id] = definition;
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

        if (!(id in this.registry)) {
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