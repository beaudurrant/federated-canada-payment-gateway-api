<?php

namespace FederatedCanada;

/**
 * Transaction captures flag existing authorizations for settlement. Only
 * authorizations can be captured. Captures can be submitted for an amount
 * equal to or less than the original authorization.
 * 
 */
class Capture extends Transaction {
  
  /* Required */
  protected float $amount;
  
  /* Optional */
  protected ?string $shipping_carrier;
  protected ?string $tracking_number;
  protected ?string $orderid;

  /**
   * Creates a new capture for the payment gateway
   * 
   * @param int $transactionid
   * @param float $amount
   * @param string $tracking_number (optional)
   * @param string $shipping_carrier (optional) ups/fedex/dhl/usps
   * @param string $orderid (optional)
   */
  function __construct(int $transactionid, float $amount, string $tracking_number = null, string $shipping_carrier = null, string $orderid = null) {
    $this->type = 'capture';
    $this->transactionid = $transactionid;
    $this->amount = $amount;
    $this->tracking_number = $tracking_number;
    $this->shipping_carrier = $shipping_carrier;
    $this->orderid = $orderid;
  }

}

?>