<div class="box">
   
                <div class="box-body">
  
                <div class="table-responsive">
                <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'companies/import_clients', $attributes); ?>  

                <table id="table-rates" class="table table-striped b-t">
                    <thead>
                    <tr>
                        <th><?=lang('first_name')?></th> 
                        <th><?=lang('last_name')?></th> 
                        <th><?=lang('company_name')?></th>
                        <th><?=lang('email')?></th> 
                        <th><?=lang('address_line_1')?></th> 
                        <th><?=lang('address_line_2')?></th> 
                        <th><?=lang('city')?></th>
                        <th><?=lang('country')?></th> 
                        <th><?=lang('phone')?></th> 
                        <th><input type="checkbox" id="select-all" checked> <?=lang('select')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    $data = $this->session->userdata('import_clients') ? $this->session->userdata('import_clients') : array();                    
                    foreach ($data as $acc) { ?>
                    <tr>
                        <td><?=$acc->first_name?></td>
                        <td><?=$acc->last_name?></td>
                        <td><?=$acc->company?></td>
                        <td><?=$acc->email?></td> 
                        <td><?=$acc->address_1?></td>
                        <td><?=$acc->address_2?></td>
                        <td><?=$acc->city?></td>
                        <td><?=$acc->country?></td> 
                        <td><?=$acc->phone?></td> 
                        <td><input type="checkbox" name="<?=$acc->id?>" checked></td>               
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
                        <td></td>
                        <td></td>    
                        <td></td>
                        <td></td>
                        <td><button class="btn btn-success btn-block btn-sm"><?=lang('import')?></button></td>                 
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