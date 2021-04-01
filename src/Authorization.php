<?php

namespace FederatedCanada;

/**
 * Authorization (auth)
 * Transaction authorizations are authorized immediately but are not flagged
 * for settlement. These transactions must be flagged for settlement using
 * the capture transaction type. Authorizations typically remain activate for
 * three to seven business days.
 * 
 */
class Authorization extends Payment {

  /**
   * Creates a new authorization for the payment gateway
   */
  function __construct() {
    $this->type = 'auth';
  }

}

?>