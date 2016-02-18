<?php
/**
 * Created by PhpStorm.
 * User: linh.nguyen-ngoc
 * Date: 2/17/2016
 * Time: 2:39 PM
 */

namespace Test;

use PHPUnit_Framework_TestCase;
use App\Models\Payment;

class PaymentTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->payment = new Payment();
    }

    /**
     * if credit card type is AMEX, then use Paypal.
     */
    /*public function testAmexPaypalPayment() {
        $param['amount'] = 10;
        $param['currency'] = 'USD';
        $param['card_number'] = '378282246310005';
        $param['card_expire_month'] = '11';
        $param['card_expire_year'] = '2019';
        $param['card_ccv'] = '1234';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('success', $result['type']);
        $this->assertEquals('You pay by Paypal.', $result['content']);
    }*/

    /**
     * if currency is USD, EUR, or AUD, then use Paypal.
     */
    public function testCurrencyPaypalPayment() {
        $param['amount'] = 10;
        $param['currency'] = 'USD';
        $param['card_number'] = '4417119669820331';
        $param['card_expire_month'] = '11';
        $param['card_expire_year'] = '2019';
        $param['card_ccv'] = '012';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('success', $result['type']);
        $this->assertEquals('You pay by Paypal.', $result['content']);
    }

    /**
     * Braintree payment is used when credit card type is not AMEX and currency is not in USD, EUR, or AUD
     */
    public function testBraintreePayment() {
        $param['amount'] = 10;
        $param['currency'] = 'SGD';
        $param['card_number'] = '4500600000000061';
        $param['card_expire_month'] = '11';
        $param['card_expire_year'] = '2019';
        $param['card_ccv'] = '012';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('success', $result['type']);
        $this->assertEquals('You pay by Braintree.', $result['content']);
    }

    /**
     * if currency is not USD and credit card is AMEX, return error message, that AMEX is possible to use only for USD
     */
    public function testAmexCurrencyPaymentFail() {
        $param['amount'] = 10;
        $param['currency'] = 'SGD';
        $param['card_number'] = '378282246310005';
        $param['card_expire_month'] = '11';
        $param['card_expire_year'] = '2019';
        $param['card_ccv'] = '1234';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('AMEX is possible to use only for USD', $result['content']);
    }

}