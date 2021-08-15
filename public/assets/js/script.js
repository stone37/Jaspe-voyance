$(document).ready(function() {

    // Gestion des notification serveur
    flashes($('.notify'));

    $(window).scroll(function() {
        let scroll = $(window).scrollTop();
        let currScrollTop = $(this).scrollTop();

        if (scroll >= 200)
            $('#btn-smooth-scroll').removeClass('d-none').addClass('animated fadeInRight');
        else
            $('#btn-smooth-scroll').addClass('d-none').removeClass('animated fadeInRight');

        lastScrollTop = currScrollTop;
    });



});

let options = {
    "closeButton": false, // true/false
    "debug": false, // true/false
    "newestOnTop": false, // true/false
    "progressBar": true, // true/false
    "positionClass": "toast-top-right", // toast-top-right / toast-top-left / toast-bottom-right / toast-bottom-left
    "preventDuplicates": false, //true/false
    "onclick": null,
    "showDuration": "300", // in milliseconds
    "hideDuration": "1000", // in milliseconds
    "timeOut": "8000", // in milliseconds
    "extendedTimeOut": "1000", // in milliseconds
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

/**
 * Affiche des notifications
 *
 * @param titre
 * @param message
 * @param options
 * @param type
 */
function notification(titre, message, options, type) {
    if(typeof options == 'undefined') options = {};
    if(typeof type == 'undefined') type = "info";

    toastr[type](message, titre, options);
}

function simpleModals(element, route, elementRacine) {
    element.click(function (e) {
        e.preventDefault();

        let $id = $(this).attr('id'), $modal = '#confirm'+$id;

        $.ajax({
            url: Routing.generate(route, {id: $id}),
            type: 'GET',
            success: function(data) {
                $(elementRacine).html(data.html);
                $($modal).modal()
            }
        });
    });
}

function bulkModals(element, container, route, elementRacine) {
    element.click(function (e) {
        e.preventDefault();

        let ids = [];

        container.find('.list-checkbook').each(function () {
            if ($(this).prop('checked')) {
                ids.push($(this).val());
            }
        });

        if (ids.length) {
            let $modal = '#confirmMulti'+ids.length;

            $.ajax({
                url: Routing.generate(route),
                data: {'data': ids},
                type: 'GET',
                success: function(data) {
                    $(elementRacine).html(data.html);
                    $($modal).modal()
                }
            });
        }
    });

}

function flashes (selector) {
    selector.each(function (index, element) {
        if ($(element).html() !== undefined) {
            toastr[$(element).attr('app-data')]($(element).html());
        }
    })
}


