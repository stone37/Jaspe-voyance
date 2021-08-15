$(document).ready(function() {
    // SideNav Button Initialization
    $('.button-collapse').sideNav({
        edge: 'left', // Choose the horizontal origin
        closeOnClick: false, // Closes side-nav on &lt;a&gt; clicks, useful for Angular/Meteor
        breakpoint: 1440, // Breakpoint for button collapse
        menuWidth: 250, // Width for sidenav
        timeDurationOpen: 300, // Time duration open menu
        timeDurationClose: 200, // Time duration open menu
        timeDurationOverlayOpen: 50, // Time duration open overlay
        timeDurationOverlayClose: 200, // Time duration close overlay
        easingOpen: 'easeOutQuad', // Open animation
        easingClose: 'easeOutCubic', // Close animation
        showOverlay: true, // Display overflay
        showCloseButton: false // Append close button into siednav
    });

    // SideNav Scrollbar Initialization
    var sideNavScrollbar = document.querySelector('.custom-scrollbar');
    var ps = new PerfectScrollbar(sideNavScrollbar);


    // Gestion des checkbox dans la liste
    let $principalCheckbox = $('#principal-checkbox'),
        $listCheckbook = $('.list-checkbook'),
        $listCheckbookLength = $listCheckbook.length,
        $listCheckbookNumber = 0,
        $btnBulkDelete = $('#entity-list-delete-bulk-btn');

    $principalCheckbox.on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $('.list-checkbook').prop('checked', true);

            $listCheckbookNumber = $listCheckbookLength;

            if ($listCheckbookLength > 0)
                $btnBulkDelete.removeClass('d-none');

        } else {
            $('.list-checkbook').prop('checked', false);
            $btnBulkDelete.addClass('d-none');

            $listCheckbookNumber = 0;
        }
    });

    $listCheckbook.on('click', function () {
        let $this = $(this);

        if ($this.prop('checked')) {
            $listCheckbookNumber++;
            $btnBulkDelete.removeClass('d-none');

            if ($listCheckbookNumber === $listCheckbookLength)
                $principalCheckbox.prop('checked', true)
        } else {
            $listCheckbookNumber--;

            if ($listCheckbookNumber === 0)
                $btnBulkDelete.addClass('d-none');

            if ($listCheckbookNumber < $listCheckbookLength)
                $principalCheckbox.prop('checked', false)
        }
    });

    let $container = $('#modal-container'),
        $checkbookContainer = $('#list-checkbook-container');

    // Gestion des suppression

    // Demand
    simpleModals($('.entity-demand-delete'), 'app_admin_demand_delete', $container);
    bulkModals($('.entity-demand-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_demand_bulk_delete', $container);

    // Product
    simpleModals($('.entity-product-delete'), 'app_admin_product_delete', $container);
    bulkModals($('.entity-product-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_product_bulk_delete', $container);

    // Order
    simpleModals($('.entity-order-delete'), 'app_admin_order_delete', $container);
    bulkModals($('.entity-order-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_order_bulk_delete', $container);

    // Shipment
    simpleModals($('.entity-shipment-delete'), 'app_admin_shipment_delete', $container);
    bulkModals($('.entity-shipment-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_demand_bulk_delete', $container);

    // Post
    simpleModals($('.entity-post-delete'), 'app_admin_post_delete', $container);
    bulkModals($('.entity-post-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_post_bulk_delete', $container);

    // Category
    simpleModals($('.entity-category-delete'), 'app_admin_category_delete', $container);
    bulkModals($('.entity-category-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_category_bulk_delete', $container);

    // Tag
    simpleModals($('.entity-tag-delete'), 'app_admin_tag_delete', $container);
    bulkModals($('.entity-tag-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_tag_bulk_delete', $container);

    // comment
    simpleModals($('.entity-comment-delete'), 'app_admin_comment_delete', $container);
    bulkModals($('.entity-comment-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_deliverer_bulk_delete', $container);

    // Client
    simpleModals($('.entity-user-delete'), 'app_admin_user_delete', $container);
    bulkModals($('.entity-user-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_user_bulk_delete', $container);

    // Administrateur
    simpleModals($('.entity-admin-delete'), 'app_admin_admin_delete', $container);
    bulkModals($('.entity-admin-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_admin_bulk_delete', $container);

    // Tax
    simpleModals($('.entity-tax-delete'), 'app_admin_tax_delete', $container);
    bulkModals($('.entity-tax-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_tax_bulk_delete', $container);

    // Shipment method
    simpleModals($('.entity-shipmentMethod-delete'), 'app_admin_shipmentMethod_delete', $container);
    bulkModals($('.entity-shipmentMethod-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_shipmentMethod_bulk_delete', $container);

    // Review
    simpleModals($('.entity-review-delete'), 'app_admin_review_delete', $container);
    bulkModals($('.entity-review-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_review_bulk_delete', $container);

    // Event
    simpleModals($('.entity-event-delete'), 'app_admin_event_delete', $container);
    bulkModals($('.entity-event-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_event_bulk_delete', $container);

    // Event
    simpleModals($('.entity-demandEvent-delete'), 'app_admin_demand_event_delete', $container);
    bulkModals($('.entity-demandEvent-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_demand_event_bulk_delete', $container);

    // Payment
    simpleModals($('.entity-payment-delete'), 'app_admin_payment_delete', $container);
    bulkModals($('.entity-payment-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_payment_bulk_delete', $container);

    // Email send
    simpleModals($('.entity-email-delete'), 'app_admin_send_mail_delete', $container);
    bulkModals($('.entity-email-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_send_mail_bulk_delete', $container);

    // Banner
    simpleModals($('.entity-banner-delete'), 'app_admin_banner_delete', $container);
    bulkModals($('.entity-banner-delete-bulk-btn a.btn-danger'), $checkbookContainer,
        'app_admin_banner_bulk_delete', $container);
});


