$(document).ready(function () {

    var domain_name = '';
    var type = '';
    var domain_name = '';
    var error = false;
    var name = '';

    $('.vertical li:has(ul)').addClass('parent');
    $('.horizontal li:has(ul)').addClass('parent');

    $('#Transfer').on('click', function (e) {
        e.preventDefault();
        name = $('#searchBar').val();
        if (checkName(name)) {
            type = $('#Transfer').attr('data'); 

            if (name != '') {
                var ext = $('#ext').find('option:selected').val();
                domain_name = name + "." + ext;
                $(this).hide();
                $('#checking').show();
                checkAvailability();
            }
            else {
                swal("Empty Search!", "Please enter a domain name", "warning");
            }
        }
    });


    $('#btnSearch').on('click', function (e) {
        e.preventDefault();
        name = $('#searchBar').val();
        if (checkName(name)) {
            type = $('#btnSearch').attr('data'); 

            if (name != '') {
                var ext = $('#ext').find('option:selected').val();
                domain_name = name + "." + ext;
                $(this).hide();
                $('#checking').show();
                checkAvailability();
            }
            else {
                swal("Empty Search!", "Please enter a domain name", "warning");
            }
        }

    }); 


    $.fn.searchAgain = function () {
        $('#response').hide(500);
    }


    //The actual request to check availability
    function checkAvailability() {

        $.ajax({
            url: '/domains/check_availability',
            type: 'POST',
            data: {
                domain: domain_name,
                type: type
            },
            dataType: 'json',
            success: function (data) {
                $('#domain').val(data.domain);
                $('#price').val(data.price);
                $('#type').val(type);
                $('#checking').hide();
                $('#btnSearch').show();
                $('#continue').hide();
                $('#searchBar').val('');
                $('#Transfer').show();
                $('#textBar').val('');
                $('#response').html(data.result).slideDown(500);
            },

            error: function (data) {
                $('#checking').hide();
                $('#btnSearch').show();
                $('#Search').show();
                $('#Transfer').show();
            }
        });
    }


    function checkName(name) {
        if (name.indexOf('.') !== -1) {
            swal("Invalid Domain!", "Please enter the name only and select the extension", "warning");
            $('#checking').hide();
            $('#btnSearch').show();
            $('#Transfer').show();
            return false;
        }
        return true;
    }


    $('#cart').on('click', '#add_available', function () {
        $('#cart').submit();
    });


    $.fn.continueOrder = function () {
         
       if (window.location.href == base_url + 'cart/domain') {
            $('.search_form').submit();
        }

        else {
            $.ajax({
                url: base_url + 'cart/add_domain',
                type: 'post',
                data: $("#search_form").serialize(),
                success: function (data) {
                    $('#response').slideUp(500);
                    $('#continue').slideDown(500);
                },
                error: function (data) {

                }
            });
        }
    }


    $('.carousel').carousel({
        interval: 4000
    });


    var winScroll = $(window).scrollTop();
    winScroll > 1 ? $("#to-top").css({
        bottom: "10px"
    }) : $("#to-top").css({
        bottom: "-100px"
    }), $(window).on("scroll", function () {
        winScroll = $(window).scrollTop(), winScroll > 1 ? $("#to-top").css({
            opacity: 1,
            bottom: "30px"
        }) : $("#to-top").css({
            opacity: 0,
            bottom: "-100px"
        })
    }), $("#to-top").on('click', function () {
        return $("html, body").animate({
            scrollTop: "0px"
        }, 800), !1
    });



    //v1.5

    $(document).on('change','#domain_price',function(){
        var price = $(this).find('option:selected').val();
        $('#price').val(price);
    });        


    $("#kb_search").keyup(function(){ 
        var search = $(this).val(); 
        if(search != "" && search != " "){ 
           $.ajax({ 
             url: base_url + 'knowledge/search', 
             type: 'post', 
             data: {search:search}, 
             dataType: 'json', 
             success:function(response){ 
               var len = response.length; 
               $("#searchResult").empty(); 
               for( var i = 0; i<len; i++){ 
                  var slug = response[i]['slug'];  
                  var title = response[i]['title'];                  
                  $("#searchResult").append("<li><a href='" + base_url + "knowledge/article/" + slug + "'>"+title+"</a></li>"); 
             } 
           }  
        }); 
      }
      else
      {
        $("#searchResult").empty(); 
      }
    }); 


    $("#issue_search").keyup(function(){ 
        var search = $(this).val(); 
        if(search != "" && search != " "){ 
           $.ajax({ 
             url: base_url + 'issues/search', 
             type: 'post', 
             data: {search:search}, 
             dataType: 'json', 
             success:function(response){ 
               var len = response.length; 
               $("#searchResult").empty(); 
               for( var i = 0; i<len; i++){ 
                  var slug = response[i]['slug'];  
                  var title = response[i]['title'];                  
                  $("#searchResult").append("<li><a href='" + base_url + "issues/issue/" + slug + "'>"+title+"</a></li>"); 
             } 
           }  
        }); 
      }
      else
      {
        $("#searchResult").empty(); 
      }
    }); 


    $("#feature_search").keyup(function(){ 
        var search = $(this).val(); 
        if(search != "" && search != " "){ 
           $.ajax({ 
             url: base_url + 'features/search', 
             type: 'post', 
             data: {search:search}, 
             dataType: 'json', 
             success:function(response){ 
               var len = response.length; 
               $("#searchResult").empty(); 
               for( var i = 0; i<len; i++){ 
                  var slug = response[i]['slug'];  
                  var title = response[i]['title'];                  
                  $("#searchResult").append("<li><a href='" + base_url + "features/request/" + slug + "'>"+title+"</a></li>"); 
             } 
           }  
        }); 
      }
      else
      {
        $("#searchResult").empty(); 
      }
    }); 


    function toggleIcon(e) 
    {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


    $('.AppendDataTables').DataTable();

});