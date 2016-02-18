<?php
/**
 * Created by PhpStorm.
 * User: linh.nguyen-ngoc
 * Date: 2/17/2016
 * Time: 11:13 AM
 */

namespace App\Models;

class Order {
    /**
     * Insert order into database
     * @param $order
     * @return int
     * @throws \Exception
     */
    public function addOrder($order, &$messageArr) {
        $link = @mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
        if (!$link) {
            $messageArr['content'] =  'Could not connect to mysql ' . mysql_error() . PHP_EOL .
                            '. Please check connection parameters in app/bootstrap.php';
            $messageArr['type'] = "error";
            return;
        }
        if (!mysql_select_db(MYSQL_DB, $link)) {
            $messageArr['content'] = 'Could not select database ' . mysql_error() . PHP_EOL .
                            '. Please check connection parameters in app/bootstrap.php';
            $messageArr['type'] = "error";
            return;
        }

        $query = sprintf("INSERT INTO orders(amount, currency, customer_name, card_name, card_number, card_expiration, card_ccv, description, response, created_time)
                          VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', NOW())",
                          mysql_real_escape_string($order['amount']),
                          mysql_real_escape_string($order['currency']),
                          mysql_real_escape_string($order['customer_name']),
                          mysql_real_escape_string($order['card_name']),
                          mysql_real_escape_string($order['card_number']),
                          mysql_real_escape_string($order['card_expiration']),
                          mysql_real_escape_string($order['card_ccv']),
                          mysql_real_escape_string($order['message']),
                          mysql_real_escape_string($order['response']));
        $result = mysql_query($query);

        if (!$result) {
            $errorMsg = 'Error creating new order: ' . mysql_error();
            mysql_close();
            $messageArr['content'] = $errorMsg;
            return;
        }
        $orderId = mysql_insert_id();
        mysql_close();

        return $orderId;
    }
}