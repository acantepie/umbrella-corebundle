import AjaxUtils from "umbrella_core/utils/AjaxUtils";

export default class Bind {

    constructor($view, newDom = true) {
        this.$view = $view;

        this.bindTooltip();
        this.bindToast();
        this.bindPopover();

        if (!newDom) {
            this.bindForm();
            this.bindLink();
        }
    }

    bindTooltip() {
        if ($.tooltip) {
            this.$view.find('[data-toggle="tooltip"]').tooltip({
                container: this.$view
            });
        }
    }

    bindToast() {
        if ($.toast) {
            this.$view.find('[data-toggle="toast"]').each((i, e) => {
                $.toast($(e).data('options'));
            })
        }
    }

    bindPopover() {
        if ($.popover) {
            this.$view.find('[data-toggle="popover"]').popover({
                container: this.$view
            });
        }
    }

    // if you don't want your form was bind : use class no-bind
    bindForm() {
        this.$view.on('click', '[data-xhr]:not(form):not(.no-bind)', (e) => {
            e.preventDefault();
            AjaxUtils.handleLink($(e.currentTarget));
        });
    }

    // if you don't want your link was bind : use class no-bind
    bindLink() {
        this.$view.on('submit', 'form[data-xhr]:not(.no-bind)', (e) => {
            e.preventDefault();
            AjaxUtils.handleForm($(e.currentTarget));
        });
    }
}