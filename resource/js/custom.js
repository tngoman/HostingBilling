$(document).ready(function () {

    // Resize current autoresizable textareas
   $('textarea.js-auto-size').textareaAutoSize();
   
   // select2 
   if ($.fn.select2) {
       $(".select2-option").select2();
       $("#select2-tags").select2({
         tags:["red", "green", "blue"],
         tokenSeparators: [",", " "]}
       );
   }
   
   $('#add-translation').on('click', function () {
       var lang = $('#add-language').val();
       window.location.href = base_url+'settings/translations/add/'+lang+'/?settings=translations';
   });
   
   
   $('.span12').addClass('col-lg-12').removeClass('span12');
   if (!$('button').prop('type')) {
       $(this).attr('type', 'button');
   }

   $.fn.showCategoryFields = function (selectObject) {

      if(selectObject.value == 9) {
           $('#qty').val('1');
           $('#price').val('0');
           $('#generic_group, #domain_group').hide(500); 
           $('#hosting_group').show(300);
      }

      else if(selectObject.value == 8) {
           $('#qty').val('1');
           $('#price').val('0');
           $('#generic_group, #hosting_group').hide(500);
           $('#domain_group').show(300);        
       }

       else {
           $('#generic_group').show(300);
           $('#domain_group, #hosting_group').hide(500); 
       }
   }

   //disable field added by wysihtml5
$('input[name=_wysihtml5_mode]').prop("disabled", true);


});

function textarea_resize(el) {
   var lines = $(el).val().split(/\r\n|\r|\n/).length;
   var height = ((lines * 34) - ((lines - 1) * 10));
   $(el).css('height', height + 'px');
}


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


$(document).on('submit', '.btn', function(){
   Pace.Start; 
});



$('#btnSearch, #Search').on('click', function (e) {
   e.preventDefault();
   name = $('#searchBar').val();
   if (checkName(name)) {
       type = $('#Search').attr('data');

       if(type == undefined) {
        type = $('#btnSearch').attr('data');
       }

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
       url: base_url + 'domains/check_availability',
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
           $('#Search').show();
           $('#continue').hide();
           $('#searchBar').val('');
           $('#Transfer').show();
           $('#textBar').val('');
           $('#response').html(data.result).slideDown(500);
       },
       
   
       error: function (data) {
            console.log(data);
           $('#checking').hide();
           $('#btnSearch').show();
           $('#Search').show();
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
       $('#Search').show();
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


//v1.5

$(document).on('change','#domain_price',function(){
    var price = $(this).find('option:selected').val();
    $('#price').val(price);
});  

