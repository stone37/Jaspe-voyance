$(document).ready(function() {

    /**
     * Newsletter modal
     */
    if (!sessionStorage.getItem('newsletter-modal')) {
        $("#newsletterModalForm").modal();
        sessionStorage.setItem('newsletter-modal', true);
    }

    newsletter($('#newsletter-form'));
    newsletter($('#newsletter-footer-form'))
});

function newsletter (element) {
    element.submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(element).attr('action'),
            type: $(element).attr('method'),
            data: element.serialize(),
            success: function(data) {
                if (data.success) {
                    notification("Newsletter", data.message, {}, 'success')
                } else {
                    let errors = $.parseJSON(data.errors);

                    $(errors).each(function (key, value) {
                        notification("Newsletter", value, {}, 'error')
                    });
                }
            }
        })
    });
}
