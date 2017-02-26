<?php

require 'PayPal-PHP-SDK/autoload.php';
require_once 'functions.php';
require_once 'ListPayments.php';
require_once 'Billing.php';

class PayPalPlugin {
  
  public static function add_menu_item() {
    add_menu_page ( "PayPal XXX", "PayPal XXX", "manage_options", "paypal-xxx" );
    //add_menu_page ( "PayPal Transactions", "PayPal Transactions", "manage_options", "paypal-transactions", "PayPalPlugin::transactions_page", null, 99 );
    add_submenu_page( "paypal-xxx", "PayPal Transactions", "PayPal Transactions", "manage_options", "paypal-transactions", "PayPalPlugin::transactions_page", null, 99 );
    add_submenu_page( "paypal-xxx", "PayPal Billing", "PayPal Billing", "manage_options", "paypal-billing", "Billing::billing_page", null, 99 );
    }
  
  private static function getCreditCardInfo($fundingInstruments = Array()) {
    foreach ($fundingInstruments as $fi) {
      if ($fi->getCreditCard()) return $fi->getCreditCard();
    }
    return null;
  }
  
  private static function getSaleInfo($relatedResources = Array()) {
    foreach ($relatedResources as $rr) {
      if ($rr->getSale()) return $rr->getSale();
    }
    return null;
  }
  
  private static function displayTransaction($tx) {
    $amount = $tx->getAmount();
    $sale = PayPalPlugin::getSaleInfo($tx->getRelatedResources());
    ?>
    <!-- <td><?= $tx ?></td> -->
    <td><?= $amount->getTotal() ?></td>
    <td><?= $amount->getCurrency() ?></td>
    <!-- <td><?= $tx->getReferenceId() ?></td> -->
    <td><?= $sale->getState() ?></td>
    <td><?= $sale->getId() ?></td>
    <?php
  }

  public static function transactions_page() {
    global $_GET;
    $start = key_exists('start', $_GET) ? intval($_GET['start']) : 0;
    $limit = key_exists('limit', $_GET) ? intval($_GET['limit']) : 10;
//     $prevId = key_exists('prev_id', $_GET) ? $_GET['prev_id'] : null;
//     $payments = ListPayments::list_payments(key_exists('next_id', $_GET) ? $_GET['next_id'] : null, 10);
    $payments = ListPayments::list_payments($start, $limit);
    $count = $payments->getCount();
    $nextId = $payments->getNextId();
    ?>
      <div class="wrap">
    	  <h2>PayPal Transactions</h2>
    	  <table border="1">
    	  <thead>
    	      <tr>
    	      <!-- <th></th> -->
    	      <th>Id</th>
    	      <!-- <th>Intent</th> -->
    	      <th>Payer</th>	<!-- PayPal\Api\Payer -->
    	      <!-- <th>Payee</th> -->	<!-- PayPal\Api\Payee -->
    	      <!-- <th>Transactions</th> -->	<!-- PayPal\Api\Transaction[] -->
    	      <th>Amount</th>
    	      <th>Currency</th>
    	      <!-- <th>Reference Id</th> -->
    	      <th>State</th>
    	      <th>Id</th>
    	      <th>State</th>
    	      <!-- <th>Experience Profile Id</th> -->
    	      <th>Note To Payer</th>
    	      <!-- <th>Redirect Urls</th> -->	<!-- PayPal\Api\RedirectUrls -->
    	      <th>Failure Reason</th>
    	      <th>Create Time</th>
    	      <!-- <th>Update Time</th> -->
    	      <!-- <th>Approval Link</th> -->
    	      </tr>
    	      </thead>
    	      <tbody>
    	  <?php
    	    $payments = $payments->getPayments();
    	    $row = 0; 
    	    foreach ($payments as $p) {
    	      $tc = count($p->getTransactions());
    	      $payer = $p->getPayer();
    	      $rowspan = ($tc > 1) ? "rowspan=\"$tc\"" : "";
    	      if ($payer->getPaymentMethod() == "credit_card") {
    	        $card = PayPalPlugin::getCreditCardInfo($payer->getFundingInstruments());
    	        /*
    	        echo "<!--\n";
    	        echo "Credit card: " . $fi[0]->getCreditCard() . "\n";
    	        echo "Credit card token: " . $fi[0]->getCreditCardToken() . "\n";
    	        echo "Payment card: " . $fi[0]->getPaymentCard() . "\n";
    	        echo "Billing: " . $fi[0]->getBilling() . "\n";
    	        echo "-->\n";
    	        */
    	        $first = $card->getFirstName();
    	        $middle = null;
    	        $last = $card->getLastName();
    	      } else {
    	        $payerInfo = $payer->getPayerInfo();
    	        $first = $payerInfo->getFirstName();
    	        $middle = $payerInfo->getMiddleName();
    	        $last = $payerInfo->getLastName();
  	          }
    	      ?>
    	      <tr>
    	      <!-- <td <?= $rowspan ?>><?= ++$row ?></td> -->
    	      <td <?= $rowspan ?>><?= $p->getId() ?></td>
    	      <!-- <td <?= $rowspan ?>><?= $p->getIntent() ?></td> -->
    	      <td <?= $rowspan ?>>
      	        <?= $first ?>
      	        <?= $middle ?>
      	        <?= $last ?>
    	      </td>
    	      <!-- <td <?= $rowspan ?>><?= $p->getPayee() ?></td> -->
    	      <?php PayPalPlugin::displayTransaction($p->getTransactions()[0]); ?>
    	      <td <?= $rowspan ?>><?= $p->getState() ?></td>
    	      <!-- <td <?= $rowspan ?>><?= $p->getExperienceProfileId() ?></td> -->
    	      <td <?= $rowspan ?>><?= $p->getNoteToPayer() ?></td>
    	      <!-- <td <?= $rowspan ?>><?= $p->getRedirectUrls() ?></td> -->
    	      <td <?= $rowspan ?>><?= $p->getFailureReason() ?></td>
    	      <td <?= $rowspan ?>><?= $p->getCreateTime() ?></td>
    	      <!-- <td <?= $rowspan ?>><?= $p->getUpdateTime() ?></td> -->
    	      <!-- <td <?= $rowspan ?>><?= $p->getApprovalLink() ?></td> -->
    	      </tr>
    	      <?php
    	      for ($tn=1; $tn < $tc; $tn++) {
    	        PayPalPlugin::displayTransaction($p->getTransactions()[$tn]);
    	      }
    	    }
  	      ?>
  	      </tbody>
  	      <?php
  	        $prevStart = $start - $limit;
  	        $nextStart = $start + $limit;
  	      ?>
  	      <?php if ($prevStart >= 0 || $nextId) { ?>
  	        <?php
  	          $adminURL = get_admin_url(null, "admin.php");
  	        ?>
  	        <tfoot>
  	          <tr>
  	            <td colspan="10">
  	              <?php if ($prevStart >= 0) { ?>
  	                <a style="float: left;" href="<?= add_query_arg( array( 'page' => 'paypal-transactions', 'start' => $prevStart, 'limit' => $limit), $adminURL) ?>">Prev</a>
  	              <?php } ?>
  	              &nbsp;
  	              <?php if ($nextId) { ?>
  	                <a style="float: right;" href="<?= add_query_arg( array( 'page' => 'paypal-transactions', 'start' => $nextStart, 'limit' => $limit), $adminURL)?>">Next</a>
  	              <?php } ?>
  	            </td>
  	          </tr>
  	        </tfoot>
  	      <?php } ?>
  	      </table>
    	</div>
      <?php
    }
    
  }