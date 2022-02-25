<link rel="stylesheet" href="<?=base_url()?>resource/css/app.css" type="text/css" />
<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.pure-table .table td {
    vertical-align: middle !important;
}
</style>
<?php
ini_set('memory_limit', '-1'); 
$cur = App::currencies(config_item('default_currency'));
$customer = ($client > 0) ? Client::view_by_id($client) : array();
?>

  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('invoices_report')?></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>


<table class="table pure-table">
<thead>
  <tr>
<th><?=lang('status')?></th>
<th><?=lang('invoice_date')?></th>
<th><?=lang('due_date')?></th>
<th><?=lang('invoice')?>#</th>
<th class="text-right"><?=lang('amount')?></th>
<th class="text-right"><?=lang('balance_due')?></th>
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
        <td><div class="small text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><?=$invoice->reference_no?></td>

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

        <tr class="hover-muted">
          <td colspan="4"><strong><?=lang('total')?></strong></td>
          <td class="text-right"><strong><?=Applib::format_currency($cur->code,$invoice_total)?></strong></td>
          <td class="text-right"><strong><?=Applib::format_currency($cur->code,$due_total)?></strong></td>
        </tr>


<!----></tbody>
</table>
    


