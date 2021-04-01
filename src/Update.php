<?php

namespace FederatedCanada;

/**
 * Transaction updates can be used to update previous transactions with
 * specific order information, such as a tracking number and shipping carrier.
 * 
 */
class Update extends Transaction {
  
  /* Optional */
  protected ?string $shipping_carrier;
  protected ?string $tracking_number;
  protected ?string $orderid;

  /**
   * Creates a new update for the payment gateway
   * 
   * @param int $transactionid
   * @param string $tracking_number (optional)
   * @param string $shipping_carrier (optional) ups/fedex/dhl/usps
   * @param string $orderid (optional)
   */
  function __construct(int $transactionid, string $tracking_number = null, string $shipping_carrier = null, string $orderid = null) {
    $this->type = 'update';
    $this->transactionid = $transactionid;
    $this->tracking_number = $tracking_number;
    $this->shipping_carrier = $shipping_carrier;
    $this->orderid = $orderid;
  }

}

?>