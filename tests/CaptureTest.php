<?php

namespace FederatedCanada\Tests;

use PHPUnit\Framework\TestCase;
use FederatedCanada\Authorization;
use FederatedCanada\Capture;

class CaptureTest extends TestCase {

  public function testCaptureSuccess() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 50.00);
    $auth->process('192.168.0.1');
    $capture = new Capture($auth->response->transactionid, 50.00);
    $capture->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $capture->process('192.168.0.1');
    $this->assertEquals(100, $capture->response->response_code);
  }
  
  public function testCaptureLessThanAmount() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 50.00);
    $auth->process('192.168.0.1');
    $capture = new Capture($auth->response->transactionid, 30.00);
    $capture->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $capture->process('192.168.0.1');
    $this->assertEquals(100, $capture->response->response_code);
  }
  
  public function testCaptureGreaterThanAmount() {
    $auth = new Authorization();
    $auth->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $auth->setCreditCardInfo('4111111111111111', '1010', '999', 50.00);
    $auth->process('192.168.0.1');
    $capture = new Capture($auth->response->transactionid, 80.00);
    $capture->setCredentials(FEDERATED_USERNAME, FEDERATED_PASSWORD);
    $capture->process('192.168.0.1');
    $this->assertEquals(100, $capture->response->response_code);
  }

}

?>