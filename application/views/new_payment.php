<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><title></title></head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
    <p><?=strftime("%b %d, %Y", time()); ?> </p>
    <p>Hi <?=$email?> <br> <br>
    	This is to let you know that a new payment has been received: <br><br>

    	INVOICE REF: <?=$invoice_ref?><br>
    	CLIENT NAME: <?=$client?><br>
    	AMOUNT PAID: <?=App::currencies($currency)->symbol;?><?=$amount?><br>
    </p>

    	<p>The Payment has been applied to the Invoice Successfully. </p>
		<p>You can view the Invoice <a href="<?=base_url()?>invoices/view/<?=$invoice_id?>">View Invoice</a></p>
		<br>

		------------------------------

		<p>Regards </p> <br>
</body>
</html>
