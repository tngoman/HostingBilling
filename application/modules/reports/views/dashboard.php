<?php
$chart_year = ($this->session->userdata('chart_year')) ? $this->session->userdata('chart_year') : date('Y');
$cur = App::currencies(config_item('default_currency'));
$this->lang->load('calendar',config_item('language'));
$total_receipts = $this->applib->get_sum('payments', 'amount', $array = array('inv_deleted' => 'No'));
$invoice_amount = $this->applib->get_sum('items', 'total_cost', $array = array('total_cost >' => '0'));
$total_sales = $invoice_amount + $this->applib->total_tax();
$outstanding = $total_sales - $total_receipts;
if ($outstanding < 0) {
    $outstanding = 0;
}
if ($total_sales > 0) {
    $perc_paid = ($total_receipts / $total_sales) * 100;
    if ($perc_paid > 100) {
        $perc_paid = '100';
    } else {
        $perc_paid = round($perc_paid, 1);
    }
    $perc_outstanding = round(100 - $perc_paid, 1);
} else {
    $perc_paid = 0;
    $perc_outstanding = 0;
}
?> 

  <div class="box">
           <div class="box-header">
            <?=$this->load->view('report_header');?>

        </div>

            <div class="box-body">
 
            <div class="row">


            <div class="col-md-8">
            <div class="box box-default box-solid">
            <div class="box-header">
            <?=lang('invoiced_monthly')?>
            </div>
            <div class="box-body">
            <canvas id="line-chart"></canvas>
                </div>
                </div>
            </div>

             <div class="col-sm-4">

             <div class="box box-default">
              <div class="box-header">
              <?=lang('invoices')?>: <?=Applib::format_currency($cur->code, $total_sales);?>
              </div>
                <div class="box-body">
                    <div class="chart-responsive">
                        <canvas id="pieChart" height="150"></canvas>
                    </div> 
                </div> 
              </div> 


             <div class="box box-solid">
              <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                   
                  <tbody>
                  <tr>
                    <td><?=lang('total_sales')?> </td>
                    <td><?=Applib::format_currency($cur->code,Report::total_paid());?> </td>
                  </tr>

                  <tr>
                    <td><?=lang('collected_this_year')?> </td>
                    <td><?=Applib::format_currency($cur->code,Report::year_amount(date('Y')));?></td>
                  </tr>

                  <tr>
                    <td><?=lang('paid_this_month')?></td>
                    <td><?=Applib::format_currency($cur->code,Report::month_amount(date('Y'),date('m')));?></td>
                  </tr>

                  <tr>
                    <td><?=lang('paid_last_month')?></td>
                    <td><?=Applib::format_currency($cur->code,Report::month_amount(date('Y'),date('m')-1));?></td>
                  </tr>

                  <tr>
                    <td><?=lang('payments_received')?></td>
                    <td><?=Report::num_payments()?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>

      </div>
   </div> 

            </div>

            <div class="row ">



<!-- 1st Quarter -->
      <div class="col-md-3 col-sm-6">
        <div class="box box-info">
          <header class="box-header">
            <h4 class="widget-title">1st <?=lang('quarter')?>, <?=$chart_year?></h4>
          </header><!-- .widget-header -->
          <hr class="widget-separator">
          <div class="widget-body p-t-lg">
          <?php
          $total_jan = Report::month_amount($chart_year, '01');
          $total_feb = Report::month_amount($chart_year, '02');
          $total_mar = Report::month_amount($chart_year, '03');
          $sum = array($total_jan,$total_feb,$total_mar);
          ?>
            <div class="clearfix m-b-md small text-muted"><?=lang('cal_january')?><div class="pull-right ">
            <?=Applib::format_currency($cur->code,$total_jan);?></div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_february')?><div class="pull-right ">
              <?=Applib::format_currency($cur->code,$total_feb);?>
            </div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_march')?><div class="pull-right ">
              <?=Applib::format_currency($cur->code,$total_mar);?>
            </div>
            </div>

            <div class="clearfix m-b-md small">
              <div class="pull-right text-dark"><strong>
              <?=Applib::format_currency($cur->code,array_sum($sum));?></strong></div>
            </div>

            </div><!-- .widget-body -->
        </div><!-- .widget -->
      </div>

      <!-- 2nd Quarter -->
<div class="col-md-3 col-sm-6">
  <div class="box box-info">
          <header class="box-header">
            <h4 class="widget-title">2nd <?=lang('quarter')?>, <?=$chart_year?></h4>
          </header><!-- .widget-header -->
          <hr class="widget-separator">
          <div class="widget-body p-t-lg">
          <?php
          $total_apr = Report::month_amount($chart_year, '04');
          $total_may = Report::month_amount($chart_year, '05');
          $total_jun = Report::month_amount($chart_year, '06');
          $sum = array($total_apr,$total_may,$total_jun);
          ?>
            <div class="clearfix m-b-md small text-muted"><?=lang('cal_april')?><div class="pull-right">
            <?=Applib::format_currency($cur->code,$total_apr);?></div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_may')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_may);?>
            </div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_june')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_jun);?>
            </div>
            </div>

            <div class="clearfix m-b-md small">
              <div class="pull-right text-dark"><strong>
              <?=Applib::format_currency($cur->code,array_sum($sum));?></strong></div>
            </div>

            </div><!-- .widget-body -->
        </div><!-- .widget -->
</div>

<!-- 3rd Quarter -->

<div class="col-md-3 col-sm-6">
        <div class="box box-info">
          <header class="box-header">
            <h4 class="widget-title">3rd <?=lang('quarter')?>, <?=$chart_year?></h4>
          </header><!-- .widget-header -->
          <hr class="widget-separator">
          <div class="widget-body p-t-lg">
          <?php
          $total_jul = Report::month_amount($chart_year, '07');
          $total_aug = Report::month_amount($chart_year, '08');
          $total_sep = Report::month_amount($chart_year, '09');
          $sum = array($total_jul,$total_aug,$total_sep);
          ?>
            <div class="clearfix m-b-md small text-muted"><?=lang('cal_july')?><div class="pull-right">
            <?=Applib::format_currency($cur->code,$total_jul);?></div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_august')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_aug);?>
            </div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_september')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_sep);?>
            </div>
            </div>

            <div class="clearfix m-b-md small">
              <div class="pull-right text-dark"><strong>
              <?=Applib::format_currency($cur->code,array_sum($sum));?></strong></div>
            </div>

            </div><!-- .widget-body -->
        </div><!-- .widget -->
</div>
<!-- 4th Quarter -->

<div class="col-md-3 col-sm-6">
        <div class="box box-info">
          <header class="box-header">
            <h4 class="widget-title">4th <?=lang('quarter')?>, <?=$chart_year?></h4>
          </header><!-- .widget-header -->
          <hr class="widget-separator">
          <div class="widget-body p-t-lg">
          <?php
          $total_oct = Report::month_amount($chart_year, '10');
          $total_nov = Report::month_amount($chart_year, '11');
          $total_dec = Report::month_amount($chart_year, '12');
          $sum = array($total_oct,$total_nov,$total_dec);
          ?>
            <div class="clearfix m-b-md small text-muted"><?=lang('cal_october')?><div class="pull-right">
            <?=Applib::format_currency($cur->code,$total_oct);?></div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_november')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_nov);?>
            </div>
            </div>

            <div class="clearfix m-b-md small text-muted"><?=lang('cal_december')?><div class="pull-right">
              <?=Applib::format_currency($cur->code,$total_dec);?>
            </div>
            </div>

            <div class="clearfix m-b-md small">
              <div class="pull-right text-dark"><strong>
              <?=Applib::format_currency($cur->code,array_sum($sum));?></strong></div>
            </div>

            </div><!-- .widget-body -->
        </div><!-- .widget -->
</div>
      <!-- End Quarters -->

            </div>
            <!-- End Row -->

            <div class="row">


            <div class="col-sm-6">
                            <section class="box box-default box-solid">
                                <header class="box-header"><?=lang('top_clients')?></header>
                                <div class="box-body">
                                <section class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">


                <ul class="list-group alt">

              <?php foreach (Report::top_clients(20) as $key => $client) { ?>
                      <li class="list-group-item">
                            <div><a href="<?=base_url()?>companies/view/<?=$client->co_id?>">
                            <?=Client::view_by_id($client->co_id)->company_name?></a>
                            <small class="text-muted pull-right">
                            <?=Applib::format_currency($cur->code,Client::amount_paid($client->co_id));?>
                              </small>
                            </div>

                      </li>


              <?php } ?>
                    </ul>



                                </section>
                              </div>

                            </section>
            </div>

            <div class="col-sm-6">
                            <section class="box box-default box-solid">
                                <header class="box-header"><?=lang('outstanding')?></header>
                                <div class="box-body">
                                <section class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">


               <ul class="list-group alt">
               <?php foreach (Report::outstanding() as $key => $i) {  ?>
                      <li class="list-group-item">
                            <div><a href="<?=base_url()?>invoices/view/<?=$i->inv_id?>">
                            <?=$i->reference_no;?></a>
                            <small class="text-muted pull-right">
                            <?php if ($i->currency != config_item('default_currency')) {
                                echo Applib::format_currency($cur->code,Applib::convert_currency($i->currency, Invoice::get_invoice_due_amount($i->inv_id)));
                                  }else{
                                echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($i->inv_id));
                                  }
                              ?>
                              </small>
                            </div>

                      </li>


              <?php } ?>
                    </ul>

                    </section>
                </div>
            </section>
            </div> 
            </div>
            <!-- End Row -->  

            </div>
 
        <!-- end -->

 
<script src="<?=base_url()?>resource/js/charts/chartjs/Chart.min.js"></script> 
<script type="text/javascript">

(function($){
"use strict";

  var ctx = $("#line-chart");
  var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      datasets: [
        {
          label: "<?php echo (isset($_GET['setyear'])) ? $_GET['setyear'] : date('Y')?>",
          data: [
          <?=Report::invoiced($chart_year,'01')?>,
          <?=Report::invoiced($chart_year,'02')?>,
          <?=Report::invoiced($chart_year,'03')?>,
          <?=Report::invoiced($chart_year,'04')?>,
          <?=Report::invoiced($chart_year,'05')?>,
          <?=Report::invoiced($chart_year,'06')?>,
          <?=Report::invoiced($chart_year,'07')?>,
          <?=Report::invoiced($chart_year,'08')?>,
          <?=Report::invoiced($chart_year,'09')?>,
          <?=Report::invoiced($chart_year,'10')?>,
          <?=Report::invoiced($chart_year,'11')?>,
          <?=Report::invoiced($chart_year,'12')?>
          ]
        }
      ]
    }
  });



  new Chart($("#pieChart"), {
      type: 'pie',
      data: {
        labels: ["<?php echo lang('outstanding').' '.$perc_outstanding.'%'; ?>", "<?php echo lang('paid').' '.$perc_paid.'%' ?>"],
        datasets: [{
          label: "<?php echo lang('sales') ?>",
          backgroundColor: ["#e8c3b9","#3cba9f"],
          data: [<?php echo $outstanding; ?>, <?php echo $total_receipts; ?>]
        }]
      },
      options: {
        title: {
          display: true,
          text: ''
        }
      }
  });
})(jQuery); 
</script>
