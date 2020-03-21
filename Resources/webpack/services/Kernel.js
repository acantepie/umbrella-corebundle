class Kernel {

    constructor() {
        this.factories = [];
    }

    registerComponent(uname, factory) {

        let wrapper = factory;

        if (isNativeClass(factory)) {
            wrapper = function ($html) {
                return new factory($html);
            };
        }

        this.factories[uname] = wrapper;
        return this;
    }

    getComponent(component_id) {
        const $node = $('#' + component_id);
        if ($node)
            return $node.data('component');
        return null;
    }

    createComponent($html) {

        const uname = $html.data('mount');

        if (!uname)
            return null;

        const factory = this.factories[uname];
        if (!factory) {
            console.log(uname + ' component not found');
            return null;
        }

        return factory($html);
    }

    mountComponents($view) {
        const self = this;

        $view.find('[data-mount]').each(function (idx, node) {

            let $node = $(node);

            if ($node.data('component'))
                return null;

            let component = self.createComponent($node);
            $node.data('component', component);

        });
    }

}

function isNativeClass(thing) {
    return typeof thing === 'function' && thing.hasOwnProperty('prototype'); // && !thing.hasOwnProperty('arguments');
}

module.exports = Kernel;