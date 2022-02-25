<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('signups')?></h4>
        </div>
            <div class="modal-body">
            <div class="table-responsive">
                            <table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
                                <thead>
                                    <tr>
                                        <th class="w_5 hidden"></th>
                                        <th class="col-date"><?=lang('date')?></th>
                                        <th class=""><?=lang('products_services')?></th>
                                        <th class=""><?=lang('amount')?></th>
                                        <th class=""><?=lang('commission')?></th>
                                        <th class=""><?=lang('payout')?></th>
                                        <th class=""><?=lang('status')?></th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach (Affiliate::account($id) as $key => $aff) { ?>
                                    <tr>
                                        <td class="hidden"></td>
                                        <td><?=$aff->date?></td>
                                        <td><?=$aff->item_name?></td>
                                        <td><?=Applib::format_currency(config_item('default_currency'), $aff->amount)?>
                                        </td>
                                        <td><?=Applib::format_currency(config_item('default_currency'), $aff->commission)?>
                                        </td>
                                        <td><?=$aff->type == 'once' ? lang('once') : lang('recurring')?></td>
                                        <td><?=lang($aff->status)?></td>
                                    </tr>
                                    <?php }  ?>
                                </tbody>
                            </table>
                        </div>
        </div>
		<div class="modal-footer"> <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('close')?></a>
	</form>
	</div>
</div>
</div>
 