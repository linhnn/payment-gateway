<?php
/**
 * Created by PhpStorm.
 * User: linh.nguyen-ngoc
 * Date: 2/17/2016
 * Time: 7:50 AM
 */

require_once __DIR__ . '/../vendor/autoload.php';

define('MYSQL_HOST', 'localhost'); // MYSQL HOST
define('MYSQL_USERNAME', 'root'); // MYSQL USER NAME
define('MYSQL_PASSWORD', ''); // MYSQL PASSWORD
define('MYSQL_DB', 'payment_gateway'); // MYSQL DATABASE

define('PAYPAL_CLIENT_ID', 'EBWKjlELKMYqRNQ6sYvFo64FtaRLRR5BdHEESmha49TM'); // PAYPAL CLIENT ID
define('PAYPAL_CLIENT_SECRET', 'EO422dn3gQLgDbuwqTjzrFgFtaRLRR5BdHEESmha49TM'); // PAYPAL CLIENT SECRET
define('BRAINTREE_MERCHANT_ID', 'yywg7sxxpy6fx393'); // BRAINTREE MERCHANT ID
define('BRAINTREE_PUBLIC_KEY', '9n8xpkq8qp2zp3ct'); // BRAINTREE PUBLIC KEY
define('BRAINTREE_PRIVATE_KEY', '6391f5af7701ce81aa22214b899d56f0'); // BRAINTREE PRIVATE KEY