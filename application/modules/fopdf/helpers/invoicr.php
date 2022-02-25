<?php
require_once('req/autoload.php');

/*******************************************************************************
* Invoicr                                                                      *
*                                                                              *
* Version: 1.0	                                                               *
* Author:  EpicBrands BVBA                                    				   *
* http://www.epicbrands.be                                                     *
*******************************************************************************/
class invoicr extends tFPDF_rotation 
{

	var $font = 'helvetica';
	var $columnOpacity = 0.06;
	var $columnSpacing = 0.3;
	var $referenceformat = array('.',',',2,2,2);
	var $margins = array('l'=>20,'t'=>20,'r'=>20);

	var $l;
	var $document;
	var $type;
	var $reference;
	var $logo;
	var $color;
	var $date;
	var $due;
	var $from;
	var $to;
	var $items;
	var $totals;
	var $badge;
	var $addText;
	var $footernote;
	var $dimensions;

	
	
	/*******************************************************************************
	*                                                                              *
	*                               Public methods                                 *
	*                                                                              *
	*******************************************************************************/
	function __construct($size='A4',$currency='€',$language='en',$type='invoice')
	{
		$this->columns = 5;
		$this->items = array();
		$this->totals = array();
		$this->addText = array();
		$this->firstColumnWidth = 70;
		$this->currency = $currency;
		$this->maxImageDimensions = array(config_item('invoice_logo_width'),config_item('invoice_logo_height'));
                $this->type = $type;
                
		$v = explode(".", phpversion());
		if ($v[0] > 5 || ($v[0] == 5 && $v[1] > 4)) {
                    $this->font = 'dejavusanscondensed';
                }
                
		$this->setLanguage($language);
		$this->setDocumentSize($size);
		$this->setColor("#222222");
                
		tfpdf::__construct('P','mm',array($this->document['w'],$this->document['h']));
		$this->AliasNbPages();
		$this->SetMargins($this->margins['l'],$this->margins['t'],$this->margins['r']);
		if ($v[0] > 5 || ($v[0] == 5 && $v[1] > 4)) {
			$this->AddFont('dejavusanscondensed','','DejaVuSansCondensed.ttf',true);
			$this->AddFont('dejavusanscondensed','B','DejaVuSansCondensed-Bold.ttf',true);
		} else {
			$this->AddFont('helvetica','');
			$this->AddFont('helvetica','b');
		}
	}
	
	function setType($title)
	{
		$this->title = $title;
	}
	
	function setColor($rgbcolor)
	{
		$this->color = $this->hex2rgb($rgbcolor);
	}
	
	function setDate($date)
	{
		$this->date = $date;
	}
	
	function setDue($date)
	{
		$this->due = $date;
	}
	
	function setLogo($logo=0,$maxWidth=0,$maxHeight=0)
	{
		if($maxWidth and $maxHeight) {
			$this->maxImageDimensions = array($maxWidth,$maxHeight);
		}
		$this->logo = $logo;
		$this->dimensions = $this->resizeToFit($logo);
	}
	
	function setFrom($data)
	{
		$this->from = $data;
	}
	
	function setTo($data)
	{
		$this->to = $data;
	}
	
	function setReference($reference)
	{
		$this->reference = $reference;
	}
	
	function setNumberFormat($decimal_sep,$thousands_sep,$dec,$vdec,$qdec)
	{
		$this->referenceformat = array($decimal_sep,$thousands_sep,$dec,$vdec,$qdec);
	}
	
	function flipflop()
	{
		$this->flipflop = true;
	}
	
	function addItem($item,$description,$quantity,$vat,$price,$discount=0,$total)
	{
		$p['item'] 			= $item;
		$p['description']               = $this->br2nl($description);
		$p['vat']			= $vat;
                if (config_item('currency_position') == 'before') { $cur_before = $this->currency.''; $cur_after = ''; }
                if (config_item('currency_position') == 'after') { $cur_before = ''; $cur_after = ''.$this->currency; }
                if(is_numeric($vat)) {
                        $p['vat'] = $cur_before.number_format($vat,$this->referenceformat[2],$this->referenceformat[0],$this->referenceformat[1]).$cur_after;
                } 
		$p['quantity']                  = number_format($quantity,$this->referenceformat[4],$this->referenceformat[0],$this->referenceformat[1]);
		$p['price']			= $price;
		$p['total']			= $total;
		
		if($discount!==false) {
			$this->firstColumnWidth = 58;
			$p['discount'] = $discount;
			if(is_numeric($discount)) {
				$p['discount']	= $cur_before.number_format($discount,$this->referenceformat[3],$this->referenceformat[0],$this->referenceformat[1]);
			}
			$this->discountField = true;
			$this->columns = 6;
		}
		
		$this->items[]		= $p;
	}
	
	function addTotal($name,$value,$colored=0)
	{
		$t['name']			= $name;
		$t['value']			= $value;
                if (config_item('currency_position') == 'before') { $cur_before = $this->currency.''; $cur_after = ''; }
                if (config_item('currency_position') == 'after') { $cur_before = ''; $cur_after = ''.$this->currency; }
		if(is_numeric($value)) {
			$t['value']			= $cur_before.number_format($value,$this->referenceformat[2],$this->referenceformat[0],$this->referenceformat[1]).$cur_after;
		} 
		$t['colored']		= $colored;
		$this->totals[]		= $t;
	}
	
	function addTitle($title) 
	{
		$this->addText[] = array('title',$title);
	}
	
	function addParagraph($paragraph) 
	{
		$paragraph = $this->br2nl($paragraph);
		$this->addText[] = array('paragraph',$paragraph);
	}
	
	function addBadge($badge)
	{
		$this->badge = $badge;
	}
	
	function setFooternote($note) 
	{
		$this->footernote = $note;
	}
	
	function render($name='',$destination='')
	{
		$this->AddPage();
		$this->Body();
		$this->AliasNbPages();
		$this->Output($name,$destination);
	}
	
	/*******************************************************************************
	*                                                                              *
	*                               Create Invoice                                 *
	*                                                                              *
	*******************************************************************************/
	function Header()
	{
	    if(isset($this->logo)) {
	    	$this->Image($this->logo,$this->margins['l'],$this->margins['t'],$this->dimensions[0],$this->dimensions[1]);
	    }

	    //Title
		$this->SetTextColor(0,0,0);
		$this->SetFont($this->font,'B',20);
                $this->Cell(0,5, mb_strtoupper($this->title),0,1,'R');
		$this->SetFont($this->font,'',10);
		$this->Ln(5);
		
		$lineheight = 5;
		//Calculate position of strings
		$this->SetFont($this->font,'B',10);	
		$positionX = $this->document['w']-$this->margins['l']-$this->margins['r']-max(mb_strtoupper($this->GetStringWidth($this->l['reference_no'])),mb_strtoupper($this->GetStringWidth($this->l['date_issued'])),mb_strtoupper($this->GetStringWidth($this->l['due_date'])))-35;
		
	    //Number
	    $this->Cell($positionX,$lineheight);
	    
	    $this->SetTextColor($this->color[0],$this->color[1],$this->color[2]);
		$this->Cell(32,$lineheight, mb_strtoupper($this->l['reference_no']).':',0,0,'L');
		$this->SetTextColor(50,50,50);
		$this->SetFont($this->font,'',10);
		$this->Cell(0,$lineheight,$this->reference,0,1,'R');
		
		//Date
		$this->Cell($positionX,$lineheight);
		$this->SetFont($this->font,'B',10);
		$this->SetTextColor($this->color[0],$this->color[1],$this->color[2]);
		$this->Cell(32,$lineheight, mb_strtoupper($this->l['date_issued']).':',0,0,'L');	
		$this->SetTextColor(50,50,50);
		$this->SetFont($this->font,'',10);
		$this->Cell(0,$lineheight,$this->date,0,1,'R');
		
		//Due date
		if($this->due) 
		{
			$this->Cell($positionX,$lineheight);
			$this->SetFont($this->font,'B',10);
			$this->SetTextColor($this->color[0],$this->color[1],$this->color[2]);
			$this->Cell(32,$lineheight, mb_strtoupper($this->l['due_date']).':',0,0,'L');	
			$this->SetTextColor(50,50,50);
			$this->SetFont($this->font,'',10);
			$this->Cell(0,$lineheight,$this->due,0,1,'R');
		}
		
		//First page
		if($this->PageNo()==1) 
		{
		
			if(($this->margins['t']+$this->dimensions[1]) > $this->GetY()) 
			{
				$this->SetY($this->margins['t']+$this->dimensions[1]+10);
			} 
			else 
			{
				$this->SetY($this->GetY()+10);
			}
			$this->Ln(5);
			$this->SetTextColor($this->color[0],$this->color[1],$this->color[2]);
			$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);
			$this->SetFont($this->font,'B',10);
			$width = ($this->document['w']-$this->margins['l']-$this->margins['r'])/2;
			if(isset($this->flipflop))
			{
				$to = $this->l['to'];
				$from = $this->l['from'];
				$this->l['to'] = $from;
				$this->l['from'] = $to;
				$to = $this->to;
				$from = $this->from;
				$this->to = $from;
				$this->from = $to;
			}
			$this->Cell($width,$lineheight,mb_strtoupper($this->l['from']),0,0,'L');
			$this->Cell(0,$lineheight,mb_strtoupper($this->l['to']),0,0,'L');
			$this->Ln(7);
			$this->SetLineWidth(0.3);
			$this->Line($this->margins['l'], $this->GetY(),$this->margins['l']+$width-10, $this->GetY());
			$this->Line($this->margins['l']+$width, $this->GetY(),$this->margins['l']+$width+$width, $this->GetY());

			//Information
			$this->Ln(5);
			$this->SetTextColor(50,50,50);
			$this->SetFont($this->font,'B',10);
			$this->Cell($width,$lineheight,$this->from[0],0,0,'L');
			$this->Cell(0,$lineheight,$this->to[0],0,0,'L');
			$this->SetFont($this->font,'',10);
			$this->SetTextColor(100,100,100);
			$this->Ln(7);
			for($i=1; $i<max(count($this->from),count($this->to)); $i++) {
				$this->Cell($width,$lineheight, (isset($this->from[$i]) ? $this->from[$i]:'') ,0,0,'L');
				$this->Cell(0,$lineheight, (isset($this->to[$i]) ? $this->to[$i]:''),0,0,'L');
				$this->Ln(5);
			}	
			$this->Ln(-10);
		}
		$this->Ln(5);
		
		//Table header
		if(!isset($this->productsEnded)) 
		{
			$width_other = ($this->document['w']-$this->margins['l']-$this->margins['r']-$this->firstColumnWidth-($this->columns*$this->columnSpacing))/($this->columns-1);
			$this->SetTextColor(50,50,50);
			$this->Ln(12);
			$this->SetFont($this->font,'B',10);
			$this->Cell(1,10,'',0,0,'L',0);

			

		if((config_item('show_invoice_tax') == 'FALSE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'FALSE'  && $this->type == 'estimate')){
			$this->Cell($this->firstColumnWidth+$width_other,10, mb_strtoupper($this->l['item_name']),0,0,'L',0);
		}else{

			$this->Cell($this->firstColumnWidth,10, mb_strtoupper($this->l['item_name']),0,0,'L',0);
		}

			$this->Cell($this->columnSpacing,10,'',0,0,'L',0);
			$this->Cell($width_other,10, mb_strtoupper($this->l['amount']),0,0,'C',0);
			$this->Cell($this->columnSpacing,10,'',0,0,'L',0);


		if((config_item('show_invoice_tax') == 'TRUE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'TRUE'  && $this->type == 'estimate')){

			$this->Cell($width_other,10, mb_strtoupper($this->l['vat']),0,0,'C',0);
			$this->Cell($this->columnSpacing,10,'',0,0,'L',0);
		}
			$this->Cell($width_other,10, mb_strtoupper($this->l['price']),0,0,'C',0);
			if(isset($this->discountField)) 
			{
				$this->Cell($this->columnSpacing,10,'',0,0,'L',0);
				$this->Cell($width_other,10, mb_strtoupper($this->l['discount']),0,0,'C',0);
			}
			$this->Cell($this->columnSpacing,10,'',0,0,'L',0);
			$this->Cell($width_other,10, mb_strtoupper($this->l['total']),0,0,'C',0);
			$this->Ln();
			$this->SetLineWidth(0.3);
			$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);
			$this->Line($this->margins['l'], $this->GetY(),$this->document['w']-$this->margins['r'], $this->GetY());
			$this->Ln(2);	
		} else 
		{
			$this->Ln(12);	
		}
	}
	
	function Body()
	{	
                if (config_item('currency_position') == 'before') { $cur_before = $this->currency.''; $cur_after = ''; }
                if (config_item('currency_position') == 'after') { $cur_before = ''; $cur_after = ''.$this->currency; }
		$width_other = ($this->document['w']-$this->margins['l']-$this->margins['r']-$this->firstColumnWidth-($this->columns *$this->columnSpacing))/($this->columns-1);
		$cellHeight = 9;
		$bgcolor = (1-$this->columnOpacity)*255;
		if($this->items) {
			foreach($this->items as $item) 
			{
				if($item['description']) 
				{
					//Precalculate height
					$calculateHeight = new invoicr;
					$calculateHeight->addPage();
					$calculateHeight->setXY(0,0);
					$calculateHeight->SetFont($this->font,'',9);	
					$calculateHeight->MultiCell($this->firstColumnWidth,3, $item['description'],0,'L',1);


					$descriptionHeight = $calculateHeight->getY()+$cellHeight+2;
					$pageHeight = $this->document['h']-$this->GetY()-$this->margins['t']-$this->margins['t'];
					if($pageHeight<0) 
					{
						$this->AddPage();
					}
				}
				$cHeight = $cellHeight;
				$this->SetFont($this->font,'B',9);
				$this->SetTextColor(50,50,50);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);
				$this->Cell(1,$cHeight,'',0,0,'L',1);
				$x = $this->GetX();


		if((config_item('show_invoice_tax') == 'FALSE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'FALSE'  && $this->type == 'estimate')){
				$this->Cell($this->firstColumnWidth+$width_other,$cHeight, $item['item'],0,0,'L',1);
			}else{
				$this->Cell($this->firstColumnWidth,$cHeight, $item['item'],0,0,'L',1);
			}

			

				if($item['description'])
				{
					$resetX = $this->GetX();
					$resetY = $this->GetY();
					$this->SetTextColor(120,120,120);
					$this->SetXY($x,$this->GetY()+8);
					$this->SetFont($this->font,'',9);	


		if((config_item('show_invoice_tax') == 'FALSE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'FALSE'  && $this->type == 'estimate')){
			$this->MultiCell($this->firstColumnWidth+$width_other,3, $item['description'],0,'L',1);
			}else{
					$this->MultiCell($this->firstColumnWidth,3, $item['description'],0,'L',1);
			}
					//Calculate Height
					$newY = $this->GetY();
					$cHeight = $newY-$resetY+2;
					//Make our spacer cell the same height
					$this->SetXY($x-1,$resetY);

					$this->Cell(1,$cHeight,'',0,0,'L',1);
					//Draw empty cell
					$this->SetXY($x,$newY);

		if((config_item('show_invoice_tax') == 'FALSE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'FALSE'  && $this->type == 'estimate')){
					$this->Cell($this->firstColumnWidth+$width_other,2,'',0,0,'L',1);
			}else{
					$this->Cell($this->firstColumnWidth,2,'',0,0,'L',1);
			}
					$this->SetXY($resetX,$resetY);	
				}
				$this->SetTextColor(50,50,50);
				$this->SetFont($this->font,'',9);
				$this->Cell($this->columnSpacing,$cHeight,'',0,0,'L',0);
				$this->Cell($width_other,$cHeight,$item['quantity'],0,0,'C',1);
				$this->Cell($this->columnSpacing,$cHeight,'',0,0,'L',0);

				//$this->Cell($width_other,$cHeight,iconv(‘UTF-8’, ‘windows-1252’, $item[‘vat’]),0,0,’C’,1);
		if((config_item('show_invoice_tax') == 'TRUE' && $this->type == 'invoice') || 
                    (config_item('show_estimate_tax') == 'TRUE'  && $this->type == 'estimate')){
				$this->Cell($width_other,$cHeight, $item['vat'],0,0,'C',1);
			}

				$this->Cell($this->columnSpacing,$cHeight,'',0,0,'L',0);
				$this->Cell($width_other,$cHeight, $cur_before.number_format($item['price'],$this->referenceformat[2],$this->referenceformat[0],$this->referenceformat[1]).$cur_after."  ",0,0,'R',1);
				if(isset($this->discountField)) 
				{
					$this->Cell($this->columnSpacing,$cHeight,'',0,0,'L',0);
					if(isset($item['discount'])) 
					{
						$this->Cell($width_other,$cHeight,$cur_before.number_format($item['discount'],$this->referenceformat[2],$this->referenceformat[0],$this->referenceformat[1]).$cur_after."  ",0,0,'R',1);
					} 
					else 
					{
						$this->Cell($width_other,$cHeight,'',0,0,'C',1);
					}
				}
				$this->Cell($this->columnSpacing,$cHeight,'',0,0,'L',0);
				$this->Cell($width_other,$cHeight, $cur_before.number_format($item['total'],$this->referenceformat[2],$this->referenceformat[0],$this->referenceformat[1]).$cur_after."  ",0,0,'R',1);
				$this->Ln();
				$this->Ln($this->columnSpacing);
			}
		}
		$badgeX = $this->getX();
		$badgeY = $this->getY();
		
		//Add totals
		if($this->totals) 
		{
			foreach($this->totals as $total) 
			{
				$this->SetTextColor(50,50,50);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);
				$this->Cell(1+$this->firstColumnWidth,$cellHeight,'',0,0,'L',0);
				for($i=0;$i<$this->columns-4;$i++) 
				{
					$this->Cell($width_other,$cellHeight,'',0,0,'L',0);
					$this->Cell($this->columnSpacing,$cellHeight,'',0,0,'L',0);
				}
				if($total['colored']) 
				{
					$this->SetTextColor(255,255,255);
					$this->SetFillColor($this->color[0],$this->color[1],$this->color[2]);
				}
				$this->SetFont($this->font,'B',9);
				$this->Cell(1,$cellHeight,'',0,0,'L',1);
				$this->Cell(($width_other*2)-$this->columnSpacing,$cellHeight,$total['name'],0,0,'L',1);
				$this->Cell($this->columnSpacing,$cellHeight,'',0,0,'L',0);
				$this->SetFont($this->font,'B',9);
				$this->SetFillColor($bgcolor,$bgcolor,$bgcolor);
				if($total['colored']) 
				{
					$this->SetTextColor(255,255,255);
					$this->SetFillColor($this->color[0],$this->color[1],$this->color[2]);
				}
				$this->Cell($width_other,$cellHeight,$total['value']."  ",0,0,'R',1);
				$this->Ln();
				$this->Ln($this->columnSpacing);
			}
		}
		$this->productsEnded = true;
		$this->Ln();
		$this->Ln(3);
		
		
		//Badge
		if($this->badge) 
		{
			$badge = ' '.mb_strtoupper($this->badge).' ';
			$resetX = $this->getX();
			$resetY = $this->getY();
			$this->setXY($badgeX,$badgeY+15);
			$this->SetLineWidth(0.4);
			$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);		
			$this->setTextColor($this->color[0],$this->color[1],$this->color[2]);
			$this->SetFont($this->font,'B',16);
			$this->Rotate(10,$this->getX(),$this->getY());
			$this->Rect($this->GetX(),$this->GetY(),$this->GetStringWidth($badge)+2,10);
			$this->Write(10,$badge);
			$this->Rotate(0);
			if($resetY>$this->getY()+20) 
			{
				$this->setXY($resetX,$resetY);
			} 
			else 
			{
				$this->Ln(18);
			}
		}
		
		//Add information
		foreach($this->addText as $text) 
		{
			if($text[0] == 'title') 
			{
				$this->SetFont($this->font,'B',10);
				$this->SetTextColor(50,50,50);
				$this->Cell(0,10, mb_strtoupper($text[1]),0,0,'L',0);
				$this->Ln();
				$this->SetLineWidth(0.3);
				$this->SetDrawColor($this->color[0],$this->color[1],$this->color[2]);
				$this->Line($this->margins['l'], $this->GetY(),$this->document['w']-$this->margins['r'], $this->GetY());
				$this->Ln(4);
			}
			if($text[0] == 'paragraph') 
			{
				$this->SetTextColor(80,80,80);
				$this->SetFont($this->font,'',10);
				$this->MultiCell(0,4, strip_tags($text[1]),0,'L',0);
				$this->Ln(4);
			}
		}
	}

	function Footer()
	{
		$this->SetY(-$this->margins['t']);
		$this->SetFont($this->font,'',9);
		$this->SetTextColor(50,50,50);
		$this->Cell(0,10,$this->footernote,0,0,'L');
		$this->Cell(0,10,$this->l['page'].' '.$this->PageNo().' '.$this->l['page_of'].' {nb}',0,0,'R');
	}
	function getLanguage($language)
	{
		$this->language = $language;
		include('languages/'.$language.'.inc');
                if ($language == 'el') { return $this->stripAccents($l); }
                return $l;
	}
	
	/*******************************************************************************
	*                                                                              *
	*                               Private methods                                *
	*                                                                              *
	*******************************************************************************/
        
        function stripAccents($str = array()) {
                $chars = array("Ά"=>"Α","ά"=>"α","Έ"=>"Ε","έ"=>"ε","Ή"=>"Η","ή"=>"η","Ί"=>"Ι","ί"=>"ι","Ό"=>"Ο","ό"=>"ο","Ύ"=>"Υ","ύ"=>"υ","Ώ"=>"Ω","ώ"=>"ω");
                $ignore = array('estimate_cost', 'tax', 'paid', 'balance_due', 'page', 'page_of');
                foreach ($str as $key => $value) {
                    $new = $value;
                    if (!in_array($key, $ignore)) {
                        foreach ($chars as $find => $replace) {
                            $new = str_replace($find, $replace, $new);
                        }
                    }
                    $str[$key] = $new;
                }
                return $str;
        }
        
	private function setLanguage($language)
	{
		$this->language = $language;
		include('languages/'.$language.'.inc');
		$this->l = $l;
                if ($language == 'el') { $this->l = $this->stripAccents($l); }
	}
	
	private function setDocumentSize($dsize)
	{
		switch ($dsize)
		{
			case 'A4':
				$document['w'] = 210;
				$document['h'] = 297;
				break;
			case 'letter':
				$document['w'] = 215.9;
				$document['h'] = 279.4;
				break;
			case 'legal':
				$document['w'] = 215.9;
				$document['h'] = 355.6;
				break;
			default:
				$document['w'] = 210;
				$document['h'] = 297;
				break;
		}
		$this->document = $document;
	}
	
	private function resizeToFit($image)
	{
		list($width, $height) = getimagesize($image);
		$newWidth = $this->maxImageDimensions[0]/$width;
		$newHeight = $this->maxImageDimensions[1]/$height;
		$scale = min($newWidth, $newHeight);
		return array(
			round($this->pixelsToMM($scale * $width)),
			round($this->pixelsToMM($scale * $height))
		);
	}
	    
	private function pixelsToMM($val) 
	{
		$mm_inch = 25.4;
		$dpi = 96;
                $ratio = 1.3;
		return intval(($val * $mm_inch/$dpi) / $ratio);
	}
	
	private function hex2rgb($hex)
	{
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return $rgb;
	}
	
	private function br2nl($string)
	{
    	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	}  

}