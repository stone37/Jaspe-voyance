$(document).ready(function() {
    /**
     * ##############
     * Upload File
     * ##############
     */
    function readURL(input) {

        let url = input.value;
        let ext = url.substring(url.lastIndexOf('.')+1).toLowerCase();

        if (input.files && input.files[0] && (ext === 'gif' || ext === 'png' || ext === 'jpeg' || ext === 'jpg')) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#image-view').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0])
        }
    }

    $('#entity-image').change(function () { readURL(this)});


    // Gestion des localisation
    let $citySelect = $('select.app-location-city'),
        $districtSelect = $('select.app-location-district');

    $citySelect.on("change", function() {
        let $this = $(this);

        if ($this.val()) {
            $.ajax({
                url: Routing.generate('app_districts_by_city', {id: $this.val()}),
                type: 'GET',
                success: function(data){
                    let $result = $.parseJSON(data);

                    $districtSelect.empty().html(" ");

                    let $title = $("<option>").attr({
                        value: "",
                        selected: "selected"
                    }).text("Zone");

                    $districtSelect.append($title);

                    $.each($result, function(i, obj) {
                        let $content = $("<option>").attr({value: obj}).text(i);

                        $districtSelect.append($content);
                    });

                    $districtSelect.materialSelect();
                }
            });
        }
    });

    // Settings block
    $(v).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-voyance-block').addClass('active')
        } else {
            $('#app-setting-voyance-block').removeClass('active')
        }
    });

    $(vCabinet).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-voyance-cabinet-block').addClass('active')
        } else {
            $('#app-setting-voyance-cabinet-block').removeClass('active')
        }
    });

    $(vMail).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-voyance-mail-block').addClass('active')
        } else {
            $('#app-setting-voyance-mail-block').removeClass('active')
        }
    });

    $(vPhone).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-voyance-phone-block').addClass('active')
        } else {
            $('#app-setting-voyance-phone-block').removeClass('active')
        }
    });

    $(soins).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-soins-block').addClass('active')
        } else {
            $('#app-setting-soins-block').removeClass('active')
        }
    });

    $(blog).on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('#app-setting-blog-block').addClass('active')
        } else {
            $('#app-setting-blog-block').removeClass('active')
        }
    });



    /**
     * Affiche des notifications
     *
     * @param titre
     * @param message
     * @param options
     * @param type
     */
    function notification (titre, message, options, type) {
        if(typeof options == 'undefined') options = {};
        if(typeof type == 'undefined') type = "info";

        toastr[type](message, titre, options);
    }


});




