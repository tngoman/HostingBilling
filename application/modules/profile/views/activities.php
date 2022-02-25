<div class="box">
<div class="box-body">	
	<div class="table-responsive">
		<table id="table-activities" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>	
					<th><?=lang('activity_date')?></th>	
					<th><?=lang('user')?></th>			
					<th><?=lang('module')?></th>
					<th><?=lang('activity')?> </th>
					
				</tr> </thead> <tbody>
				<?php foreach (User::user_log(User::get_id()) as $key => $a) { ?>
				<tr>
				<td><?=$a->activity_date?></td>
				<td><?=User::displayName($a->user)?></td>
				<td><?=strtoupper($a->module)?></td>
				<td>
                                    <?php 
                                    if (lang($a->activity) != '') {
                                        if (!empty($a->value1)) {
                                            if (!empty($a->value2)){
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>', '<em>'.$a->value2.'</em>');
                                            } else {
                                                echo sprintf(lang($a->activity), '<em>'.$a->value1.'</em>');
                                            }
                                        } else { echo lang($a->activity); }
                                    } else { echo $a->activity; } 
                                    ?>
                                <?php if (config_item('last_seen_activities') < strtotime($a->activity_date)) { ?>
                                &nbsp;&nbsp;<span class="badge bg-success text-white"><?=lang('new')?></span>
                                <?php } ?>

                                </td>
			</tr>
			<?php } ?>
			
	

 </tbody>
</table>
</div>
</div>
</div>