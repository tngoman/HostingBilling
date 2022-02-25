<div class="box">
    <div class="box-body inner">
        <div class="container aside-xxxl animated fadeInUp">
            <div class="panel panel-primary bg-white m-t-sm">
                <header class="panel-heading text-center">
                    <h3><?=lang('invoice') . " - " . $invoice[0]->reference_no?></h3>
                </header>
                <div class="panel-body">

				<div id="card-error" role="alert"></div>

                    <?php
						$attributes = array('id' => 'payment-form', 'name'=>'stripe','class' => 'bs-example form-horizontal');
                        echo form_open(base_url().'stripepay/authenticate', $attributes);  ?>
						<input type="hidden" name="invoice" value="<?=$id?>">
						<input type="hidden" name="amount" value="<?=$due?>">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" name="name" id="name" class="form-control" placeholder="Full name" autofocus="" value="<?=$company[0]->company_name?>" required>
							</div>
							</div> 

							<div class="col-md-12">					 
							<div class="form-group">
								<input type="text" name="phone" id="phone" class="form-control" placeholder="Mobile No" value="<?=$company[0]->company_phone?>" required>								 
							</div>
							</div> 
					 
							<div class="col-md-12">
                            <div class="form-group">
                                <div id="card-number" class="form-control"></div>
                            </div> 
							</div> 

	 
							<div class="col-md-6">
								<div class="form-group">
									<div id="card-expiry" class="form-control"></div>
								</div>
							</div>
							<div class="col-md-1"></div>
							<div class="col-md-5">
								<div class="form-group">
									<div id="card-cvv" class="form-control"></div>
								</div>
							</div>
					 
						<button type="submit" class="btn btn-block btn-success" id="payBtn"><?=lang('pay')?> <?=Applib::format_currency($invoice[0]->currency, $due)?></button>
					</div>

                </form>
            </div>
        </div>
    </div>
</div> 

<script src="https://js.stripe.com/v3/"></script>

<script>
(function($) {
    var stripe = Stripe("<?=$public_key?>");
    var elements = stripe.elements();
    var style = { 
        invalid: {
            fontFamily: "Arial, sans-serif",
            color: "#fa755a",
            iconColor: "#fa755a",
        },
    };

    var cardNumber = elements.create("cardNumber", {
        style: style,
    });
    cardNumber.mount("#card-number");

    var exp = elements.create("cardExpiry", {
        style: style,
    });
    exp.mount("#card-expiry");

    var cvc = elements.create("cardCvc", {
        style: style,
    });
    cvc.mount("#card-cvv");
 
    var resultContainer = $("#card-error");
    cardNumber.on("change", function(event) {
        if (event.error) {
            resultContainer.html("<p class='alert alert-danger'>" + event.error.message + "</p>");
        } else {
            resultContainer.html("");
        }
    });
 
    var form = $("#payment-form");
 
    $('#payBtn').on("click", function(e) {
        e.preventDefault();
        createToken();
    });
 
    function createToken() {
        stripe.createToken(cardNumber).then(function(result) {
            if (result.error) { 
                resultContainer.html("<p class='alert alert-danger'>" + result.error.message + "</p>");
            } else { 
                stripeTokenHandler(result.token);
            }
        });
    }
 
    function stripeTokenHandler(token) { 
        var hiddenInput = document.createElement("input");
        hiddenInput.setAttribute("type", "hidden");
        hiddenInput.setAttribute("name", "stripeToken");
        hiddenInput.setAttribute("value", token.id);
		form.append(hiddenInput); 
 		form.submit();
    }

})(jQuery);
</script>