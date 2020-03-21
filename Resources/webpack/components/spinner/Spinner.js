require('./Spinner.scss');

class Spinner {

    static displaySpinner(label = '') {
        if ($('#umbrella-spinner').length === 0) {

            const overlay = jQuery(
                '<div id="umbrella-spinner">' +
                '<div><i class="fa fa-circle-o-notch fa-spin fa-fw" aria-hidden="true"></i><span>' +
                label +
                '</span></div>' +
                '</div>');

            overlay.appendTo(document.body);
            $('body').addClass('no-scroll-y');
        }
    }

    static updateSpinner(label = '') {
        if ($('#umbrella-spinner').length === 1) {
            $('#umbrella-spinner span').text(label);
        }
    }

    static hideSpinner() {
        $('#umbrella-spinner').remove();
        $('body').removeClass('no-scroll-y');
    }
}

module.exports = Spinner;