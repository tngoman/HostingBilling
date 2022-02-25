<div class="box">   
        <div class="box-body">
        <div class="table-responsive">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
    echo form_open(base_url().'domains/import_domains', $attributes); ?>  
    
        <div class="row">   
            <div class="form-group">
            <label class="col-sm-3 control-label"><?=lang('registrar')?></label>
            <div class="col-md-3">
            <select name="registrar" class="form-control m-b">
                    <option value=""><?=lang('none')?></option>
                    <?php
                            
                            $registrars = Plugin::domain_registrars();
                            foreach ($registrars as $registrar)
                            {?> 
                            <option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?></option>
                            <?php } ?>

                    </select>
                </div>
            </div>
        </div>

        <table id="table-rates" class="table table-striped b-t">
            <thead>
            <tr>
                <th><?=lang('type')?></th> 
                <th><?=lang('domain')?></th> 
                <th><?=lang('period')?></th>
                <th><?=lang('registration')?> <?=lang('date')?></th> 
                <th><?=lang('expires')?></th> 
                <th><?=lang('status')?></th> 
                <th><?=lang('notes')?></th> 
                <th><input type="checkbox" id="select-all" checked> <?=lang('select')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            
            $data = $this->session->userdata('import_domains') ? $this->session->userdata('import_domains') : array();                    
            foreach ($data as $acc) { ?>
            <tr>
                <td><?=$acc->type?></td>
                <td><?=$acc->domain?></td>
                <td><?=$acc->period?></td>
                <td><?=$acc->registration?></td> 
                <td><?=$acc->expires?></td>
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