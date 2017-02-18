<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
// require __DIR__  . '/vendor/autoload.php';
// Use below for direct download installation
require __DIR__  . '/PayPal-PHP-SDK/autoload.php';

// After Step 1
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'Af7av5c-BGgs6lVh49qd-2fqXz93USKji2SlLOSkVy5g1tNtIaGe_z37eFssD56FjRyMLstG_qfA7jF1', // ClientID
         'EJAklAjAb5lUBWbRJG_1_wuOmoY7eGi2QDYUrvlXvNIMdLg-k1zJnW0hdgIaK8KhBDOutx1tVYvQWKAO' // ClientSecret
    )
);

// After Step 2
$creditCard = new \PayPal\Api\CreditCard();
$creditCard->setType("visa")
    ->setNumber("4417119669820331")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
    ->setCvv2("012")
    ->setFirstName("Joe")
    ->setLastName("Shopper");

// After Step 3
try {
    $creditCard->create($apiContext);
    echo $creditCard;
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception. 
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}


