<?php
//Set default date timezone
if (config_item('timezone')) { date_default_timezone_set(config_item('timezone')); }

$inv = Invoice::view_by_id($id);
$cur = App::currencies($inv->currency);
$pos = config_item('currency_position');
$dec = config_item('currency_decimals');
$vdec = config_item('tax_decimals');
$qdec = config_item('quantity_decimals');

// Get invoice and client info
$client = Client::view_by_id($inv->client);
$language = App::languages($client->language);
 
          $invoice = new invoicr("A4", $cur->symbol, $language->code, 'invoice');
          //$invoice->AddFont('lato','','lato.php');
          $invoice->currency = $cur->symbol;
          $v = explode(".", phpversion());
          if ($v[0] < 5 || ($v[0] == 5 && $v[1] < 5)) {
              if ($cur->code == 'EUR') { $invoice->currency = chr(128); }
              if ($cur->code == 'GBP') { $invoice->currency = chr(163); }
          }
          
          $lang = $invoice->getLanguage($language->code);
          $lang2 = $lang2 = $this->lang->load('hd_lang', $language->name, TRUE, FALSE, '', TRUE);
          
          //Set number formatting
          $invoice->setNumberFormat(config_item('decimal_separator'), config_item('thousand_separator'), $dec, $vdec, $qdec);
          //Set your logo
          $invoice->setLogo("resource/images/logos/".config_item('invoice_logo')); // $invoice->setLogo(image,maxwidth,maxheight);
          //Set theme color
          $invoice->setColor(config_item('invoice_color'));
          //Set type
          $invoice->setType($lang['invoice']);
          //Set reference
          
          $invoice->setReference($inv->reference_no);
          //Set date
          $invoice->setDate(
                            strftime(config_item('date_format'),
                            strtotime($inv->date_saved))
                            );
          //Set due date
          $invoice->setDue(
                          strftime(config_item('date_format'),
                          strtotime($inv->due_date))
                          );
          
          //Set from
          $sfx = "_".$language->name;
          
          $city_from = config_item('company_city'.$sfx) ? config_item('company_city'.$sfx) : config_item('company_city');
          $zip_from = config_item('company_zip_code'.$sfx) ? config_item('company_zip_code'.$sfx) : config_item('company_zip_code');
          if (!empty($zip_from)) { $city_from .= ", ".$zip_from; }
          
          $state_from = config_item('company_state'.$sfx) ? config_item('company_state'.$sfx) : config_item('company_state');
          $country_from = config_item('company_country'.$sfx) ? config_item('company_country'.$sfx) : config_item('company_country');
          if (!empty($state_from)) { $country_from = $state_from.", ".$country_from; }
          
          $city_to = $client->city;
          $zip_to = $client->zip;
          if (!empty($zip_to)) { $city_to .= ", ".$zip_to; }
          
          $state_to = $client->state;
          $country_to = $client->country;
          if (!empty($state_to)) { $country_to = $state_to.", ".$country_to; }
          
          $address = (config_item('company_address'.$sfx) ? config_item('company_address'.$sfx) : config_item('company_address'));
          $address = str_replace("\r", "", $address);
          $address = str_replace("\n", "", $address);
          
          $from_vat = (config_item('company_vat') != '') 
                    ? $lang2['company_vat'].' | '.(config_item('company_vat'.$sfx) ? config_item('company_vat'.$sfx) : config_item('company_vat')) 
                    : '';
                    
          
          
          $from = array(
                  (config_item('company_legal_name'.$sfx) ? config_item('company_legal_name'.$sfx) : config_item('company_legal_name')),
                  $address,
                  $city_from,
                  $country_from,
                  $from_vat,
                  );
          if (config_item('company_registration'.$sfx) != '' || config_item('company_registration') != '') {
              $from[] = $lang2['company_registration'].' | '.(config_item('company_registration'.$sfx) ? config_item('company_registration'.$sfx) : config_item('company_registration'));
          }
          
          $to_vat = ($client->VAT != '') 
                    ? $lang2['company_vat'].' | '.$client->VAT 
                    : '';
          
          $to = array($client->company_name, $client->company_address, $city_to, $country_to, $to_vat);
          
          $invoice->setFrom($from);
          $invoice->setTo($to);
          if (config_item('swap_to_from') == 'TRUE') { $invoice->flipflop(); }
          
          // Calculate Invoice
          $sub_total = Invoice::get_invoice_subtotal($inv->inv_id);
          $tax = Invoice::get_invoice_tax($inv->inv_id);
          $tax2 = Invoice::get_invoice_tax($inv->inv_id,'tax2');
          $discount = Invoice::get_invoice_discount($inv->inv_id);
          $paid = Invoice::get_invoice_paid($inv->inv_id);
          $fee = Invoice::get_invoice_fee($inv->inv_id);
          $invoice_cost = Invoice::get_invoice_due_amount($inv->inv_id);
          //Add items
          foreach (Invoice::has_items($inv->inv_id) as $key => $item) {
                              if(config_item('show_invoice_tax') == 'TRUE'){
                                $show_tax = $item->item_tax_total;
                              } else{
                                $show_tax = false;
                              }
          $invoice->addItem(
                          $item->item_name,
                            $item->item_desc,
                            Applib::format_quantity($item->quantity),
                            $show_tax,
                            $item->unit_cost,
                            false,
                            $item->total_cost
                            );
          } 
          //Add totals
          $invoice->addTotal($lang['total']." ",$sub_total);
          
          if ($inv->tax > 0.00) {
          $invoice->addTotal(config_item('tax_name')." (".number_format($inv->tax, $vdec, config_item('decimal_separator'), config_item('thousand_separator'))."%)",$tax);
          }
          
          if ($inv->tax2 > 0.00) {
          $invoice->addTotal($lang['tax']." 2 (".number_format($inv->tax2, $vdec, config_item('decimal_separator'), config_item('thousand_separator'))."%)",$tax2);
          }
          
          if($inv->discount != 0){
            $invoice->addTotal($lang['discount']." ".number_format($inv->discount, $vdec, config_item('decimal_separator'), config_item('thousand_separator'))."%",$discount);
          }
          if($paid > 0){
            $invoice->addTotal($lang['paid']." ",$paid);
          }
          if($fee > 0){
            $invoice->addTotal(lang('extra_fee').' - '.$inv->extra_fee."% ",$fee);
          }
          
          $invoice->addTotal($lang['balance_due']." ",$invoice_cost,true);
 



//Set badge
if (config_item('display_invoice_badge') == 'TRUE') {
 $invoice->addBadge($lang[Invoice::payment_status($inv->inv_id)]);
}

//Add title
$invoice->addTitle($lang['payment_information']);
//Add Paragraph
$invoice->addParagraph(nl2br($inv->notes));
//Set footer note
$invoice->setFooternote(config_item('invoice_footer'));

if(isset($attach)){
  $render = 'F';
  $invoice->render('./resource/tmp/'.lang('invoice').' '.$inv->reference_no.'.pdf',$render);
}else{
  $render = 'D';
  $invoice->render($lang['invoice'].' '.$inv->reference_no.'.pdf',$render);
}
//Render