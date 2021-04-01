<?php

namespace FederatedCanada;

/**
 * Transaction refunds will reverse a previously settled transaction. If the
 * transaction has not been settled, it must be voided instead of refunded.
 * 
 */
class Refund extends Transaction {
  
  /* Required */
  protected float $amount;

  /**
   * Creates a new refund for the payment gateway
   * 
   * @param int $transactionid
   * @param string $amount
   */
  function __construct(int $transactionid, float $amount) {
    $this->type = 'refund';
    $this->transactionid = $transactionid;
    $this->amount = $amount;
  }

}

?>