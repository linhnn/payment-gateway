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
        <!-- CSS -->
        <link rel="stylesheet" href="../public/css/bootstrap.min.css">
        <link rel="stylesheet" href="../public/css/style.css">
    </head>

    <body style="zoom: 1;">
        <div class="col-md-10">
            <h1>Payment Gateway</h1>
            <form class="payment_gateway_form" action="checkout.php" method="POST">
                <div class="row">
                    <div class="col-md-4"><!-- Order Section -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">Order</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="order_amount">Price</label>
                                    <input type="text" name="order[amount]" class="form-control" required pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)">
                                </div>
                                <div class="form-group">
                                    <label for="order_currency">Currency</label>
                                    <select name="order[currency]" class="form-control">
                                        <?php $currencyArr = array('USD','EUR','THB','HKD','SGD','AUD'); ?>
                                        <?php foreach ($currencyArr as $currency) { ?>
                                            <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="order_customer_name">Customer Full Name</label>
                                    <input type="text" name="order[customer_name]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6"><!-- Payment Section -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">Payment</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="order_card_name">Credit card holder name</label>
                                    <input type="text" name="order[card_name]" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="order_card_number">Credit card number</label>
                                    <input type="text" name="order[card_number]" class="form-control" required pattern="[0-9]{13,16}">
                                </div>
                                <div class="form-group">
                                    <label for="order_card_expire_month">Expire month</label>
                                    <select name="order[card_expire_month]" class="form-control">
                                        <?php for ($i=1; $i<=12; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="order_card_expire_year">Expire year</label>
                                    <select name="order[card_expire_year]" class="form-control">
                                        <?php for ($i=2016; $i<=2026; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="order_card_ccv">Credit card CCV</label>
                                    <input type="text" name="order[card_ccv]" class="form-control" required pattern="\d*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row"><!-- Submit Button -->
                    <div class="col-md-10">
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- JS -->
        <script src="../public/js/jquery.min.js" type="text/javascript"></script>
        <script src="../public/js/script.js" type="text/javascript"></script>
    </body>
</html>