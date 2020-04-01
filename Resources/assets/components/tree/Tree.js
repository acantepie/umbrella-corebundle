import KernelComponent from "umbrella_core/core/KernelComponent";
import './tree.scss';

export default class Tree extends KernelComponent {

    constructor($elt) {
        super();

        this.$view = $elt;
        this.options = $elt.data('options') || {};

        this.configureOptions();
        this.render();
        this.bind();
    }

    configureOptions() {
        let defaultOptions = {
            collapsable: true,
            start_expanded: true,
        };

        this.options = {...defaultOptions, ...this.options};
    }

    render() {
        $.ajax({
            url: this.options['load_url'],
            success: (response) => {
                this.$view.html(response.html);
                this.initTree();
            }
        });
    }

    reload() {
        this.render();
    }

    bind() {
        this.$view.on('click', '.js-umbrella-tree .js-collapse-handle', (e) => {
            let $target = $(e.currentTarget);
            $target.closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
        });
    }

    initTree() {
        let $tree = this.$view.find('.js-umbrella-tree');
        $tree.nestedSortable({
            forcePlaceholderSize: true,
            handle: '.node-content',
            items: 'li',
            toleranceElement: '> div',
            isTree: true,
            startCollapsed: !this.options['start_expanded'],
            maxLevels: parseInt(this.options['max_depth']),
            relocate: (e, object) => {
                let prev_node_id, parent_node_id, node_id;

                let $node = $(object.item[0]).closest('li');

                let $root = $node.closest('ol');
                let $parent = $node.parent().closest('li');
                let $prev_node = $node.prev();

                node_id = $node.data('id');
                if ($prev_node.length) {
                    prev_node_id = $prev_node.data('id');
                } else if ($parent.length) {
                    parent_node_id = $parent.data('id');
                } else if ($root.length) {
                    parent_node_id = $root.data('root-id');
                }

                let params = {prev_node_id, parent_node_id, node_id};

                if (this.options['relocate_url']) {
                    Api.POST(this.options['relocate_url'], params);
                }
            }
        });

        if (!this.options['draggable']) {
            $tree.find('li').on('mousedown', (e) => {
                e.stopPropagation();
            });
        }
    }

}
