<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Authorization;
use FederatedCanada\Sale;
use FederatedCanada\Voided;

class VoidTest extends TestCase {

  public function testVoidSaleSuccess() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo('4111111111111111', '1010', '999', 100.00);
    $sale->process('192.168.0.1');
    $void = new Voided($sale->response->transactionid);
    $void->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $void->process('192.168.0.1');
    $this->assertEquals(100, $void->response->response_code);
  }
  
  public function testVoidAuthorizationSuccess() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 100.00);
    $auth->process('192.168.0.1');
    $void = new Voided($auth->response->transactionid);
    $void->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $void->process('192.168.0.1');
    $this->assertEquals(100, $void->response->response_code);
  }

}

?>