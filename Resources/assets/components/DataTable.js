import Toolbar from "./Toolbar";
import AjaxUtils from "umbrella_core/utils/AjaxUtils";
import Component from "umbrella_core/core/Component";

/**
 * Custom event :
 * draw:before
 * draw:done
 */
export default class DataTable extends Component {

    constructor($view) {
        super($view);

        this.$table = this.$view.find('table');
        this.$toolbarAction = this.$view.find('.js-umbrella-toolbar .umbrella-actions');
        this.$toolbarForm = this.$view.find('.js-umbrella-toolbar form');

        this.table = null;

        this.options = $view.data('options') || {};

        this.init();
        this.bind();

        this.timer = null;
        this.startAutoReload(this.options['poll_interval']);
    }

    init() {
        this.configureOptions();
        this.table = this.$table.DataTable(this.options);
    }

    bind() {

        // toolbar => filter form
        if (this.$toolbarForm.length) {

            new Toolbar(this.$toolbarForm, {
                'submitOnChange': true
            });

            this.$toolbarForm.on('tb:submit', () => {
                this.reload();
            });
        }

        // toolbar => action form
        if (this.$toolbarAction.length) {
            this.$toolbarAction.on('click', '.js-umbrella-action[data-send=searched]', (e) => {
                let $target = $(e.currentTarget);

                // avoid default action
                e.preventDefault();
                e.stopPropagation();

                // do ajax call and send extra params
                if ($target.data('xhr')) {
                    AjaxUtils.get({
                        url: $target.data('xhr'),
                        data: this.table.ajax.params()
                    });
                } else {
                    window.location.href = $target.attr('href') + '?' + $.param(this.table.ajax.params());
                }
            });

            this.$toolbarAction.on('click', '.js-umbrella-action[data-send=selected]', (e) => {
                let $target = $(e.currentTarget);

                // avoid default action
                e.preventDefault();
                e.stopPropagation();

                // do ajax call and send extra params
                if ($target.data('xhr')) {
                    AjaxUtils.get({
                        url: $target.data('xhr'),
                        data: this.selectedRowsIdParams()
                    });
                } else {
                    window.location.href = $target.attr('href') + '?' + $.param(this.selectedRowsIdParams());
                }
            });
        }

        // row toggle
        this.$table.on('change', '.js-toggle-widget input[type=checkbox]', (e) => {
            const $e = $(e.currentTarget);
            if ($e.is(':checked')) {
                AjaxUtils.get({url: $e.data('yes-url')});
            } else {
                AjaxUtils.get({url: $e.data('no-url')});
            }
        });

        // row re-order
        if (this.options['rowReorder']) {
            this.table.on('row-reorder', (e, diff, edit) => {
                let changeSet = [];
                for (let i = 0, ien = diff.length; i < ien; i++) {
                    let id = this.table.row(diff[i].node).id();
                    changeSet.push({
                        'id': id,
                        'old_sequence': $(diff[i].oldData).data('sequence'),
                        'new_sequence': $(diff[i].newData).data('sequence')
                    });
                }

                let ajax_url = this.options['rowReorder']['url'];
                if (ajax_url) {
                    AjaxUtils.get({
                        url: ajax_url,
                        data: {
                            'change_set': changeSet
                        }
                    });
                }
            });
        }

        // row select
        this.table.on('change', 'thead tr th.js-select input[type=checkbox]', (e) => {
            let $target = $(e.currentTarget);
            let $checkboxes = this.$table.find('tbody tr td.js-select input[type=checkbox]');
            $checkboxes.prop('checked', $target.prop('checked'));
            $checkboxes.trigger('change');
        });

        this.table.on('change', 'tbody tr td.js-select input[type=checkbox]', (e) => {
            let $target = $(e.currentTarget);
            let $tr = $target.closest('tr');
            if ($target.prop('checked')) {
                $tr.addClass('selected');
            } else {
                $tr.removeClass('selected');
            }
        });

        // default error handler
        this.bindError((e, settings, techNote, message) => {

            let html = '<tr>';
            html += '<td class="text-danger text-center" colspan="100%">';
            html += '<i class="mdi mdi-alert-circle-outline" aria-hidden="true"></i> Impossible de charger les données';
            html += '</td>';
            html += '</tr>';

            this.$view.find('tbody').html(html);
            this.stopAutoReload();
        });
    }

    configureOptions() {
        this.options['ajax']['data'] = (d) => {
            // avoid sending unused params
            delete d['columns'];
            delete d['search'];
            return {...d, ...this.options['ajax_data'], ...this.toolbarData()};
        };
        this.options['preDrawCallback'] = (settings) => {
            this.$view.trigger('draw:before');
        };
        this.options['drawCallback'] = (settings) => {
            this.$view.trigger('draw:done');

            // tooltip
            this.$view.find('[data-toggle="tooltip"]').tooltip();

            // treegrid
            if (this.options['tree']) {
                this.$table.treegrid({
                    'treeColumn': this.options['tree_column'],
                    'initialState': this.options['tree_state']
                });
            }

            // popover
            this.$table.find('[data-toggle=popover]').popover({
                container: this.$table,
                html: true
            });
        };
    }

    toolbarData() {
        return this.$toolbarForm.length
            ? this.$toolbarForm.serializeFormToJson()
            : [];
    }

    reload(paging = true) {
        this.$table.DataTable().draw(paging);
    }

    displaySpinner() {
        this.$table.find('tbody').html(this.$table.find('tbody').data('spinner'));
    }

    selectedRowsIdParams() {
        let ids = [];
        this.$table.find('tbody tr.selected[data-id]').each((e, elt) => {
            ids.push($(elt).data('id'));
        });
        return {'ids': ids};
    }

    bindError(cb) {
        this.table.on('error.dt', cb);
    }

    startAutoReload(pollInterval) {
        this.pollInterval = pollInterval;
        if (this.pollInterval > 0) {
            this.__autoReload();
        }
    }

    stopAutoReload() {
        this.pollInterval = null;
        if (this.timer) {
            clearTimeout(this.timer);
        }
    }

    __autoReload() {
        if (this.pollInterval > 0) {
            this.timer = setTimeout(() => {
                this.reload(false);
                this.__autoReload();
            }, this.pollInterval * 1000);
        }
    }
}
