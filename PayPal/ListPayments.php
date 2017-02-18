<?php

use PayPal\Rest\ApiContext;
use PayPal\Api\Payment;


class ListPayments {
  
  // #GetPaymentList
  // This sample code demonstrate how you can
  // retrieve a list of all Payment resources
  // you've created using the Payments API.
  // Note various query parameters that you can
  // use to filter, and paginate through the
  // payments list.
  // API used: GET /v1/payments/payments
  
//   require 'CreatePayment.php';
  
  public static function list_payments($start_index = 0, $count = 10, $apiContext = false) {
    
    if (!$apiContext) {
      global $clientId, $clientSecret;
      $apiContext = getApiContext($clientId, $clientSecret);
    }
    
    // ### Retrieve payment
    // Retrieve the PaymentHistory object by calling the
    // static `get` method on the Payment class,
    // and pass a Map object that contains
    // query parameters for paginations and filtering.
    // Refer the method doc for valid values for keys
    // (See bootstrap.php for more on `ApiContext`)
    try {
      $params = array('count' => $count, 'start_index' => $start_index);
    
      $payments = Payment::all($params, $apiContext);
    } catch (Exception $ex) {
      // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//       ResultPrinter::printError("List Payments", "Payment", null, $params, $ex);
      exit(1);
    }
    
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
//     ResultPrinter::printResult("List Payments", "Payment", null, $params, $payments);
    return $payments;
  }
  
}