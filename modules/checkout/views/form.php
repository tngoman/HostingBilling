<?php if($live) { ?>
<script type="text/javascript" src="https://2checkout.com/checkout/api/2co.min.js"></script>
<script type="text/javascript" src="https://2checkout.com/checkout/api/script/publickey/"></script>
<?php } else{ ?>
<script type="text/javascript" src="https://sandbox.2checkout.com/checkout/api/2co.min.js"></script>
<script type="text/javascript" src="https://sandbox.2checkout.com/checkout/api/script/publickey/"></script>
<?php } ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('due_amount')?> <strong><?=App::currencies($info['currency'])->symbol;?><?=number_format($info['amount'],2);?></strong> for Invoice #<?=$info['item_name'];?> via 2Checkout</h4>
        </div>
        <div class="modal-body">

    <?php
    $attributes = array('class' => 'bs-example form-horizontal','id' => 'tcoPay','onsubmit' => 'return false');
          echo form_open(base_url().'checkout/process',$attributes); ?>

          <?php // Show PHP errors, if they exist:
        if (isset($errors) && !empty($errors) && is_array($errors)) {
            echo '<div class="alert alert-error"><h4>Error!</h4>The following error(s) occurred:<ul>';
            foreach ($errors as $e) {
                echo "<li>$e</li>";
            }
            echo '</ul></div>';
        }?>

        <div id="payment-errors"></div>
        <input type="hidden" name="invoice_id" value="<?=$info['item_number']?>">
        <input id="sellerId" type="hidden" value="<?=$seller_id?>" />
        <input id="publishableKey" type="hidden" value="<?=$publishable_key?>" />
        <input id="token" name="token" type="hidden" value="">

        <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('due_amount')?> (<?=App::currencies($info['currency'])->symbol?>) </label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" name="due" value="<?=number_format($info['amount'],2)?>" readonly>
                </div>
                </div>

        <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('amount')?> ( <?=lang('eg')?> 1900 )</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control input-medium" name="amount" autocomplete="off" value="<?=round($info['amount'],2)?>" required>
                </div>
        </div>

        <div class="form-group">
                <label class="col-lg-4 control-label">Card Number</label>
                <div class="col-lg-5">
                    <input type="text" id="ccNo" size="20" class="form-control card-number input-medium" autocomplete="off" placeholder="5555555555554444" required>
                </div>
        </div>

        <div class="form-group">
                <label class="col-lg-4 control-label">CVC</label>
                <div class="col-lg-2">
                    <input type="text" id="cvv" size="4" class="form-control card-cvc input-mini" autocomplete="off" placeholder="123" required>
                </div>
        </div>

        <div class="form-group">
                <label class="col-lg-4 control-label">Expiration (MM/YYYY)</label>
                <div class="col-lg-2">
                    <input type="text" size="2" id="expMonth" class="form-control input-mini" autocomplete="off" placeholder="MM" required>

                </div>
                <div class="col-lg-2">
                <input type="text" size="4" id="expYear" class="form-control input-mini" placeholder="YYYY" required>
                </div>
        </div>

    <div class="modal-footer">
    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
     <input type="submit" value="Submit Payment" class="btn btn-success" />
    </div>
</form>


        </div>



    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script>
    (function($){
     // Called when token created successfully.
    function successCallback(data) {
        var myForm = document.getElementById('tcoPay');
        myForm.token.value = data.response.token.token;
        myForm.submit(); 
    }

    // Called when token creation fails.
    function errorCallback(data) {
        if (data.errorCode === 200) {
            TCO.requestToken(successCallback,
            errorCallback, 'tcoPay');
        } else {
            alert(data.errorMsg);
        }
    }

    $(function () {
        $("#tcoPay").on('submit', function (e) {
            e.preventDefault();
            TCO.requestToken(successCallback, errorCallback, 'tcoPay');
        });
    });

})(jQuery);
</script>