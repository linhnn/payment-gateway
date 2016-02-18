Payment Gateway using PayPal REST API and Braintree payments
===============================

Overview
--------

https://github.com/HQInterview/PHP-Round1

Pre-requisites
--------------

   * PHP 5.3+
   * curl, openssl and mysql PHP extensions
   * [Composer](http://getcomposer.org/download/) for installing the Rest API SDK.
   * Mysql 5.x server 

	
Running the app
---------------

   * Copy the payment-gateway folder to your htdocs folder.
   * Run 'composer update' from the root directory.
   * Create a new database called *payment_gateway* and update connection parameters in app/bootstrap.php file.
   * Create the necessary tables as give in install/db.sql or simply run the install/create_tables.php file
   * Optionally, update *app/bootstrap.php* with your own client id and client secret.
   * You are ready. Bring up http://localhost/payment-gateway on your favorite browser.

Credit Card Sample
------------------

```
Credit card number: 4417119669820331
Credit card expiration: 11/2019
Credit card CCV: 021

Credit card number: 4500600000000061
Credit card expiration: 11/2019
Credit card CCV: 021
```

Tests
---------------

You can run the test suite by PHPUnit:

```
phpunit test/ValidationTest.php

phpunit test/PaymentTest.php
```

Author
---------------
Nguyen Ngoc Linh

Email: linhvt22@gmail.com
	 
