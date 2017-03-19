<?php
/* Template Name: Webhook Test */

use PayPal\Api\WebhookEvent;


$requestBody = file_get_contents('php://input');
//error_log(print_r($requestBody, true));
$event = new WebhookEvent();
$event->fromJson($requestBody);
error_log($event->getId());
$type = $event->getEventType();
error_log($type);
// error_log(print_r($types, true));
// foreach ($types as $type) {
//   error_log(print_r($type, true));
// }
wp_send_json( Array('status' => 'OK', 'request' => $event->getEventType ) );
