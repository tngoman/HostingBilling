<div class="box">   
        <div class="box-body">
        <div class="table-responsive">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'accounts/import_accounts', $attributes); ?>      
   
        <table id="table-rates" class="table table-striped b-t">
            <thead>
            <tr>
                <th><?=lang('domain')?></th> 
                <th><?=lang('username')?></th> 
                <th><?=lang('billed')?></th> 
                <th><?=lang('package')?></th>
                <th><?=lang('next_renewal')?></th> 
                <th><?=lang('status')?></th>
                <th><?=lang('notes')?></th> 
                <th><input type="checkbox" id="select-all" checked> <?=lang('select')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            
            $data = $this->session->userdata('import_accounts') ? $this->session->userdata('import_accounts') : array();      
            $services = Item::get_hosting();        
                          
            foreach ($data as $acc) { ?>
            <tr>
                <td><?=$acc->domain?></td>
                <td><?=$acc->username?></td>
                <td><?=$acc->renewal?></td>
                <td><select name="package[<?=$acc->id?>]">
                <option value="0"><?=lang('select')?></option>
                    <?php 
                    foreach($services as $service)
                    { ?>
                        <option value="<?php echo $service->item_id; ?>" 
                        <?php $interval = strtolower(str_replace("_", "", $acc->renewal));
                        if(isset($service->$interval))
                        {
                            if($acc->recurring_amount == intval($service->$interval))
                            {
                                echo "selected";
                            } 
                        }                        
                        
                        ?>

                        ><?php echo $service->item_name; ?></option>
                     <?php  } ?>
                     </select>
                </td> 
                <td><?=$acc->due_date?></td>
                <td><?=$acc->status?></td>
                <td><?=$acc->notes?></td>  
                <td><input type="checkbox" checked name="<?=$acc->id?>"></td>               
            </tr>
            <?php }  ?>
            
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>    
                <td></td>
                <td></td>
                <td><button class="btn btn-success btn-block btn-sm"><?=lang('import')?></button></td> 
                <td></td>  
                <td></td>                               
            </tr>
            </tfoot>
        </table>  
        </form>
        </div>                          
    </div>
</div>
    

<script>
$(document).ready(function() {
    $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
        this.checked = checked;
    });
    })
});
</script>