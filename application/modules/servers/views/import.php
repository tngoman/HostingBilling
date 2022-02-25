<div class="box">
   
                <div class="box-body">

                <?php if($this->session->flashdata('message')): ?>
                    <div class="alert alert-info alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?php echo $this->session->flashdata('message') ?>
                    </div>
                <?php endif ?>


                <?php
                    if(!is_array($data)) {
                        echo $data;
                    }
                 ?>

                <div class="table-responsive">
                <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'servers/import/'.$id, $attributes); ?>
                <table id="table-rates" class="table table-striped">
                    <thead>
                    <tr>
                        <th><?=lang('domain')?></th> 
                        <th><?=lang('username')?></th> 
                        <td><?=lang('email')?></td>
                        <td><?=lang('client')?></td>
                        <td><?=lang('package_name')?></td>
                        <td><?=lang('package')?></td>
                        <td><?=lang('server')?></td>
                        <td><?=lang('import')?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    $data = (is_array($data)) ? $data : array();                    
                    foreach ($data as $acc) { ?>
                    <tr>
                        <td><?=$acc['domain']?></td>
                        <td><?=(isset($acc['user'])) ? $acc['user'] : ''?></td>
                        <td><?=$acc['email']?></td>
                        <td><?=(isset($acc['client'])) ? $acc['client'] : '<span class="label label-default">'.lang('will_create')?></span></td>
                        <td><?=$acc['plan']?></td>
                        <td><?=(isset($acc['package'])) ? $acc['package'] : lang('not_found')?></td>    
                        <td><?=(isset($acc['server'])) ? $acc['server'] : ''?></td>
                        <td><?=(isset($acc['import']) && $acc['import'] == 1) ? '<input type="checkbox" checked name="'.$acc['domain'].'"': ''?></td>                 
                    </tr>
                    <?php }  ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>    
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn btn-success btn-sm btn-block"><?=lang('import')?></button></td>                 
                    </tr>
                    </tbody>
                </table>  
                </form>
              </div>                          
        </div>
 </div>
    