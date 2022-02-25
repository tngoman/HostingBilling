<?php 
$cur = App::currencies(config_item('default_currency'));
$customer = ($client > 0) ? Client::view_by_id($client) : array();
?>

<div class="box">
  <div class="box-header b-b"> 
             <?=$this->load->view('report_header');?>

             <?php if($this->uri->segment(3) && isset($customer->co_id)){ ?>
              <a href="<?=base_url()?>reports/invoicespdf/<?=$customer->co_id;?>" class="btn btn-dark btn-sm pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
             
             </div>


            <div class="box-body">


<div class="fill body reports-top rep-new-band">
<div class="criteria-container fill-container hidden-print">
  <div class="criteria-band">
    <address class="row">
     <?php echo form_open(base_url().'reports/view/invoicesbyclient'); ?>
    


        <div class="col-md-4">
          <label><?=lang('client_name')?> </label>
          <i class="fa fa-search"></i>&nbsp;
    <span></span> <b class="caret"></b>
          <select class="select2-option w_280" name="client" >
                                            <optgroup label="<?=lang('clients')?>">
                                                <?php foreach (Client::get_all_clients() as $c): ?>
                                                    <option value="<?=$c->co_id?>" <?=($client == $c->co_id) ? 'selected="selected"' : '';?>>
                                                    <?=ucfirst($c->company_name)?></option>
                                                <?php endforeach;  ?>
                                            </optgroup>
                                        </select>
        </div>
   
      <div class="col-md-2">  
  <button class="btn btn-<?=config_item('theme_color')?>" type="submit">
    <?=lang('run_report')?>
  </button>
</div>



    </address>
  


  </div>
</div>


</form>

<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('invoices_report')?></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>

<table class="table zi-table table-hover norow-action small"><thead>
  <tr>
<th class="text-left">
  <div class="pull-left over-flow"><?=lang('status')?></div>
</th>
         <th class="text-left">
  <div class="pull-left over-flow"> <?=lang('invoice_date')?></div>
  
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('due_date')?></div>
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('invoice')?>#</div>
</th>
         
         <th class="sortable text-right">
  <div class=" over-flow"> <?=lang('invoice_amount')?></div>
</th>
         <th class="sortable text-right">
  <div class=" over-flow"> <?=lang('balance_due')?></div>
</th>
  </tr>
</thead>

<tbody>

<?php 
$due_total = 0;
$invoice_total = 0;
foreach ($invoices as $key => $invoice) { 
  $status = Invoice::payment_status($invoice->inv_id);
  $text_color = 'info';
  switch ($status) {
    case 'fully_paid':
      $text_color = 'success';
      break;
    case 'not_paid':
      $text_color = 'danger';
      break;
  }
  ?>
        <tr>
        <td><div class="text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>

        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $payable = Applib::convert_currency($invoice->currency, Invoice::payable($invoice->inv_id));
          echo Applib::format_currency($cur->code,$payable);
          $invoice_total += $payable;
        }else{
          $invoice_total += Invoice::payable($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::payable($invoice->inv_id));
        }
        ?></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $due = Applib::convert_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id));
          $due_total += $due;
          echo Applib::format_currency($cur->code,$due);
          }else{
          $due_total += Invoice::get_invoice_due_amount($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($invoice->inv_id));
          }
          ?></td>
      </tr>
<?php } ?>

        <tr class="hover-muted bt">
          <td colspan="4"><?=lang('total')?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$invoice_total)?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$due_total)?></td>
        </tr>


<!----></tbody>
</table>  </div>
    
 
</div>
</div>
