<?php

namespace FederatedCanada;

/**
 * Sale (sale)
 * Transaction sales are submitted and immediately flagged for settlement.
 * These transactions will automatically be settled.
 * 
 */
class Sale extends Payment {

  /**
   * Creates a new sale for the payment gateway
   */
  function __construct() {
    $this->type = 'sale';
  }

}

?>