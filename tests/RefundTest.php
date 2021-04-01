<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Sale;
use FederatedCanada\Refund;

class RefundTest extends TestCase {

  public function testRefundSuccess() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 100.00);
    $sale->process('192.168.0.1');
    $refund = new Refund($sale->response->transactionid, 100.00);
    $refund->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $refund->process('192.168.0.1');
    $this->assertEquals(100, $refund->response->response_code);
  }
  
  public function testRefundLessThanAmount() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 100.00);
    $sale->process('192.168.0.1');
    $refund = new Refund($sale->response->transactionid, 80.00);
    $refund->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $refund->process('192.168.0.1');
    $this->assertEquals(100, $refund->response->response_code);
  }
  
  public function testRefundGreaterThanAmount() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 100.00);
    $sale->process('192.168.0.1');
    $refund = new Refund($sale->response->transactionid, 120.00);
    $refund->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $refund->process('192.168.0.1');
    $this->assertEquals(300, $refund->response->response_code);
  }

}

?>