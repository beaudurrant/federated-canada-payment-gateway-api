<?php

namespace FederatedCanada;

/**
 * Transaction voids will cancel an existing sale or captured authorization.
 * In addition, non-captured authorizations can be voided to prevent any
 * future capture. Voids can only occur if the transaction has not been
 * settled.
 * 
 */
class Voided extends Transaction {

  /**
   * Creates a new void for the payment gateway
   * 
   * @param int $transactionid
   */
  function __construct(int $transactionid) {
    $this->type = 'void';
    $this->transactionid = $transactionid;
  }

}

?>