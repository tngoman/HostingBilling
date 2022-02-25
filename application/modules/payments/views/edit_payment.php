
<!-- Start -->
            <div class="box">
                <div class="box-header b-b clearfix">
                          <?php $i = Payment::view_by_id($id); ?>

                            <strong><i class="fa fa-info-circle"></i> <?=lang('payment')?> - <?=$i->trans_id?> </strong>                      
                            <a href="<?=base_url()?>payments/view/<?=$i->p_id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="top" class="btn btn-<?=config_item('theme_color');?> btn-sm pull-right"><i class="fa fa-info-circle"></i> <?=lang('payment_details')?></a>
                    </div>
                   <div class="box-body">

                                <?php
                                $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url().'payments/edit',$attributes); ?>
                                <input type="hidden" name="p_id" value="<?=$i->p_id?>">

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?=lang('amount')?> <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" value="<?=$i->amount?>" name="amount">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?=lang('payment_method')?> <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select name="payment_method" class="form-control">
                                            <?php foreach (App::list_payment_methods() as $key => $p_method) { ?>
                                                <option value="<?=$p_method->method_id?>"<?=($i->payment_method == $p_method->method_id ? ' selected="selected"' : '')?>><?=$p_method->method_name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <?php $currency = App::currencies($i->currency); ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?=lang('currency')?> <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select name="currency" class="form-control">
                                           <?php foreach (App::currencies() as $cur) : ?>
                                <option value="<?=$cur->code?>"<?=($currency->code == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?=lang('payment_date')?></label>
                                    <div class="col-lg-4">
                                        <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($i->payment_date));?>" name="payment_date" data-date-format="<?=config_item('date_picker_format');?>" >
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?=lang('notes')?> </label>
                                    <div class="col-lg-8">
                                        <textarea name="notes" class="form-control ta"><?=$i->notes?></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"> <?=lang('save_changes')?></button>


                                </form>
                            </div>
                          </div>

   
<!-- end -->
