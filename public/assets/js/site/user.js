(function() {
    let $container = $('#modal-container');

    modal($('.app-user-advert-delete'), 'app_dashboard_advert_delete', $container);


})();


function modal(element, route, elementRacine) {
    element.click(function (e) {
        e.preventDefault();

        let $id    = $(this).attr('data-id');
        let $modal = '#confirm'+$id;

        $.ajax({
            url: Routing.generate(route, {
                id: $id,
            }),
            type: 'GET',
            success: function(data) {
                $(elementRacine).html(data.html);
                $($modal).modal()
            }
        });
    });
}