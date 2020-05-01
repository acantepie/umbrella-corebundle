import dragula from 'dragula';
import Component from "umbrella_core/core/Component";

export default class Collection extends Component {

    constructor($view) {
        super($view);

        this.prototype = this.$view.data('prototype');
        this.prototype_name = this.$view.data('prototype-name');
        this.index = this.$view.data('index');
        this.maxLength = this.$view.data('maxLength');

        this.toggleAdd();
        this.bind();
    }

    bind() {
        // bind add row
        this.$view.on('click', '.js-add-row', (e) => {
            e.preventDefault();
            this.index += 1;
            const regexp = new RegExp(this.prototype_name, "g");
            const $newRow = $(this.prototype.replace(regexp, this.index));

            this.$view.data('index', this.index);
            this.$view.find('tbody').first().append($newRow);

            // bind row
            app.mount($newRow);

            this.toggleAdd();
            this.$view.trigger('form:row:add', [$newRow]);
        });

        // bind delete row
        this.$view.on('click', '.js-del-row', (e) => {
            e.preventDefault();

            $(e.currentTarget).closest('tr').remove();
            this.toggleAdd();

            this.$view.trigger('form:row:del');
        });

        // before submit => refresh input row order
        this.$view.closest('form[data-mount="Form"]').on('submit', () => {
            let order = 0;
            this.$view.find('.js-order').each((i, e) => {
                $(e).val(order);
                order++;
            });
        });

        // sorting
        if (this.$view.data('sortable')) {
            dragula({
                containers: [this.$view.find('tbody')[0]],
                moves: function (el, source, handle, sibling) {
                    return handle.classList.contains('js-sort-handle') || handle.parentNode.classList.contains('js-sort-handle');
                },
                mirrorContainer: this.$view.find('tbody')[0]
            });
        }
    }

    count() {
        return this.$view.find('tbody tr').length;
    }

    toggleAdd() {
        if (this.maxLength > 0) {
            this.$view.find('.js-add-row').toggleClass('d-none', this.count() >= this.maxLength);
        }
    }
}
