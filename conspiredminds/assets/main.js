// Bootstrap DataTable
$(document).ready(function () {
    var table = $('#pbs').DataTable({
        responsive: true,
        lengthMenu: [10, 20, 30, 40, 50, 100, 500],
        dom: 'Blfrtip',
    });
    
    // For Tab
    $('.cdc-tab-title ul li:first-child a').addClass('active');
    $('.cdct-content-box').hide();
    $('.cdct-content-box:first').show();

    $('.cdc-tab-wrap .cdc-tab-title ul li a').click(function(){
        $('.cdc-tab-wrap .cdc-tab-title ul li a').removeClass('active');
        $(this).addClass('active');
        $('.cdc-tab-wrap .cdct-content-box').hide();
        var activeTab = $(this).attr('href');
        $(activeTab).fadeIn();
        return false;
    });
    //Property list Naviagtion on property summary tab
    $('.dashboard').click(function(){
        $('.cdc-tab-wrap .cdc-tab-title ul li a').removeClass('active');
        $('.cdc-tab-wrap .cdc-tab-title ul li').each(function () {
            var dashboardLink = $('.dashboard').attr('href');
            let anchor = $(this).children('a');
            if ($(anchor).attr('href') == dashboardLink) {
                $(anchor).addClass('active');
            }
        });
        $('.cdc-tab-wrap .cdct-content-box').hide();
        var dashboardTab = $(this).attr('href');
        $(dashboardTab).fadeIn();
        return false;
    });

    // Table Design
    var dt_buttons = '<div class="dt-buttons-wrap"> </div>';
    jQuery('.dataTables_wrapper').prepend ('<div class="datatable-wrap"></div>');
    jQuery('.dataTables_wrapper').prepend ('<div class="filter-row"></div>');
    jQuery('.dataTables_length').appendTo('.filter-row');
    jQuery(dt_buttons).appendTo('.filter-row');
    jQuery('.dt-buttons').appendTo('.dt-buttons-wrap');
    jQuery('.dataTables_filter').appendTo('.filter-row');
    jQuery('#pbs').appendTo('.datatable-wrap');


    $('.mp-tab-title ul li:first-child a').addClass('active');
    $('.mange-prop-content .mange-prop-box').hide();
    $('.mange-prop-content .mange-prop-box:first').show();

    $('.mp-tab-title ul li a').click(function(){
        $('.mp-tab-title ul li a').removeClass('active');
        $(this).addClass('active');
        $('.mange-prop-content .mange-prop-box').hide();
        var activeTab = $(this).attr('href');
        $(activeTab).fadeIn();
        return false;
    });

});