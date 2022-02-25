<div class="box box-solid box-default">
    <header class="box-header "><?=lang('new_order')?></header>
    <div class="box-body inner">
        <div class="row">
            <div class="col-sm-6">
                <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'orders/select_client',$attributes); ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 w_300">
                            <select class="select2-option w_280" id="modal_client" name="co_id" required> 
                                <option value="" selected>Select</option>
                                <?php foreach (Client::get_all_clients() as $client): ?>
                                <option value="<?=$client->co_id?>"><?=ucfirst($client->company_name)?></option>
                                <?php endforeach;  ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                        <button type="submit" class="btn btn-success pull-right"><?=lang('continue')?></button>
                        </div>
                        <div class="col-md-1">
                            <a href="<?=base_url()?>companies/create" class="btn btn-default btn-sm"
                                data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom"><i
                                    class="fa fa-plus"></i> <?=lang('new_client')?></a>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
 