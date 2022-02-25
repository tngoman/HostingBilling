<?php 
    $cart = $this->session->userdata('cart'); 
    $total = 0;
?>
<div class="box">
    <div class="container inner">
        <div class="row">

            <div class="row">
                <div class="col-sm-6">
                    <h4><?=lang('enter_full_domain')?></h4>
                        <form method="post" action="<?=base_url()?>cart/existing_domain" class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="domain" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="submit" class="btn btn-primary btn-block" name="type"
                                        value="<?= lang('continue') ?>" />
                                </div>
                            </div>
                        </form>

                </div>
            </div>
        </div>

    </div>
</div>