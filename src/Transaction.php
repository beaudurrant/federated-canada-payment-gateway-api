<?php

namespace FederatedCanada;

/**
 * 1. The customer sends their payment information to the merchant's web site.
 * 2. The merchant's web site posts the payment data to the Payment Gateway.
 * 3. The Payment Gateway responds immediately with the results of the transactions.
 * 4. The merchant's web site displays the appropriate message to the customer.
 * 
 */
abstract class Transaction {
  
  /* Type of Transaction */
  protected string $type;
  
  /* ID of Transaction with Gateway */
  protected int $transactionid;
  
  /* Gateway Credentials */
  private string $username;
  private string $password;
  
  /* URL to post data and get responses */
  private string $URL = 'https://secure.federatedgateway.com/api/transact.php';
  private string $postURL;
  private string $queryString = '';
  
  private string $ip;
  
  /* Properties not to send (GET) to Gateway */
  private array $privateProperties = array (
    'privateProperties',
    'URL',
    'postURL',
    'queryString',
    'response' 
  );
  
  /* Response Object & response from the transaction */
  public Response $response;

  function __construct() {}

  /**
   * Sets the username and password to connect to payment gateway
   * 
   * @param string $username
   * @param string $password
   */
  public function setCredentials (string $username, string $password) : void {
    $this->username = $username;
    $this->password = $password;
  }
  
  /**
   * Creates the querystring variables to the Payment Gateway
   * Saves and parses the response
   * 
   * @param string $ip
   * 
   * @return boolean "if the proccess was successful"
   */
  public function process(string $ip) : bool {
    $this->ip = $ip;
    // create querystring by looping through properties with key values
    foreach($this as $key => $value) {
      if(! in_array($key, $this->privateProperties)) {
        if($value) $this->queryString .= $key . '=' . urlencode($value) . '&';
      }
    }
    $this->postURL = $this->URL . '?' . $this->queryString;
    $this->response = new Response($this->postURL);
    if($this->response->response == 1) return true;
    return false;
  }

}

?>