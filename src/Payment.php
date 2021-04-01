<?php

namespace FederatedCanada;

/**
 * Sale (sale)
 * Transaction sales are submitted and immediately flagged for settlement.
 * These transactions will automatically be settled.
 * 
 * Authorization (auth)
 * Transaction authorizations are authorized immediately but are not flagged
 * for settlement. These transactions must be flagged for settlement using
 * the capture transaction type. Authorizations typically remain activate for
 * three to seven business days.
 * 
 * Credit (credit) - *Depreciated
 * Transaction credits apply a negative amount to the cardholder's card. In
 * most situations credits are disabled as transaction refunds should be used
 * instead.
 * 
 */

abstract class Payment extends Transaction {
  
  /* CC info required */
  protected string $ccnumber;                // Credit card number
  protected string $ccexp;                   // Credit card expiration (ie. 0711 = 7/2011)
  protected string $cvv;                     // Card security code
  
  /* Check info required */
  protected string $checkname;               // Customers name on ACH account
  protected string $checkaba;                // The customer's bank routing number
  protected string $checkaccount;            // The customer's bank account number
  protected string $account_holder_type;     // The customer's type of ACH account (business/personal)
  protected string $account_type;            // The customer's ACH account entity (checking/savings)
  protected ?string $sec_code;               // ACH Standard Entry Class codes (PPD/WEB/TEL/CCD)
  protected float $amount;                   // Total amount to be charged (i.e. 10.00)
  protected string $payment;                 // Set Payment Type to ACH or Credit Card
  protected ?int $processor_id;              // If using Multiple MIDs, route to this processor.
  protected ?int $dup_seconds;               // Disable Duplicate checking (in seconds)
  protected ?string $descriptor;             // Set payment descriptor
  protected ?string $descriptor_phone;       // Set payment descriptor phone
  protected ?string $validation;             // Specify which Validation processors to use
  protected ?string $product_sku_number;     // Associate API call with Recurring SKU
  protected ?string $orderdescription;       // Order description
  protected ?string $orderid;                // Order Id
  protected float $tax;                      // Total tax amount (x.xx)
  protected float $shipping;                 // Total shipping amount (x.xx)
  protected ?string $ponumber;               // Original Purchase Order
  protected ?string $firstname;              // Cardholder's first name
  protected ?string $lastname;               // Cardholder's last name
  protected ?string $company;                // Cardholder's company
  protected ?string $address1;               // Card billing address
  protected ?string $address2;               // Card billing address - line 2
  protected ?string $city;                   // Card billing city
  protected ?string $state;                  // Card billing state (2 character abbrev.)
  protected ?string $zip;                    // Card billing zip code
  protected ?string $country;                // Card billing country (ie. US - ISO-3166)
  protected ?string $phone;                  // Billing phone number
  protected ?string $fax;                    // Billing fax number
  protected ?string $email;                  // Billing email address
  protected ?string $shipping_firstname;     // Shipping first name
  protected ?string $shipping_lastname;      // Shipping last name
  protected ?string $shipping_company;       // Shipping company
  protected ?string $shipping_address1;      // Shipping address
  protected ?string $shipping_address2;      // Shipping address - line 2
  protected ?string $shipping_city;          // Shipping city
  protected ?string $shipping_state;         // Shipping state
  protected ?string $shipping_zip;           // Shipping zip code
  protected ?string $shipping_country;       // Shipping country (ie. US)
  protected ?string $shipping_email;         // Shipping email address
  
  function __construct() {}

  /**
   * Set credit card info (setCreditCardInfo or setCheckInfo must be called to process)
   * 
   * @param string $ccnumber
   * @param string $ccexp
   * @param string $cvv
   * @param string $amount
   * @param string tax (optional)
   * @param string shipping (optional)
   */
  public function setCreditCardInfo(string $ccnumber, string $ccexp, string $cvv, string $amount, float $tax = 0.00, float $shipping = 0.00) : void {
    $this->payment = 'creditcard';
    $this->ccnumber = $ccnumber;
    $this->ccexp = $ccexp;
    $this->cvv = $cvv;
    $this->amount = $amount;
    $this->tax = $tax;
    $this->shipping = $shipping;
  }

  /**
   * Set check info (setCreditCardInfo or setCheckInfo must be called to process)
   * 
   * @param string $checkname
   * @param string $checkaba
   * @param string $checkaccount
   * @param string $account_holder_type
   * @param string $account_type
   * @param float $amount
   * @param float tax (optional)
   * @param float shipping (optional)
   * @param string $sec_code (optional)
   */
  public function setCheckInfo(string $checkname, string $checkaba, string $checkaccount, string $account_holder_type, string $account_type, float $amount, float $tax = 0.00, float $shipping = 0.00, string $sec_code = null) : void {
    $this->payment = 'check';
    $this->checkname = $checkname;
    $this->checkaba = $checkaba;
    $this->checkaccount = $checkaccount;
    $this->account_holder_type = $account_holder_type;
    $this->account_type = $account_type;
    $this->amount = $amount;
    $this->tax = $tax;
    $this->shipping = $shipping;
    $this->sec_code = $sec_code;
  }

  /**
   * Set processor info, for supported processors
   * 
   * @param int $processor_id (optional)
   * @param int $dup_seconds (optional)
   * @param string $descriptor (optional)
   * @param string $descriptor_phone (optional)
   * @param string $validation (optional)
   */
  public function setProcessorInfo(int $processor_id = null, int $dup_seconds = null, string $descriptor = null, string $descriptor_phone = null, string $validation = null) : void {
    $this->processor_id = $processor_id;
    $this->dup_seconds = $dup_seconds;
    $this->descriptor = $descriptor;
    $this->descriptor_phone = $descriptor_phone;
    $this->validation = $validation;
  }

  /**
   * Set product info
   * 
   * @param string $product_sku_number (optional)
   */
  public function setProductInfo(string $product_sku_number = null) : void {
    $this->product_sku_number = $product_sku_number;
  }

  /**
   * Set order info
   * 
   * @param string $orderid (optional)
   * @param string $orderdescription (optional)
   * @param string $ponumber (optional)
   */
  public function setOrderInfo(string $orderid = null, string $orderdescription = null, string $ponumber = null) : void {
    $this->orderid = $orderid;
    $this->orderdescription = $orderdescription;
    $this->ponumber = $ponumber;
  }

  /**
   * Set billing info
   * 
   * @param string $firstname (optional)
   * @param string $lastname (optional)
   * @param string $company (optional)
   * @param string $address1 (optional)
   * @param string $address2 (optional)
   * @param string $city (optional)
   * @param string $state (optional)
   * @param string $zip (optional)
   * @param string $country (optional)
   * @param string $phone (optional)
   * @param string $fax (optional)
   * @param string $email (optional)
   */
  public function setBillingInfo(string $firstname = null, string $lastname = null, string $company = null, string $address1 = null, string $address2 = null, string $city = null, string $state = null, string $zip = null, string $country = null, string $phone = null, string $fax = null, string $email = null) : void {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->company = $company;
    $this->address1 = $address1;
    $this->address2 = $address2;
    $this->city = $city;
    $this->state = $state;
    $this->zip = $zip;
    $this->country = $country;
    $this->phone = $phone;
    $this->fax = $fax;
    $this->email = $email;
  }

  /**
   * Set shipping info
   * 
   * @param string $shipping_firstname (optional)
   * @param string $shipping_lastname (optional)
   * @param string $shipping_company (optional)
   * @param string $shipping_address1 (optional)
   * @param string $shipping_address2 (optional)
   * @param string $shipping_city (optional)
   * @param string $shipping_state (optional)
   * @param string $shipping_zip (optional)
   * @param string $shipping_country  (optional)
   * @param string $shipping_email (optional)
   */
  public function setShippingInfo(string $shipping_firstname = null, string $shipping_lastname = null, string $shipping_company = null, string $shipping_address1 = null, string $shipping_address2 = null, string $shipping_city = null, string $shipping_state = null, string $shipping_zip = null, string $shipping_country = null, string $shipping_email = null) : void {
    $this->shipping_firstname = $shipping_firstname;
    $this->shipping_lastname = $shipping_lastname;
    $this->shipping_company = $shipping_company;
    $this->shipping_address1 = $shipping_address1;
    $this->shipping_address2 = $shipping_address2;
    $this->shipping_city = $shipping_city;
    $this->shipping_state = $shipping_state;
    $this->shipping_zip = $shipping_zip;
    $this->shipping_country = $shipping_country;
    $this->shipping_email = $shipping_email;
  }

}
?>