import Component from "umbrella_core/core/Component";
import Bind from "umbrella_core/core/Bind";
import AjaxHandler from "umbrella_core/core/AjaxHandler";

export default class UmbrellaApp {

    constructor() {
        this.componentRegistry = {};
        this.ajaxHandlerRegistry = {};
    }

    // *** Components *** //

    mount($container = null) {
        for (let selector in this.componentRegistry) {
            const componentClass = this.componentRegistry[selector];

            const $e = $container ? $container.find(selector) : $(selector);
            $e.each(function () {
                const $view = $(this);
                if (!$view.data('component')) {
                    $view.data('component', new componentClass($view));
                }
            });
        }
    }

    use(selector, componentClass) {
        if (!(componentClass.prototype instanceof Component)) {
            console.error(`Can't use component ${componentClass.prototype.constructor.name}, he must extends Component class`);
            return;
        }

        this.componentRegistry[selector] = componentClass;
    }

    getComponents(selector) {
        let components = [];

        $(selector).each((i, e) => {
            const $e = $(e);

            // components
            if ($e.data('component')) {
                components.push($e.data('component'));
            }
        });
        return components;
    }

    // *** Ajax handlers *** //

    useAjaxHandler(handlerName, handler) {
        if (!(handler instanceof AjaxHandler)) {
            console.error(`Can't use handler ${handler.constructor.name}, he must extends AjaxHandler class`);
            return;
        }

        this.ajaxHandlerRegistry[handlerName] = handler;
    }

    getAjaxHandler(handlerName = null) {
        if (!handlerName) {
            handlerName = 'default';
        }

        if (!(handlerName in this.ajaxHandlerRegistry)) {
            throw `No ajax handler registered with name ${handlerName}`;
        }



        return this.ajaxHandlerRegistry[handlerName];
    }

    // *** Bind *** //

    bind() {
        new Bind($('body'), false);
    }

    bindNewDom($e) {
        new Bind($e, true);
    }
}