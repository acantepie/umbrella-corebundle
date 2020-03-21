require('./datatable.scss');

const Toolbar = require('../toolbar/Toolbar');
const Spinner = require('umbrella_core/components/spinner/Spinner');

/**
 * Custom event :
 * draw:before
 * draw:done
 */
class DataTable {

    constructor($elt) {
        this.$view = $elt;
        this.$table = this.$view.find('.js-umbrella-datatable');
        this.$toolbarAction = this.$view.find('.js-umbrella-toolbar .umbrella-actions');
        this.$toolbarForm = this.$view.find('.js-umbrella-toolbar form');

        this.table = null;

        this.options = $elt.data('options')||{};

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
                'submitOnChange': this.options['toolbarSubmittedOnChange'] === true
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
                if ($target.data('xhr-href')) {
                    Api.GET($target.data('xhr-href'), this.table.ajax.params());
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
                if ($target.data('xhr-href')) {
                    Api.GET($target.data('xhr-href'), this.selectedRowsIdParams());
                } else {
                    window.location.href = $target.attr('href') + '?' + $.param(this.selectedRowsIdParams());
                }
            });
        }

        // row re-order
        if (this.options['rowReorder']) {
            this.table.on('row-reorder', (e, diff, edit) => {
                let changeSet = [];
                for (let i = 0, ien = diff.length; i < ien; i++) {
                    let id = this.table.row(diff[i].node).id();
                    changeSet.push({
                        'id' : id,
                        'old_sequence' : $(diff[i].oldData).data('sequence'),
                        'new_sequence' : $(diff[i].newData).data('sequence')
                    });
                }

                let ajax_url = this.options['rowReorder']['url'];
                if (ajax_url) {
                    Api.GET(ajax_url, {'change_set' : changeSet});
                }
            });
        }

        // row click
        if (this.options['rowClick']) {
            this.table.on('click', 'tbody tr td:not(.disable-row-click)', (e) => {
                let $tr = $(e.currentTarget).closest('tr');
                let id = $tr.attr('id');

                let url = this.options['rowClick']['url'].replace('123456789', id);
                let xhr = this.options['rowClick']['xhr'];

                if (!id) {
                    return;
                }

                if (xhr) {
                    if (this.options['rowClick']['spinner']) {
                        Spinner.displaySpinner();
                        Api.GET(url, null, null, null, () => {
                            Spinner.hideSpinner();
                        });
                    } else {
                        Api.GET(url);
                    }
                    return;
                }

                if (this.options['rowClick']['target_blank']) {
                    window.open(url, '_blank');
                    return;
                }

                window.location = url;
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
            html += '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Impossible de charger les données';
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

            return {...d, ...this.toolbarData()};
        };
        this.options['preDrawCallback'] = (settings) => {
            this.$view.trigger('draw:before');
        };
        this.options['drawCallback'] = (settings) => {
            this.$view.trigger('draw:done');

            // tooltip
            this.$view.find('[data-toggle="tooltip"]').tooltip();

            // popover
            this.$table.find('[data-toggle=popover]').popover({
                container: this.$table,
                html: true
            });
        };
    }

    toolbarData() {
        return this.$toolbarForm.length
            ? this.$toolbarForm.serializeObject()
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
        this.$table.find('tbody tr.selected').each((e, elt) => {
            ids.push($(elt).attr('id'));
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

module.exports = DataTable;
