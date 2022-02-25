<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('payout_history')?></h4>
        </div>
            <div class="modal-body">
                    <div class="table-responsive">
                            <table id="table-templates-1"
                                class="table table-striped b-t b-light text-sm AppendDataTables">
                                <thead>
                                    <tr>
                                        <th class="w_5 hidden"></th>
                                        <th class="col-date"><?=lang('request_date')?></th> 
                                        <th class="col-date"><?=lang('payment_date')?></th> 
                                        <th><?=lang('amount')?></th>
                                        <th><?=lang('notes')?></th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach (Affiliate::withdrawals($id) as $key => $aff) { ?>
                                    <tr>
                                        <td class="hidden"></td>
                                        <td><?=$aff->request_date?></td>
                                        <td><?=$aff->payment_date?></td>
                                        <td><?=Applib::format_currency(config_item('default_currency'), $aff->amount)?>
                                        <td><?=$aff->notes?></td>
                                        </td>                                       
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
 