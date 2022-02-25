<div class="box">
    <div class="box-body">
        <?php
        if ($result['error'] == 'ok') { ?>

        <div class="row">
        <div class="col-md-4">
        <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <?=lang('invoice') ?>: <?=$reference?>
        </div>
        <div class="box-body">
                <?php
                $link = $result['result']['status_url'];
                $trans = $result['result']['txn_id'];
                $amount = sprintf('%.08f', $result['result']['amount']).' '.$coin; 
                ?>

                <form>
                    <div class="form-group">
                        <label><?=lang('transaction_id')?></label>
                        <input class="form-control" value="<?=$trans?>" readonly="readonly">
                    </div>

                    <div class="form-group">
                        <label><?=lang('send')?></label>
                        <input class="form-control" value="<?=$amount?>" readonly="readonly">
                    </div>

                </form>
               
                
                <div class="box-footer">
                    <a class="btn btn-success btn-block" href="<?=$link?>" target="_blank"><?=lang('send_now') ?></a> 
                </div>

         <?php } else {
                echo 'Error: '.$result['error']."\n";
            }
        ?>	
            </div> 
            </div>
        </div>
        </div>        
    </div>                     
</div>

 