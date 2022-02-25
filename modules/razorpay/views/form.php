<div class="box">
    <div class="box-body">
	<div class="container inner">
        <div class="row">
        <div class="col-md-5">  
        <p>
        <img src="<?=base_url()?>resource/images/gateways/razorpay.png" />
        </p> 

		<div id="payment-errors"></div>
		
        <input type="hidden" name="invoice_id" value="<?= $info['item_number'] ?>">
        <input type="hidden" name="currency" value="<?= $info['currency'] ?>">    
        <input name="amount" id="amount" value="<?= $info['amount'] ?>" type="hidden">
        <input  value="<?=Applib::format_currency('INR', $info['amount'])?>" type="text" readonly>
        <a href="javascript:void(0)" class="btn btn-sm btn-success" id="buy_now"><?=lang('pay_now')?></a>

        <p>
            <div id="msg"></div>
        </p>
      
</div>
<div id="loader-wrapper" style="display: none"> 
    <div id="loader"></div>
</div>
</div>
</div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">

    var encode_id = '<?php url_encode($info['item_number'])?>';
    $('body').on('click', '#buy_now', function (e) {
      
        var totalAmount = "<?= ($info['amount']) ?>";
       
        var options = {
            "key": "<?=$api_key?>",
            "amount": (parseFloat(totalAmount) * 100), // 2000 paise = INR 20
            "name": '<?= config_item('company_name')?>',
            "description": "Invoice '<?= $info['item_name'] ?>' via Razorpay",
            "image": "<?=base_url()?>resource/images/<?=config_item('company_logo')?>",
            "handler": function (response) { 
			 
                $("loader-wrapper").show();
                $('#loader-wrapper').delay(25000).fadeOut(function () {
                    $('#loader-wrapper').remove();
                });
                
                $('#msg').text('<?=lang('processing')?>');
			 
                $.ajax({
                    url: '<?=base_url()?>razorpay/processed_ipn',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        payment_id: response.razorpay_payment_id,
                        paid: totalAmount,
                        invoice: <?= $info['item_number'] ?>
                    },
                    success: function (msg) { 
                        swal('<?=lang('transaction_complete')?>', msg.message, msg.status);
                        setTimeout(function () {
                            window.location.replace('<?=base_url()?>invoices/view/'+msg.invoice_id);
                        }, 5000);
                    }
				}); 

            },
            "theme": {
                "color": "#17759a"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    });

</script>
				 