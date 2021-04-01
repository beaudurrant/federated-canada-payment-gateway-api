<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Sale;

class SaleTest extends TestCase {

  public function testSaleSuccess() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 10.00);
    $sale->process('192.168.0.1');
    $this->assertEquals(100, $sale->response->response_code);
  }
  
  public function testInvalidCVV() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 989, 10.00);
    $sale->process('192.168.0.1');
    $this->assertEquals('CVV2/CVC2 No Match', $sale->response->cvvresponsetext);
  }
  
  public function testInvalidCCNumber() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo('4111111111111110', 1010, 999, 10.00);
    $sale->process('192.168.0.1');
    $this->assertEquals(300, $sale->response->response_code);
  }
  
  public function testInvalidCCExpiry() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1000, 999, 10.00);
    $sale->process('192.168.0.1');
    $this->assertEquals(100, $sale->response->response_code);
  }
  
  public function testInvalidAddress() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 10.00);
    $sale->setBillingInfo('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'zip', 'country', 'phone', 'fax', 'email');
    $sale->process('192.168.0.1');
    $this->assertEquals('No address or ZIP match', $sale->response->avsresponsetext);
  }
  
  public function testValidAddress() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 10.00);
    $sale->setBillingInfo('firstname', 'lastname', 'company', '888', 'address2', 'city', 'state', '77777', 'country', 'phone', 'fax', 'email');
    $sale->setShippingInfo('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'zip', 'country', 'email');
    $sale->process('192.168.0.1');
    $this->assertEquals(100, $sale->response->response_code);
    $this->assertEquals('Exact match, 5-character numeric ZIP', $sale->response->avsresponsetext);
  }

}

?>