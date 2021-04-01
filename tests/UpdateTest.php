<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Sale;
use FederatedCanada\Update;

class UpdateTest extends TestCase {

  public function testUpdateSuccess() {
    $sale = new Sale();
    $sale->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $sale->setCreditCardInfo(4111111111111111, 1010, 999, 10.00);
    $sale->setOrderInfo('12345', 'Test Sale');
    $sale->process('192.168.0.1');
    $update = new Update($sale->response->transactionid, null, 'fedex', null);
    $update->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $update->process('192.168.0.1');
    $this->assertEquals(100, $update->response->response_code);
    $update = new Update($sale->response->transactionid, null, null, '54321');
    $update->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $update->process('192.168.0.1');
    $this->assertEquals(100, $update->response->response_code);
  }

}

?>