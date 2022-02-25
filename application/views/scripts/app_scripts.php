<?php if (isset($datepicker)) { ?>
<script src="<?=base_url()?>resource/js/slider/bootstrap-slider.js"></script>
<script src="<?=base_url()?>resource/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?=base_url()?>resource/js/bootstrap-datepicker/locales/bootstrap-datepicker.<?=(lang('lang_code') == 'en' ? 'en-GB': lang('lang_code'))?>.min.js"></script>

<script type="text/javascript">
(function($){
"use strict"; 
    $('.datepicker-input').datepicker({
        todayHighlight: true,
        todayBtn: "linked",
        autoclose: true
    });
})(jQuery);
</script>
<?php } ?>

<?php if (isset($form)) { ?>
<script src="<?=base_url()?>resource/js/file-input/bootstrap-filestyle.min.js"></script>
<script src="<?=base_url()?>resource/js/parsley/parsley.min.js"></script>
<script src="<?=base_url()?>resource/js/parsley/parsley.extend.js"></script>
<?php } ?>

<?php
if (isset($datatables)) {
    $sort = strtoupper(config_item('date_picker_format'));
?>
<script src="<?=base_url()?>resource/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>resource/js/datatables/dataTables.bootstrap.min.js"></script>

<script src="<?=base_url()?>resource/js/datatables/datetime-moment.js"></script>
<script type="text/javascript">
(function($){
"use strict"; 
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "currency-pre": function (a) {
                a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
                return parseFloat( a ); },
            "currency-asc": function (a,b) {
                return a - b; },
            "currency-desc": function (a,b) {
                return b - a; }
        });
        $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
                for(var iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                        oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                oSettings.oPreviousSearch.sSearch = '';

                if(typeof bDraw === 'undefined') bDraw = true;
                if(bDraw) this.fnDraw();
        }

        $(document).ready(function() {

        $.fn.dataTable.moment('<?=$sort?>');
        $.fn.dataTable.moment('<?=$sort?> HH:mm');

        var oTable1 = $('.AppendDataTables').dataTable({
        "bProcessing": true,
        "sDom": "<'row'<'col-sm-4'l><'col-sm-8'f>r>t<'row'<'col-sm-4'i><'col-sm-8'p>>",
        "sPaginationType": "full_numbers",
        "iDisplayLength": <?=config_item('rows_per_table')?>,
        "oLanguage": {
                "sProcessing": "<?=lang('processing')?>",
                "sLoadingRecords": "<?=lang('loading')?>",
                "sLengthMenu": "<?=lang('show_entries')?>",
                "sEmptyTable": "<?=lang('empty_table')?>",
                "sInfo": "<?=lang('pagination_info')?>",
                "sInfoEmpty": "<?=lang('pagination_empty')?>",
                "sInfoFiltered": "<?=lang('pagination_filtered')?>",
                "sInfoPostFix":  "",
                "sSearch": "<?=lang('search')?>:",
                "sUrl": "",
                "oPaginate": {
                        "sFirst":"<?=lang('first')?>",
                        "sPrevious": "<?=lang('previous')?>",
                        "sNext": "<?=lang('next')?>",
                        "sLast": "<?=lang('last')?>"
                }
        },
        "tableTools": {
                    "sSwfPath": "<?=base_url()?>resource/js/datatables/tableTools/swf/copy_csv_xls_pdf.swf",
              "aButtons": [
                      {
                      "sExtends": "csv",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "xls",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "pdf",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
              ],
        },
        "aaSorting": [],
        "aoColumnDefs":[{
                    "aTargets": ["no-sort"]
                  , "bSortable": false
              },{
                    "aTargets": ["col-currency"]
                  , "sType": "currency"
              }]
        });
            $("#table-tickets").dataTable().fnSort([[0,'desc']]);
            $("#table-tickets-archive").dataTable().fnSort([[1,'desc']]);           
            $("#table-files").dataTable().fnSort([[2,'desc']]);
            $("#table-links").dataTable().fnSort([[0,'asc']]);
            $("#table-clients").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-1").dataTable().fnSort([[1,'asc']]);
            $("#table-client-details-2").dataTable().fnSort([[2,'desc']]);
            $("#table-client-details-3").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-4").dataTable().fnSort([[1,'asc']]);
            $("#table-templates-1").dataTable().fnSort([[0,'asc']]);
            $("#table-templates-2").dataTable().fnSort([[0,'asc']]);
            $("#table-invoices").dataTable().fnSort([[0,'desc']]);
            $("#table-payments").dataTable().fnSort([[0,'desc']]);
            $("#table-users").dataTable().fnSort([[4,'desc']]);
            $("#table-rates").dataTable().fnSort([[0,'asc']]);
            $("#table-stuff").dataTable().fnSort([[0,'asc']]);
            $("#pages").dataTable().fnSort([[0,'asc']]);
            $("#table-activities").dataTable().fnSort([[0,'desc']]);
             $("#table-strings").DataTable().page.len(-1).draw();
            if ($('#table-strings').length == 1) { $('#table-strings_length, #table-strings_paginate').remove(); $('#table-strings_filter input').css('width','200px'); }


            $('#save-translation').on('click', function (e) {
            e.preventDefault();
            oTable1.fnResetAllFilters();
            $.ajax({
                url: base_url+'settings/translations/save/?settings=translations',
                type: 'POST',
                data: { json : JSON.stringify($('#form-strings').serializeArray()) },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.backup-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('operation_successful')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click', '.restore-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_restored_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.submit-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_submitted_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click','.active-translation',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) { isActive = 1; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { active: isActive },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });

        $(".menu-view-toggle").on('click',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var vis = 1;
            if ($(this).hasClass('btn-success')) { vis = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { visible: vis, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });

        $(".cron-enabled-toggle").on('click',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var ena = 1;
            if ($(this).hasClass('btn-success')) { ena = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { enabled: ena, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });


        $('[data-rel=tooltip]').tooltip();
});
})(jQuery);
</script>
<?php }  ?>

<?php if (isset($iconpicker)) { ?>
<script type="text/javascript" src="<?=base_url()?>resource/js/iconpicker/fontawesome-iconpicker.min.js"></script>
<script type="text/javascript">
(function($){
"use strict";
    $(document).ready(function () {
            $('#site-icon').iconpicker({hideOnSelect: true, placement: 'bottomLeft'});
            $('.menu-icon').iconpicker().on('iconpickerSelected',function(event){
                var role = $(this).attr('data-role');
                var target = $(this).attr('data-href');
                $(this).siblings('div.iconpicker-container').hide();
                $.ajax({
                    url: target,
                    type: 'POST',
                    data: { icon: event.iconpickerValue, access: role  },
                    success: function() {},
                    error: function(xhr) {}
                });
            });
    });
})(jQuery);
</script>
<?php } ?>

<?php if (isset($sortable)) { ?>
<script type="text/javascript" src="<?=base_url()?>resource/js/sortable/jquery-sortable.js"></script>
<script type="text/javascript">
    var t1, t2, t3, t4, t5;
    $('#inv-details, #est-details').sortable({
        cursorAt: { top: 20, left: 0 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t1); t1 = setTimeout('saveOrder()', 500); }
    });
    $('#menu-admin').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t2); t2 = setTimeout('saveMenu(\'admin\',1)', 500); }
    });
    $('#menu-client').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t3); t3 = setTimeout('saveMenu(\'client\',2)', 500); }
    });
    $('#menu-staff').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t4); t4 = setTimeout('saveMenu(\'staff\',3)', 500); }
    });
    $('#cron-jobs').sortable({
        cursorAt: { top: 20, left: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t5); t5 = setTimeout('setCron()', 500); }
    });

    function saveOrder() {
        var data = $('.sorted_table').sortable("serialize").get();
        var items = JSON.stringify(data);
        var table = $('.sorted_table').attr('type');
        $.ajax({
            url: "<?=base_url()?>"+table+"/items/reorder/",
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });

    }
    function saveMenu(table, access) {
        var data = $("#menu-"+table).sortable("serialize").get();
        var items = JSON.stringify(data);
        $.ajax({
            url: "<?=base_url()?>settings/hook/reorder/"+access,
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });
    }

    function setCron() {
        var data = $('#cron-jobs').sortable("serialize").get();
        var items = JSON.stringify(data);
        $.ajax({
            url: "<?=base_url()?>settings/hook/reorder/1",
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });
    }
</script>
<?php } ?>



<?php if (isset($nouislider)) { ?>
<script type="text/javascript" src="<?=base_url()?>resource/js/nouislider/jquery.nouislider.min.js"></script>
<script type="text/javascript">
(function($){
"use strict";
$(document).ready(function () {     

    var invoiceHeight = $('#invoice-logo-height').val();
    $('#invoice-logo-slider').noUiSlider({
            start: [ invoiceHeight ],
            step: 1,
            connect: "lower",
            range: {
                'min': 30,
                'max': 150
            },
            format: {
                to: function ( value ) {
                    return Math.floor(value);
                },
                from: function ( value ) {
                    return Math.floor(value);
                }
            }
    });
    $('#invoice-logo-slider').on('slide', function() {
        var invoiceHeight = $(this).val();
        var invoiceWidth = $('.invoice_image img').width();
        $('#invoice-logo-height').val(invoiceHeight);
        $('#invoice-logo-width').val(invoiceWidth);
        $('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').parent().find('.tooltip-inner').text(invoiceHeight+'px');
        $('.invoice_image img').css('height',invoiceHeight+'px');
        $('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
    });

    $('#invoice-logo-slider').on('change', function() {
        var invoiceHeight = $(this).val();
        var invoiceWidth = $('.invoice_image img').width();
        $('#invoice-logo-height').val(invoiceHeight);
        $('#invoice-logo-width').val(invoiceWidth);
        $('.invoice_image').css('height',invoiceHeight+'px');
        $('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
    });

    $('#invoice-logo-slider').on('mouseover', function() {
        var invoiceHeight = $(this).val();
        $('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').tooltip('show');
    });

});
})(jQuery);
</script>
<?php } ?>
 
 <script>     

$("#pages").each(function() {
    var dt = $(this).DataTable({
        'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'render': function(data, type, full, meta) {
          //return '<label><input type="checkbox" class="minimal" name="id[]" value="' + $('<div/>').text(data).html() + '"></label>';
          return '<div class="pretty info smooth"><input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '"></div>';
        }
      }],
      'order': [[0, 'asc']]
    });

    $('#dt-select-all').on('click', function() {
      var rows = dt.rows({ 'search': 'applied'}).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
      if($('input[type="checkbox"]:checked', rows).length) {
        $('.delete_multi').addClass('show');
      } else {
        $('.delete_multi').removeClass('show');
      }
    });

    $("#pages tbody").on('change', 'input[type="checkbox"]', function() {
      if(!this.checked) {

        if($('input[type="checkbox"]:checked', r).length) {
          $('.delete_multi').addClass('show');
        } else {
          $('.delete_multi').removeClass('show');
        }
        var el = $('#dt-select-all').get(0);
        if(el && el.checked && ('indeterminate' in el)) {
          el.indeterminate = true;
        }
      } else {
        var r = dt.rows({ 'search': 'applied'}).nodes();
        if($('input[type="checkbox"]:checked', r).length) {
          $('.delete_multi').addClass('show');
        } else {
          $('.delete_multi').removeClass('show');
        }
      }
    });

  });
</script>

<?php if (isset($attach_slip)) { ?>
<script type="text/javascript">
 
    $(document).ready(function(){
        $("#attach_slip").on('click', function(){
           //if checked
            if($("#attach_slip").is(":checked")){
                $("#attach_field").show("fast");
                }else{
                    $("#attach_field").hide("fast");
                }
        });
    });
 
</script>
<?php } ?>


<?php 
$last = $this->uri->total_segments(); 
if ($this->uri->segment(1) != 'accounts' 
&& $this->uri->segment(1) != 'domains' 
&& $this->uri->segment(1) != 'orders' 
|| (null != $this->uri->segment(2) && $this->uri->segment(2) == 'add_order')) {
if($this->session->flashdata('message')){
$message = $this->session->flashdata('message');
$alert = $this->session->flashdata('response_status'); ?>
<script type="text/javascript">
(function($){
"use strict"; 
    $(document).ready(function(){
        swal({
            title: "<?=lang($alert)?>",
            text: "<?=$message?>",
            type: "<?=$alert?>",
            timer: 5000,
            confirmButtonColor: "#38354a"
        });
});
})(jQuery);
</script>
<?php } } ?>

<?php if (isset($typeahead)) { ?>
    
<script type="text/javascript">
    (function(){
    "use strict";

    $(document).ready(function(){

        var scope = $('#auto-item-name').attr('data-scope');
        if (scope == 'invoices') {

        var substringMatcher = function(strs) {
          return function findMatches(q, cb) {
            var substrRegex;
            var matches = [];
            substrRegex = new RegExp(q, 'i');
            $.each(strs, function(i, str) {
              if (substrRegex.test(str)) {
                matches.push(str);
              }
            });
            cb(matches);
          };
        };

        $('#auto-item-name').on('keyup',function(){ $('#hidden-item-name').val($(this).val()); });

        $.ajax({
            url: base_url + scope + '/autoitems/',
            type: "POST",
            data: {},
            success: function(response){
                $('.typeahead').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 2
                    },
                    {
                    name: "item_name",
                    limit: 10,
                    source: substringMatcher(response)
                });
                $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                    $.ajax({
                        url: base_url + scope + '/autoitem/',
                        type: "POST",
                        data: {name: suggestion},
                        success: function(response){
                            $('#hidden-item-name').val(response.item_name);
                            $('#auto-item-desc').val(response.item_desc).trigger('keyup');
                            $('#auto-quantity').val(response.quantity);
                            $('#auto-unit-cost').val(response.unit_cost);
                        }
                    });
                });
            }
        });
    }

    });
})(jQuery);
</script>
<?php } ?>
 


<?php if (isset($menus)) { ?>
    <script>
    var current_group_id = <?php if (!empty($group_id)) {
            echo $group_id;
        } ?>;
 
    </script>
   


    <script type="text/javascript" src="<?=base_url()?>resource/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?=base_url()?>resource/js/nestedSortable/jquery.mjs.nestedSortable.js"></script>
  
    <script type="text/javascript">             
  
                /* highlight current menu group
                ------------------------------------------------------------------------- */
                $('#menu-group li[id="group-' + current_group_id + '"]').addClass('current');

                /* global ajax setup
                ------------------------------------------------------------------------- */
                $.ajaxSetup({
                    type: 'GET',
                    datatype: 'json',
                    timeout: 20000
                });
                $(document).ajaxStart(function () {
                    $('#loading').show();
                });
                $(document).ajaxStop(function () {
                    $('#loading').hide();
                });

                /* modal box
                ------------------------------------------------------------------------- */
                var gbox = {
                    defaults: {
                        autohide: false,
                        buttons: {
                            'Close': function () {
                                gbox.hide();
                            }
                        }
                    },
                    init: function () {
                        var winHeight = $(window).height();
                        var winWidth = $(window).width();
                        var box =
                            '<div id="gbox">' +
                            '<div id="gbox_content" ></div>' +
                            '</div>' +
                            '<div id="gbox_bg"></div>';

                        $('body').append(box);

                        $('#gbox').css({
                            top: '15%',
                            left: winWidth / 2 - $('#gbox').width() / 2
                        });

                        $('#gbox_close, #gbox_bg').click(gbox.hide);
                    },
                    show: function (options) {
                        var options = $.extend({}, this.defaults, options);
                        var options_temp = this.defaults;
                        switch (options.type) {
                            case 'ajax':
                                options_temp.content = '<div id="gbox_loading">Loading...<div>';
                                gbox._show(options_temp);
                                $.ajax({
                                    type: 'GET',
                                    global: false,
                                    datatype: 'html',
                                    url: options.url,
                                    success: function (data) {
                                        options.content = data;
                                        gbox._show(options);
                                    }
                                });
                                break;
                            default:
                                this._show(options);
                                break;
                        }
                    },
                    _show: function (options) {
                        $('#gbox_footer').remove();
                        if (options.buttons) {
                            $('#gbox').append('<div id="gbox_footer"></div>');
                            $.each(options.buttons, function (k, v) {
                                var buttonclass = '';
                                if (k == 'Save' || k == 'Yes' || k == 'OK') {
                                    buttonclass = 'btn btn-success';
                                } else {
                                    buttonclass = 'btn btn-danger';
                                }
                                $('<button></button>').addClass(buttonclass).text(k).click(v).appendTo('#gbox_footer');
                            });
                        }

                        $('#gbox, #gbox_bg').fadeIn();
                        $('#gbox_content').html(options.content);
                        $('#gbox_content input:first').focus();
                        if (options.autohide) {
                            setTimeout(function () {
                                gbox.hide();
                            }, options.autohide);
                        }
                    },
                    hide: function () {
                        $('#gbox').fadeOut(function () {
                            $('#gbox_content').html('');
                            $('#gbox_footer').remove();
                        });
                        $('#gbox_bg').fadeOut();
                    }
                };
                gbox.init();

                /* same as site_url() in php
                ------------------------------------------------------------------------- */
                function site_url(url) {
                    return base_url + 'menus' + url;
                }

                /* nested sortables
                ------------------------------------------------------------------------- */
                var menu_serialized;
                $('#easymm').nestedSortable({
                    listType: 'ul',
                    handle: 'div',
                    items: 'li',
                    placeholder: 'ns-helper',
                    opacity: .8,
                    handle: '.ns-title',
                    toleranceElement: '> div',
                    forcePlaceholderSize: true,
                    tabSize: 15,
                    update: function () {
                        menu_serialized = $('#easymm').nestedSortable('serialize');
                        $('#btn-save-menu').attr('disabled', false);
                    }
                });


                /* edit menu item
                ------------------------------------------------------------------------- */
                $('body').on('click', '.edit-menu', function () {
                    var menu_id = $(this).next().next().next().val();
                    var menu_div = $(this).parent().parent(); 
                    var li = $(this).closest('li');
                    gbox.show({
                        type: 'ajax',
                        url: base_url + 'menus/edit/' + menu_id,
                        buttons: {
                            'Save': function () {
                                $.ajax({
                                    type: 'POST',
                                    url: $('#gbox form').attr('action'),
                                    data: $('#gbox form').serialize(),
                                    success: function (data) {

                                        switch (data.status) {
                                            case 1:
                                                gbox.hide();
                                                menu_div.find('.ns-title').html(data.menu.title);
                                                menu_div.find('.ns-url').html(data.menu.url);
                                                break;
                                            case 2:
                                                gbox.hide();
                                                break;
                                            case 4:
                                                gbox.hide();
                                                li.remove();
                                                break;
                                        }
                                    }
                                });
                            },
                            'Cancel': gbox.hide
                        }
                    });
                    return false;
                });

                /* delete menu item
                ------------------------------------------------------------------------- */
                $('body').on('click', '.delete-menu', function () {
                    var li = $(this).closest('li');
                    var param = {id: $(this).next().next().val()};
                    var menu_title = $(this).parent().parent().children('.ns-title').text();
                    gbox.show({
                        content: '<h2>Delete Menu Item</h2>Are you sure you want to delete this menu item?<br><b>'
                            + menu_title +
                            '</b><br><br>This will also delete all sub items.',
                        buttons: {
                            'Yes': function () {
                                $.post(base_url + 'menus/delete', param, function (data) {
                                    if (data.success) {
                                        gbox.hide();
                                        li.remove();
                                    } else {
                                        gbox.show({
                                            content: 'Failed to delete this menu item.'
                                        });
                                    }
                                });
                            },
                            'No': gbox.hide
                        }
                    });
                    return false;
                });

                /* add menu item
                ------------------------------------------------------------------------- */
                $('#form-add-menu').on('submit', function () {
                    if ($('#menu-title').val() == '') {
                        $('#menu-title').focus();
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                            error: function (data) {
                                console.log(data);
                                gbox.show({
                                    content: 'Add menu item error. Please try again.',
                                    autohide: 1000
                                });
                            },
                            success: function (data) {
                                switch (data.status) {
                                    case 1:
                                        $('#form-add-menu')[0].reset();
                                        $('#easymm')
                                            .append(data.li);
                                        break;
                                    case 2:
                                        gbox.show({
                                            content: data.msg,
                                            autohide: 1000
                                        });
                                        break;
                                    case 3:
                                        $('#menu-title').val('').focus();
                                        break;
                                }
                            }
                        });
                    }
                    return false;
                });

                $('body').on('keydown', '#gbox input', function (e) {
                    if (e.which == 13) {
                        $('#gbox_footer .primary').trigger('click');
                        return false;
                    }
                });

                

                /* update menu / save order
                ------------------------------------------------------------------------- */
                $('#btn-save-menu').attr('disabled', true);
                $('#form-menu').submit(function () {
                    $('#btn-save-menu').attr('disabled', true);
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: menu_serialized,
                        error: function () {
                            $('#btn-save-menu').attr('disabled', false);
                            gbox.show({
                                content: '<h2>Error</h2>Save menu error. Please try again.',
                                autohide: 1000
                            });
                        },
                        success: function (data) {
                            gbox.show({
                                content: '<h2>Success</h2>Menu has been saved',
                                autohide: 1000
                            });
                        }
                    });
                    return false;
                });

                /* edit group
                ------------------------------------------------------------------------- */
                $('#edit-group').click(function () {
                    var sgroup = $('#edit-group-input');
                    var group_title = sgroup.text();
                    sgroup.html('<input name="title" class="form-control" style="width: 100%" value="' + group_title + '">'); 
                    $('#submit_menu').show();                  
                });


                

                /* delete group
                ------------------------------------------------------------------------- */
                $('#delete-group').click(function () {
                    var group_title = $('#menu-group li.current a').text();
                    var param = {id: current_group_id};
                    gbox.show({
                        content: '<h2>Delete MenuController</h2>Are you sure you want to delete this menu?<br><b>'
                            + group_title +
                            '</b><br><br>This will also delete all items under this menu.',
                        buttons: {
                            'Yes': function () {
                                $.post(base_url + 'menus/delete_menu', param, function (data) {
                                    if (data.success) {
                                        window.location = base_url + 'menus';
                                    } else {
                                        gbox.show({
                                            content: 'Failed to delete this menu.'
                                        });
                                    }
                                });
                            },
                            'No': gbox.hide
                        }
                    });
                    return false;
                });
        
        
        $("#form-menu").on('click','.activate-item',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) { isActive = 1; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { active: isActive },
                success: function() {
                    toastr.success("<?=lang('menu_item_status')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });

 

    </script>
    <?php } ?> 