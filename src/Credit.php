<?php

namespace FederatedCanada;

/**
 * Credit (credit) - *Depreciated
 * Transaction credits apply a negative amount to the cardholder's card. In
 * most situations credits are disabled as transaction refunds should be used
 * instead.
 * 
 */
class Credit extends Payment {

  /**
   * Creates a new credit for the payment gateway
   */
  function __construct() {
    $this->type = 'credit';
    // if you stil wish to use this transaction type remove the line of code below
    die('Credit :: This class is Depreciated');
  }

}

?>