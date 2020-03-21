require('./Collection.scss');

class Collection {

    constructor($elt) {
        this.$view = $elt;

        this.prototype = this.$view.data('prototype');
        this.prototype_name = this.$view.data('prototype-name');
        this.index = this.$view.data('index');
        this.max_number = this.$view.data('max-number');

        this.init();
        this.bind();
    }

    init() {
        // redefine index with use max idx foreach element
        let maxIdx = 0;
        this.$view.find(".js-row").each((d, e) => {
            let v = parseInt($(e).attr("data-idx"));
            if (v > maxIdx)
                maxIdx = v;
        });
        this.index = maxIdx;
    }

    bind() {
        // bind add row
        this.$view.on('click', '.js-add-row', (e) => {
            // avoid default action
            e.preventDefault();
            e.stopPropagation();


            this.index += 1;
            const regexp = new RegExp(this.prototype_name, "g");
            const $newRow = $(this.prototype.replace(regexp, this.index));

            this.$view.data('index', this.index);
            this.$view.find('tbody').first().append($newRow);

            // bind row
            new Form($newRow);
            Kernel.mountComponents($newRow);

            if (this.max_number && this.$view.find('tbody tr').length >= this.max_number) {
                this.$view.find(".js-add-row").hide();
            }

            this.$view.trigger('form:row:add', [$newRow]);
        });

        // bind delete row
        this.$view.on('click', '.js-del-row', (e) => {
            // avoid default action
            e.preventDefault();
            e.stopPropagation();

            $(e.currentTarget).closest('tr').remove();

            if (this.max_number && this.$view.find('tbody tr').length < this.max_number) {
                this.$view.find(".js-add-row").show();
            }

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
            this.$view.find('tbody').sortable({
                handle: ".js-sort-handler",
                forcePlaceholderSize: true,
                helper: "clone"
            });
        }
    }
}

module.exports = Collection;
