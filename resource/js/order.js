
        var domain_name  = '';
        var renewal = 'annually';
        var name_one = $('#name_one').val();
        var name_two = $('#name_two').val();
        var name_three = $('#name_three').val();
        var name_four = $('#name_four').val();
        var domain = {};
        var hosting = {};
        var order = []; 
        var total = 0;   
        var type = '';    
        var plan = {};
        var co_id = 0;
        var nameservers = "";
        var session = localStorage.getItem("order");
        if(session != undefined) {
            order = JSON.parse(session);
        }        
        

        $(document).ready(function(){ 

            resetClient();
             

            if(order.length > 0) {
                showCart();
            }


             function showCart() {  
                 
                var renew = "";
                $('#cart_pane').slideDown(500);
            
                 $('#cart').html('<table class="table table-bordered"><thead><tr><th>Item</th><th>Domain</th><th>Billed</th><th>Price</th><th>Action</th></tr></thead><tbody id="domains">');
                    total = 0;

                    order.forEach(function(items, index){   

                        switch(items.renewal) {
                                case 'monthly' : renew = 'Monthly'; break;
                                case 'quarterly' : renew = 'Quarterly'; break;
                                case 'semi_annually' : renew = 'Semi-Annually'; break;
                                case 'annually' : renew = 'Annually'; break;
                            }

                            $('#domains').append('<tr><td>'+items.name+'</td><td>'+items.domain+'</td><td>'+renew+'</td><td>'+items.price+'</td><td><button class="btn btn-block btn-sm btn-default" onClick="$(this).updateCart('+index+')">Remove</td><tr>');                           
                            total += parseFloat(items.price);     
                    });

                    $('#domains').append('<tr><td colspan="3"><span class="pull-right"><strong>Total</strong></span></td><td><strong>'+total+'</strong></td><td><button class="btn btn-danger btn-sm btn-block" onClick="$(this).clearCart();">Remove All</button></td></tr><tr><td colspan="4"></td><td><button class="btn btn-success btn-sm btn-block" onClick="$(this).submitOrder();">Submit Order</button></td></tr></tbody></table>');
                    
                }

  

             
             $.fn.updateCart = function (i) {
                order.splice(i, 1);                   

                var index = order.indexOf(domain);
                if (index > -1) {
                    order.splice(index, 1);
                }

                showCart();
             }



             //clear cart
             $.fn.clearCart = function() {
                order = [];
                domain = {};
                hosting = {};
                showCart();
                localStorage.removeItem("order");
                $('#cart_count').text(order.length);
             }



             //add domain to hosting
             $('#add_domain').click(function(){
                    addDomainHosting();
             });

             
           
             $('#plan').change(function(){
                var id = $(this).find('option:selected').attr('id');
                selectProduct(id);
             });



             $('#client').change(function(){
                co_id = $(this).find('option:selected').val();
                $('#selected_client').text($(this).find('option:selected').text());
                $('#order_client').show();
             });


             $('#modal_client').change(function(){
                co_id = $(this).find('option:selected').val();
                $('#selected_client').text($(this).find('option:selected').text());
                $('#order_client').show();
                $('#clientModal').modal('hide'); 
             });



             selectProduct = function(id) {
                hd.forEach(function(item) {
                    if(item.item_id == id) {
                        hosting = {name: item.item_name};
                        var period = '';
                        var select = '<div class="form-group"><h3>'+item.item_name+'</h3><select id="period" data="'+item.item_id+'" class="form-control" style="max-width:50%;">';
                        for(i in item) {
                            if(i == 'monthly' || i == 'quarterly' || i == 'semi_annually' || i == 'annually' ) {
                                if(item[i] > 0) {

                                        switch(i) {
                                            case 'monthly' : period = 'Monthly'; break;
                                            case 'quarterly' : period = 'Quarterly'; break;
                                            case 'semi_annually' : period = 'Semi-Annually'; break;
                                            case 'annually' : period = 'Annually'; break;
                                        }

                                        select += '<option id="'+i+'" value="'+item[i]+'">'+item[i]+' &nbsp; '+period+'</option>';
                                     }                                    
                                }
                        }

                        select += '</select></div><div class="form-group"><button class="btn btn-success" id="addToCart">Add to cart</button></div>';
                        $('#contents').html(`${select}`);         
                        $('#cartModal').modal('show');                  
                    }
                });               
 
              }



             //add hosting to cart
            $('#contents').on('click', '#addToCart', function(e) {       
                e.preventDefault();   

                $('#cartModal').modal('hide');
                hosting.price = $('#period').find('option:selected').val();
                hosting.renewal = $('#period').find('option:selected').attr('id');
                hosting.item = $('#period').attr('data');

                if($.isEmptyObject(domain) == false) {
                    $('#continue').slideUp(500);
                    addDomainHosting();
                }               


                else {                    
                    $('#cartModal').modal('hide');  
                    swal("Domain Required", "Please enter a domain name!", "warning");   
                } 
             });





             $('#btnSearch').click(function(e){                
                e.preventDefault();       
                
                var name = $('#searchBar').val();
                checkName(name);
                type = 'Domain Registration';
        
                if(name != '') {
                    
                    var ext = $('#ext').find('option:selected').val();
                    domain_name = name + "." + ext;
                    $(this).hide(); 
                    $('#btnTransfer').hide();
                    $('#checking').show();
                    checkAvailability();
                }
                else {
                    swal("Oops!", "Please enter a domain name!", "warning");
                }
        
            });
        
        
        
        
            $('#btnTransfer').click(function(e){                
                e.preventDefault();       
                
                var name = $('#searchBar').val();
                checkName(name);
                type = 'Domain Transfer';
        
                if(name != '') {
                    
                    var ext = $('#ext').find('option:selected').val();
                    domain_name = name + "." + ext;                    
                    $(this).hide();
                    $('#btnSearch').hide();
                    $('#checking').show();              
                    $('#transferModal').modal('hide');
        
                    checkAvailability();
                }
                else {
                    console.log('enter domain');
                }
        
            });
        
        
         
            function checkAvailability() {
        
                $.ajax({
                        url: base_url+'domains/check_availability',
                        type: 'POST',
                        data: {domain: domain_name, type: type},
                        dataType: 'json',
                        success: function(data) {                     
                            $('#checking').hide();
                            $('#btnSearch').show();
                            $('#btnTransfer').show();
                            $('#continue').hide();
                            $('#searchBar').val('');
                            $('#response').html(data.result).slideDown(500);
                            if(data.domain) {
                               domain = {domain: data.domain, price: data.price, name: type, renewal: renewal};
                               type = '';
                            }
                        },
                        
                        error: function(data){
                            console.log(data);
                            $('#checking').hide();
                            $('#btnSearch').show();
                            $('#btnTransfer').show();
                        }
                });
        
             }


        function checkName(name) {

            if(name.indexOf('.') !== -1)
            {
                swal("Invalid domain name!", "Please remove '.' at the end!", "warning");
                $('#checking').hide();
                $('#btnSearch').show();
                $('#btnTransfer').show();

            }
        }

        
        $('#order').on('click', '#add_available', function(e) {  
            e.preventDefault();
        });

                
        $.fn.continueOrder = function() {
            $('#cart_count').text(order.length);
            saveToLocalStorage();
    

            if($.isEmptyObject(hosting)) {
                $('#domain_only').show();                   
            }

            else {
                $('#add_domain').text('order with ' + hosting.name);
                $('#domain_only').hide();
                $('#add_domain').show();
            }

            $('#response').slideUp(500);
            $('#continue').slideDown(500);
        }




        //order domain only
        $('#domain_only').click(function(){  
            $('#nameserverModal').modal('show');            
        });



        $("#nameserverModal").on('hide.bs.modal', function () {
            domain_only();
        });


        $('#name_servers').click(function(e){
            e.preventDefault();
            $('#nameserverModal').modal('hide');
        });



        function domain_only () {
            order = $.grep(order, function(e){ 
                return e.domain != domain.domain; 
           });  

           if(name_one != '') {
               nameservers += name_one;
           }

           if(name_two != '') {
                nameservers += ", " + name_two;
            }

            if(name_three != '') {
                nameservers += ", " + name_three;
            }

            if(name_four != '') {
                nameservers += ", " + name_four;
            }
           
            domain.domain_only = true;
            domain.nameservers = nameservers;
            order.push(domain);
            domain = {};

            $('#continue').slideUp(500);
            showCart();
        }


          function addDomainHosting() {
                $('#continue').slideUp(500);
                order.push({name: hosting.name, price: hosting.price, domain: domain.domain, renewal: hosting.renewal, item: hosting.item});
                order.push(domain);
                $('#response').hide();
                saveToLocalStorage();
                domain = {};
                hosting = {};
                showCart();

                $('#plan option').removeAttr('selected');
                $('#plan option:eq(0)').prop('selected', true);
             }


             //submit_order
             $.fn.submitOrder = function () {

                if(co_id == 0 && typeof admin !== 'undefined') {
                    resetClient();
                    $('#clientModal').modal('show'); 
                }

                else {
                    var cart = { order: order };
                    var orderJSON = JSON.stringify(cart);
                    $.post(
                        base_url+'domains/cart',
                        { data: orderJSON },
                        function(data) {
                            if(data.status == true) {
                                localStorage.removeItem("order");
                                window.location.replace(base_url+'/invoices/create/'+co_id);
                            }
                        }
                    );
                 }

               
              }


              function resetClient() {
                $('#client option').removeAttr('selected');
                $('#client option:eq(0)').prop('selected', true);
              }

            

              function saveToLocalStorage() {
                 localStorage.setItem("order", JSON.stringify(order));
              }
         

        });