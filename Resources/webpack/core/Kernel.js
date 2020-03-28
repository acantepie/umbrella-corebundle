import KernelComponent from "umbrella_core/core/KernelComponent";

export default class Kernel {

    constructor() {
        this.registry = {};
    }


    registerComponent(id, definition) {
        if (!definition instanceof KernelComponent) {
            console.error(`Can'register component ${id}, he must extends KernelComponent`);
        } else {
            this.registry[id] = definition;
        }
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

    listDefinition() {
        console.log(this.registry);
    }
}