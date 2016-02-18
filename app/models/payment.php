<?php
/**
 * Created by PhpStorm.
 * User: linh.nguyen-ngoc
 * Date: 2/17/2016
 * Time: 9:05 AM
 */
namespace App\Models;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;
use Braintree;

class Payment {

    /**
     * Make payment
     * @param $order
     * @return array
     */
    public function makePayment($order) {
        $messageArr = array();
        $messageArr['response'] = '';

        //validation
        $this->validationData($order, $messageArr);

        if (isset($messageArr['type'])) {
            return $messageArr;
        }

        $cardNumber = $order['card_number'];
        $currency = $order['currency'];
        $cardType = $order['card_type'] = $this->detectCardType($cardNumber);

        if ($cardType) {
            if ($cardType == 'amex') { // if credit card type is AMEX
                if ($currency != 'USD') { // if currency is not USD
                    $messageArr['content'] = 'AMEX is possible to use only for USD';
                    $messageArr['type'] = 'error';
                } else { // use paypal
                    $this->makePaymentByPaypal($order, $messageArr);
                }
            } else {
                if (in_array($currency, array('USD', 'EUR', 'AUD'))) { // if currency is USD, EUR, or AUD, then use paypal
                    $this->makePaymentByPaypal($order, $messageArr);
                } else { // otherwise use Braintree
                    $this->makePaymentByBraintree($order, $messageArr);
                }
            }
        }

        return $messageArr;
    }

    /**
     * Make payment by paypal
     * @param $order
     * @param $messageArr
     */
    public function makePaymentByPaypal($order, &$messageArr) {
        try {
            $messageArr = array();

            $apiContext = new ApiContext(new OAuthTokenCredential(
                PAYPAL_CLIENT_ID,
                PAYPAL_CLIENT_SECRET
            ));

            $card = new CreditCard();
            $card->setType($order['card_type']);
            $card->setNumber($order['card_number']);
            $card->setExpireMonth($order['card_expire_month']);
            $card->setExpireYear($order['card_expire_year']);
            $card->setCvv2($order['card_ccv']);

            $fi = new FundingInstrument();
            $fi->setCreditCard($card);

            $payer = new Payer();
            $payer->setPaymentMethod('credit_card');
            $payer->setFundingInstruments(array($fi));

            $amount = new Amount();
            $amount->setCurrency($order['currency']);
            $amount->setTotal($order['amount']);

            $transaction = new Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription('This is the payment transaction description.');

            $payment = new \PayPal\Api\Payment();
            $payment->setIntent('sale');
            $payment->setPayer($payer);
            $payment->setTransactions(array($transaction));
            $result = $payment->create($apiContext);

            $messageArr['response'] = $result->toJSON();
            $messageArr['content'] = "You pay by Paypal.";
            $messageArr['type'] = 'success';
        } catch (PayPalConnectionException $ex) {
            $messageArr['content'] = $ex->getMessage().'(Paypal)';
            $messageArr['type'] = "error";
        } catch (\Exception $ex) {
            $messageArr['content'] = $ex->getMessage().'(Paypal)';
            $messageArr['type'] = "error";
        }
    }

    /**
     * Make payment by braintree
     * @param $order
     * @param $messageArr
     */
    public function makePaymentByBraintree($order, &$messageArr) {
        \Braintree_Configuration::environment('sandbox');
        \Braintree_Configuration::merchantId(BRAINTREE_MERCHANT_ID);
        \Braintree_Configuration::publicKey(BRAINTREE_PUBLIC_KEY);
        \Braintree_Configuration::privateKey(BRAINTREE_PRIVATE_KEY);

        $result = \Braintree_Transaction::sale(array(
            "amount"        => $order['amount'],
            "creditCard"    => array(
                "number"                => $order['card_number'],
                "cvv"                   => $order['card_ccv'],
                "expirationMonth"       => $order['card_expire_month'],
                "expirationYear"        => $order['card_expire_year']
            ),
            "options"       => array(
                "submitForSettlement"   => true
            )
        ));

        $message = '';
        if (!$result->success) {
            foreach (($result->errors->deepAll()) as $error) {
                $message.=$error->message.',';
            }
            $message = trim($message, ',').'(Braintree)';
        }

        $messageArr = array(
            'content'   => $result->success ? 'You pay by Braintree.' : $message,
            'type'      => $result->success ? 'success' : 'error',
            'response'  => json_encode(array(
                'id'        => $result->transaction ? $result->transaction->id : 0,
                'success'   => $result->success,
                'code'      => $result->transaction ? $result->transaction->processorResponseCode : 0,
                'error'     => $message
            ))
        );
    }

    /**
     * Detect Cart Type
     * @param $num
     * @return bool|string
     */
    public function detectCardType($num)
    {
        $re = array(
            "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex"       => "/^3[47][0-9]{13}$/",
            "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
        );

        if (preg_match($re['visa'],$num)) {
            return 'visa';
        } else if (preg_match($re['mastercard'],$num)) {
            return 'mastercard';
        } else if (preg_match($re['amex'],$num)) {
            return 'amex';
        } else if (preg_match($re['discover'],$num)) {
            return 'discover';
        } else {
            return false;
        }
    }

    /**
     * Validate Order Data
     * @param $order
     * @param $messageArr
     */
    public function validationData($order, &$messageArr) {
        // Amount Validation
        if (!isset($order['amount'])) {
            $messageArr['content'] = 'Payment amount not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['amount']=='') {
            $messageArr['content'] = 'Payment amount cannot be null.';
            $messageArr['type'] = "error";
            return;
        }

        // Currency Validation
        if (!isset($order['currency'])) {
            $messageArr['content'] = 'Payment currency not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['currency']=='') {
            $messageArr['content'] = 'Payment currency cannot be null.';
            $messageArr['type'] = "error";
            return;
        }
        if (!in_array($order['currency'], array('USD','EUR','THB','HKD','SGD','AUD'))) {
            $messageArr['content'] = 'Currency '.$order['currency'].' not supported by this gateway.';
            $messageArr['type'] = "error";
            return;
        }

        // Card Number Validation
        if (!isset($order['card_number'])) {
            $messageArr['content'] = 'Card number not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_number']=='') {
            $messageArr['content'] = 'Card number cannot be null.';
            $messageArr['type'] = "error";
            return;
        }
        if (!is_numeric($order['card_number'])) {
            $messageArr['content'] = 'Card number must be number.';
            $messageArr['type'] = "error";
            return;
        }

        // Card Expire Month Validation
        if (!isset($order['card_expire_month'])) {
            $messageArr['content'] = 'Card expire month not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_expire_month']=='') {
            $messageArr['content'] = 'Card expire month cannot be null.';
            $messageArr['type'] = "error";
            return;
        }
        if (!is_numeric($order['card_expire_month'])) {
            $messageArr['content'] = 'Card expire month must be number.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_expire_month'] < 1 || $order['card_expire_month'] > 12) {
            $messageArr['content'] = 'Card expire month must be from 1 to 12.';
            $messageArr['type'] = "error";
        }

        // Card Expire Year Validation
        if (!isset($order['card_expire_year'])) {
            $messageArr['content'] = 'Card expire year not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_expire_year']=='') {
            $messageArr['content'] = 'Card expire year cannot be null.';
            $messageArr['type'] = "error";
            return;
        }
        if (!is_numeric($order['card_expire_year'])) {
            $messageArr['content'] = 'Card expire year must be number.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_expire_year'] < 2016) {
            $messageArr['content'] = 'Card expire year must be from 2016.';
            $messageArr['type'] = "error";
        }

        // Card CCV Validation
        if (!isset($order['card_ccv'])) {
            $messageArr['content'] = 'Card ccv not set.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_ccv']=='') {
            $messageArr['content'] = 'Card ccv cannot be null.';
            $messageArr['type'] = "error";
            return;
        }
        if (!is_numeric($order['card_ccv'])) {
            $messageArr['content'] = 'Card ccv must be number.';
            $messageArr['type'] = "error";
            return;
        }
        if ($order['card_ccv'] < 1) {
            $messageArr['content'] = 'Card ccv must be integer.';
            $messageArr['type'] = "error";
            return;
        }
    }
}