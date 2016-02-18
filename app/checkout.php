<?php
    require_once __DIR__ . '/bootstrap.php';
    use App\Models\Payment;
    use App\Models\Order;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $param = $_REQUEST['order'];
        $payment = new Payment();
        $result = $payment->makePayment($param); // make payment by paypal or braintree
        if ($result['type']=='success') {
            $order = new Order();
            $cardExpireMonth = $param['card_expire_month'] < 10 ? '0'.$param['card_expire_month'] : $param['card_expire_month'];
            $param['card_expiration'] = $cardExpireMonth . '/' . $param['card_expire_year'];
			$param['message'] = $result['content'];
            $param['response'] = $result['response'];
            $orderId = $order->addOrder($param, $result); // insert order
            $result['order_id'] = $orderId;
        }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta content="IE=Edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>Payment Gateway</title>
        <link rel="icon" type="image/x-icon" href="../public/img/favicon.ico">
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
        <![endif]-->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    </head>

    <body style="zoom: 1;">
		<div class="row" style="text-align: center; margin: 20px 0 0 10px;">
			<div class="col-md-4">
				<?php if ($result['type']=='success')  { ?>
					<div class="alert alert-success" role="alert"><?php echo $result['content']; ?> Your order id is <?php echo $result['order_id']; ?></div>
				<?php } else { ?>
					<div class="alert alert-danger" role="alert"><?php echo $result['content']; ?></div>
				<?php } ?>
			</div>
		</div>
		<div class="row" style="text-align: center;">
			<div class="col-md-4">
				<a href="index.php">Make new order</a>
			</div>
		</div>
    </body>
<?php
    } else {
        header('Location: index.php');
    }
?>

