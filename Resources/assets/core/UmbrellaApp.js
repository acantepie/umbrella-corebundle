import Component from "umbrella_core/core/Component";
import Bind from "umbrella_core/core/Bind";
import JsResponseHandler from "umbrella_core/jsresponse/JsResponseHandler";

export default class UmbrellaApp {

    constructor() {
        this.componentRegistry = {};
        this.jsResponseHandler = new JsResponseHandler();
    }

    init($container = null) {
        if (null === $container) {
            this.mount();
            this.bind($('body'));
        } else {
            this.mount($container);
            this.bind($container);
        }
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
            console.error(`Can't use component ${componentClass.prototype.constructor.name}, class must extends Component class`);
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

    // *** Bind *** //

    bind($container = null) {
        new Bind($container, false);
    }
}