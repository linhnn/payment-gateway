<?php
/**
 * Created by PhpStorm.
 * User: linh.nguyen-ngoc
 * Date: 2/18/2016
 * Time: 11:18 AM
 */
namespace Test;

use PHPUnit_Framework_TestCase;
use App\Models\Payment;

class ValidationTest extends  PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->payment = new Payment();

        $param = array();
        $param['amount'] = '10';
        $param['currency'] = 'USD';
        $param['card_number'] = '4417119669820331';
        $param['card_expire_month'] = '11';
        $param['card_expire_year'] = '2019';
        $param['card_ccv'] = '012';
        $this->param = $param;
    }

    public function testAmountNotSet() {
        $param = $this->param;
        unset($param['amount']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Payment amount not set.', $result['content']);
    }

    public function testAmountNull() {
        $param = $this->param;
        $param['amount'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Payment amount cannot be null.', $result['content']);
    }

    public function testCurrenyNotSet() {
        $param = $this->param;
        unset($param['currency']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Payment currency not set.', $result['content']);
    }

    public function testCurrencyNull() {
        $param = $this->param;
        $param['currency'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Payment currency cannot be null.', $result['content']);
    }

    public function testCurrencyNotSupport() {
        $param = $this->param;
        $param['currency'] = 'VND';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Currency VND not supported by this gateway.', $result['content']);
    }

    public function testCardNumberNotSet() {
        $param = $this->param;
        unset($param['card_number']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card number not set.', $result['content']);
    }

    public function testCardNumberNull() {
        $param = $this->param;
        $param['card_number'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card number cannot be null.', $result['content']);
    }

    public function testCardNumberNotNumber() {
        $param = $this->param;
        $param['card_number'] = 'abcdef';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card number must be number.', $result['content']);
    }

    public function testCardExpireMonthNotSet() {
        $param = $this->param;
        unset($param['card_expire_month']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire month not set.', $result['content']);
    }

    public function testCardExpireMonthNull() {
        $param = $this->param;
        $param['card_expire_month'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire month cannot be null.', $result['content']);
    }

    public function testCardExpireMonthNotNumber() {
        $param = $this->param;
        $param['card_expire_month'] = 'abcdef';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire month must be number.', $result['content']);
    }

    public function testCardExpireMonthNotValid() {
        $param = $this->param;
        $param['card_expire_month'] = 20;
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire month must be from 1 to 12.', $result['content']);
    }

    public function testCardExpireYearNotSet() {
        $param = $this->param;
        unset($param['card_expire_year']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire year not set.', $result['content']);
    }

    public function testCardExpireYearNull() {
        $param = $this->param;
        $param['card_expire_year'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire year cannot be null.', $result['content']);
    }

    public function testCardExpireYearNotNumber() {
        $param = $this->param;
        $param['card_expire_year'] = 'abcdef';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire year must be number.', $result['content']);
    }

    public function testCardExpireYearNotValid() {
        $param = $this->param;
        $param['card_expire_year'] = 2015;
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card expire year must be from 2016.', $result['content']);
    }

    public function testCardCcvNotSet() {
        $param = $this->param;
        unset($param['card_ccv']);
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card ccv not set.', $result['content']);
    }

    public function testCardCcvNull() {
        $param = $this->param;
        $param['card_ccv'] = '';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card ccv cannot be null.', $result['content']);
    }

    public function testCardCccvNotNumber() {
        $param = $this->param;
        $param['card_ccv'] = 'abcdef';
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card ccv must be number.', $result['content']);
    }

    public function testCardCcvNotValid() {
        $param = $this->param;
        $param['card_ccv'] = -1;
        $result = $this->payment->makePayment($param);

        $this->assertEquals('error', $result['type']);
        $this->assertEquals('Card ccv must be integer.', $result['content']);
    }
}