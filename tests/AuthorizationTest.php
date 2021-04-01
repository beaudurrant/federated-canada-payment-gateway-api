<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Authorization;

class AuthorizationTest extends TestCase {

  public function testAuthorizationSuccess() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 10.00);
    $auth->process('192.168.0.1');
    $this->assertEquals(100, $auth->response->response_code);
  }
  
  public function testInvalidCVV() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '989', 10.00);
    $auth->process('192.168.0.1');
    $this->assertEquals('CVV2/CVC2 No Match', $auth->response->cvvresponsetext);
  }
  
  public function testInvalidCCNumber() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111110', '1010', '999', 10.00);
    $auth->process('192.168.0.1');
    $this->assertEquals(300, $auth->response->response_code);
  }
  
  public function testInvalidCCExpiry() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1000', '999', 10.00);
    $auth->process('192.168.0.1');
    $this->assertEquals(100, $auth->response->response_code);
  }
  
  public function testInvalidAddress() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 10.00);
    $auth->setBillingInfo('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'zip', 'country', 'phone', 'fax', 'email');
    $auth->process('192.168.0.1');
    $this->assertEquals('No address or ZIP match', $auth->response->avsresponsetext);
  }
  
  public function testValidAddress() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 10.00);
    $auth->setBillingInfo('firstname', 'lastname', 'company', '888', 'address2', 'city', 'state', '77777', 'country', 'phone', 'fax', 'email');
    $auth->setShippingInfo('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'zip', 'country', 'email');
    $auth->process('192.168.0.1');
    $this->assertEquals(100, $auth->response->response_code);
    $this->assertEquals('Exact match, 5-character numeric ZIP', $auth->response->avsresponsetext);
  }

}

?>