<?php

namespace FederatedCanada;

/**
 * Holds the response information from a Transaction
 * 
 */
class Response {
  
  /* postURL request was made to */
  public string $postURL;
  /* Original Response String from transaction */
  public string $responseString;
  /*
   * Response Text
   * 1 = Transaction Approved
   * 2 = Transaction Declined
   * 3 = Error in transaction data or system error
   */
  public int $response;
  /* Textual Response */
  public string $responsetext;
  /* Transaction Authorization Code */
  public string $authcode;
  /* Payment Gateway Transaction id */
  public int $transactionid;
  /* AVS Response Code & Text */
  public string $avsresponse;
  public string $avsresponsetext;
  /* CVV Response Code & Text */
  public string $cvvresponse;
  public string $cvvresponsetext;
  /* The Original Order id Passed in the Transaction Request. */
  public string $orderid;
  /* Numeric Mapping of Processor Responses & Text */
  public int $response_code;
  public string $response_text;

  /**
   * Creates a new response for the payment gateway, parses the string
   * 
   * @param string $postURL
   * 
   */
  function __construct(string $postURL) {
    // save the original response and request
    $this->postURL = $postURL;
    $this->responseString = file_get_contents($this->postURL);
    // split the response into a key => value array
    $responseItems = explode('&', $this->responseString);
    $valueArray = array ();
    foreach($responseItems as $item) {
      list ($key, $value) = explode('=', $item);
      $value = urldecode($value);
      $valueArray[$key] = $value;
    }
    // assign from the value array
    $this->response = (int) $valueArray['response'];
    $this->responsetext = $valueArray['responsetext'];
    $this->authcode = $valueArray['authcode'];
    $this->transactionid = (int) $valueArray['transactionid'];
    $this->avsresponse = $valueArray['avsresponse'];
    $this->setAvsResponseText();
    $this->cvvresponse = $valueArray['cvvresponse'];
    $this->setCvvResponseText();
    $this->orderid = $valueArray['orderid'];
    $this->response_code = (int) $valueArray['response_code'];
    $this->setDetailedResponseCodeText();
  }

  /**
   * Takes the code response and maps it to text response
   */
  private function setDetailedResponseCodeText() : void {
    $text = '';
    switch($this->response_code) {
      case 100:
        $text = 'Transaction was Approved';
        break;
      case 200:
        $text = 'Transaction was Declined by Processor';
        break;
      case 201:
        $text = 'Do Not Honor';
        break;
      case 202:
        $text = 'Insufficient Funds';
        break;
      case 203:
        $text = 'Over Limit';
        break;
      case 204:
        $text = 'Transaction Not Allowed';
        break;
      case 220:
        $text = 'Incorrect Payment Data';
        break;
      case 221:
        $text = 'No Such Card Issuer';
        break;
      case 222:
        $text = 'No Card Number on File with Issuer';
        break;
      case 223:
        $text = 'Expired Card';
        break;
      case 224:
        $text = 'Invalid Expiration Date';
        break;
      case 225:
        $text = 'Invalid Card Security Code';
        break;
      case 240:
        $text = 'Call Card Issuer for Further Information';
        break;
      case 250:
        $text = 'Pick Up Card';
        break;
      case 251:
        $text = 'Lost Card';
        break;
      case 252:
        $text = 'Stolen Card';
        break;
      case 253:
        $text = 'Fraudulant Card';
        break;
      case 260:
        $text = 'Declined with Further Instructions Avaliable (see response text)';
        break;
      case 261:
        $text = 'Declined - Stop all Recurring Payments';
        break;
      case 262:
        $text = 'Declined - Stop the Recurring Program';
        break;
      case 263:
        $text = 'Declined - Update Cardholder Data Avaliable';
        break;
      case 264:
        $text = 'Declined - Retry in a few days';
        break;
      case 300:
        $text = 'Transaction was Rejected by Gateway';
        break;
      case 400:
        $text = 'Transaction Error Returned by Processor';
        break;
      case 410:
        $text = 'Invalid Merchant Configuration';
        break;
      case 411:
        $text = 'Merchant Account is Inactive';
        break;
      case 420:
        $text = 'Communication Error';
        break;
      case 421:
        $text = 'Communication Error with Processor';
        break;
      case 430:
        $text = 'Duplicate Transaction at Processor';
        break;
      case 440:
        $text = 'Processor Format Error';
        break;
      case 441:
        $text = 'Invalid Transaction Information';
        break;
      case 460:
        $text = 'Processor Feature not Avaliable';
        break;
      case 461:
        $text = 'Unsupported Card Type';
        break;
    }
    $this->response_text = $text;
  }

  /**
   * Takes the CVV code response and maps it to text response
   */
  private function setCvvResponseText() : void {
    $text = '';
    switch($this->cvvresponse) {
      case 'M':
        $text = 'CVV2/CVC2 Match';
        break;
      case 'N':
        $text = 'CVV2/CVC2 No Match';
        break;
      case 'P':
        $text = 'Not Processed';
        break;
      case 'S':
        $text = 'Merchant has indicated that CVV2/CVC2 is not present on card';
        break;
      case 'U':
        $text = 'Issuer is not certified and/or has not provided Visa encryption keys';
        break;
    }
    $this->cvvresponsetext = $text;
  }

  /**
   * Takes the AVS code response and maps it to text response
   */
  private function setAvsResponseText() : void {
    $text = '';
    switch($this->avsresponse) {
      case 'X':
        $text = 'Exact match, 9-character numeric ZIP';
        break;
      case 'Y':
      case 'D':
      case 'M':
        $text = 'Exact match, 5-character numeric ZIP';
        break;
      case 'A':
      case 'B':
        $text = 'Address match only';
        break;
      case 'W':
        $text = '9-character numeric ZIP match only';
        break;
      case 'Z':
      case 'P':
      case 'L':
        $text = '5-character Zip match only';
        break;
      case 'N':
      case 'C':
        $text = 'No address or ZIP match';
        break;
      case 'U':
        $text = 'Address unavailable';
        break;
      case 'G':
      case 'I':
        $text = 'Non-U.S. Issuer does not participate';
        break;
      case 'R':
        $text = 'Issuer system unavailable';
        break;
      case 'E':
        $text = 'Not a mail/phone order';
        break;
      case 'S':
        $text = 'Service not supported';
        break;
      case '0':
      case 'O':
      case 'B':
        $text = 'AVS Not Available';
        break;
    }
    $this->avsresponsetext = $text;
  }

}

?>