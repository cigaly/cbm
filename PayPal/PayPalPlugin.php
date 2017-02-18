<?php

require 'PayPal-PHP-SDK/autoload.php';
require_once 'functions.php';
require_once 'ListPayments.php';

class PayPalPlugin {
  
  public static function add_menu_item() {
    add_menu_page ( "PayPal Transactions", "PayPal Transactions", "manage_options", "paypal-transactions", "PayPalPlugin::transactions_page", null, 99 );
  }
  
  public static function transactions_page() {
    $payments = ListPayments::list_payments(0, 30);
    ?>
      <div class="wrap">
    	  <h2>PayPal Transactions</h2>
    	  <table border="1">
    	  <thead>
    	      <tr>
    	      <th></th>
    	      <th>Id</th>
    	      <th>Intent</th>
    	      <th>Payer</th>	<!-- PayPal\Api\Payer -->
    	      <th>Payee</th>	<!-- PayPal\Api\Payee -->
    	      <th>Transactions</th>	<!-- PayPal\Api\Transaction[] -->
    	      <th>State</th>
    	      <th>Experience Profile Id</th>
    	      <th>Note To Payer</th>
    	      <th>Redirect Urls</th>	<!-- PayPal\Api\RedirectUrls -->
    	      <th>Failure Reason</th>
    	      <th>Create Time</th>
    	      <th>Update Time</th>
    	      <th>Approval Link</th>
    	      </tr>
    	      </thead>
    	      <tbody>
    	  <?php
    	    $row = 0; 
    	    foreach ($payments->getPayments() as $p) {
    	      $tc = count($p->getTransactions());
    	      ?>
    	      <tr>
    	      <td rowspan="<?=tc?>"><?= ++$row ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getId() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getIntent() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getPayer() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getPayee() ?></td>
    	      <td><?= $p->getTransactions()[0] ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getState() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getExperienceProfileId() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getNoteToPayer() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getRedirectUrls() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getFailureReason() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getCreateTime() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getUpdateTime() ?></td>
    	      <td rowspan="<?=tc?>"><?= $p->getApprovalLink() ?></td>
    	      </tr>
    	      <?php
    	      for ($tn=1; $tn < $tc; $tn++) {
    	        ?>
    	        <tr>
    	        <td><?= $p->getTransactions()[$tn] ?></td>
    	        </tr>
    	        <?php
    	      }
    	    }
  	      ?>
  	      </tbody>
  	      </table>
    	</div>
      <?php
    }
    
  }