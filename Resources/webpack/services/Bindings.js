const Spinner = require('umbrella_core/components/spinner/Spinner');

class Bindings {

    constructor($elt) {
        this.$elt = $elt;
        this.bind();
    }

    bind() {

        // bind xhr link
        this.$elt.on('click', 'a[data-xhr-href], button[data-xhr-href]', (e) => {
            e.stopPropagation();
            e.preventDefault();

            const $target = $(e.currentTarget);
            const once = $target.data('once');
            const url = $target.data('xhr-href');
            const confirm = $target.data('confirm');
            const spinner = $target.data('spinner');

            if (once && $target.data('clicked')) {
                return false;
            }
            $target.data('clicked', true);

            if (confirm) {
                const modal = new ConfirmModal(confirm, () => {
                    spinner ? this.spinAndGET(url) : Api.GET(url);
                });
                modal.show();
            } else {
                spinner ? this.spinAndGET(url) : Api.GET(url);
            }



            return false;
        });

        // bind xhr form
        this.$elt.on('submit', 'form[data-xhr-action]', (e) => {
            e.preventDefault();
            const $form = $(e.currentTarget);
            const once = $form.data('once');

            if (once && $form.data('submitted')) {
                return false;
            }
            $form.data('submitted', true);

            let formData = $form.serializeFiles();

            if (this.$submitButton !== undefined && this.$submitButton.name) {
                formData.append(this.$submitButton.name, this.$submitButton.value);
            }

            Api.ajax(
                $form.attr('method'),
                $form.data('xhr-action'),
                formData,
                (response) => { // success cb
                    this.$elt.find('form[data-xhr-action]').trigger('xhr_form:success', response);
                },
                () => { // error cb
                    this.$elt.find('form[data-xhr-action]').trigger('xhr_form:error');
                },
                () => { // error cb
                    this.$elt.find('form[data-xhr-action]').trigger('xhr_form:complete');
                }
            );
        });

        // bind xhr buttons
        this.$elt.on('click', ':submit', (e) => {
            this.$submitButton = e.currentTarget;
        });

        // bind bootstrap tooltips
        if (jQuery().tooltip) {
            this.$elt.find('[data-toggle="tooltip"]').tooltip();
        }

        // toastr
        if (typeof toastr !== 'undefined') {
            $('[data-toggle="toastr"]').each((i, elt) => {
                toastr[$(elt).data('type')]($(elt).html());
            });
        }

        // bind popover
        if (jQuery().popover) {
            $('[data-toggle="popover"]').popover({
                container: 'body'
            });
        }
    }

    spinAndGET(url) {
        Spinner.displaySpinner();
        Api.GET(url, null, null, null, () => {
            Spinner.hideSpinner();
        });
    }
}

module.exports = Bindings;
