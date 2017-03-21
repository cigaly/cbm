<?php

use PayPal\Api\Address;
use PayPal\Api\Agreement;
//use PayPal\Api\ChargeModel;
use PayPal\Api\CreditCard;
use PayPal\Api\Currency;
use PayPal\Api\FundingInstrument;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;


class Billing {
  
  static $CURRENCIES = array(
      'Australian Dollar' => 'AUD',
      'Brazilian Real' => 'BRL',
      'Canadian Dollar' => 'CAD',
      'Czech Koruna' => 'CZK',
      'Danish Krone' => 'DKK',
      'Euro' => 'EUR',
      'Hong Kong Dollar' => 'HKD',
      'Hungarian Forint' => 'HUF',
      'Israeli New Sheqel' => 'ILS',
      'Japanese Yen' => 'JPY',
      'Malaysian Ringgit' => 'MYR',
      'Mexican Peso' => 'MXN',
      'Norwegian Krone' => 'NOK',
      'New Zealand Dollar' => 'NZD',
      'Philippine Peso' => 'PHP',
      'Polish Zloty' => 'PLN',
      'Pound Sterling' => 'GBP',
      'Russian Ruble' => 'RUB',
      'Singapore Dollar' => 'SGD',
      'Swedish Krona' => 'SEK',
      'Swiss Franc' => 'CHF',
      'Taiwan New Dollar' => 'TWD',
      'Thai Baht' => 'THB',
      'U.S. Dollar' => 'USD'
  );
  
  const DEFAULT_CURRENCY = 'EUR';
  
  private static function currencySelector($name, $default = self::DEFAULT_CURRENCY) {
    ?>
    <select name="<?=$name?>">
      <?php foreach (self::$CURRENCIES as $n => $c) { ?>
        <option value="<?=$c?>" <?php if ($c == self::DEFAULT_CURRENCY) { ?>selected="selected"<?php } ?>><?=$n?></option>
      <?php } ?>
    </select>
    <?php
  }
  
  private static function baseUrl() {
    return get_site_url();
  }

  private static function getApiContext() {
    global $clientId, $clientSecret;
    return getApiContext($clientId, $clientSecret);
  }
  
  public static function billing_page() {
    global $_POST;

    if (key_exists('activate', $_POST)) {
      $plan = self::getBillingPlan($_POST['activate']);
      $success = self::activateBillingPlan($plan);
      ?>Activate <?=$_POST['activate']?> =&gt; <?=$success?><br /><?php 
    } else if (key_exists('deactivate', $_POST)) {
      $plan = self::getBillingPlan($_POST['deactivate']);
      $success = self::deactivateBillingPlan($plan);
      ?>Deactivate <?=$_POST['deactivate']?> =&gt; <?=$success?><br /><?php 
    } else if (key_exists('delete', $_POST)) {
      $plan = self::getBillingPlan($_POST['delete']);
      $success = self::deleteBillingPlan($plan);
      ?>Delete <?=$_POST['delete']?> =&gt; <?=$success?><br /><?php 
    } else if (key_exists('submit_plan', $_POST)) {
      $params = array();
      ?>Post parameters:<br/>
      <?php foreach ($_POST as $k => $v) { ?>
        <?=$k?> : "<?=$v?>"<br /><?php 
        if (!empty($v)) {
          $params[$k] = $v;
        }
      }

      $output = self::createBillingPlan($params);
      echo "<b>Plan</b>: " . $output->toJSON() . "<br />";
    }
    
    ?><form method="post" action=""><table border="1">
    <caption>Billing plans</caption>
    <tbody>
    <tr>
    <th>Id</th>
    <th>State</th>
    <th>Name</th>
    <th>Description</th>
    <th>Type</th>
    <th>Create Time</th>
    <th>Update Time</th>
    <th colspan="2"></th>
    </tr>
    <?php
    try {
    foreach (array('CREATED', 'ACTIVE', 'INACTIVE'/*, 'DELETED'*/) as $status) {
      $plans = self::getBillingPlans($status);
      if ($plans->getPlans()) {
//         echo gettype($plans->getPlans())  . "<br >";
        foreach ($plans->getPlans() as $p) {
          ?>
          <tr>
          <!-- <?=$p?> -->
          <td><?=$p->getId()?></td>
          <td><?=$p->getState()?></td>
          <td><?=$p->getName()?></td>
          <td><?=$p->getDescription()?></td>
          <td><?=$p->getType()?></td>
          <td><?=$p->getCreateTime()?></td>
          <td><?=$p->getUpdateTime()?></td>
          <td>
          <?php if ($p->getState() == 'ACTIVE') { ?>
          <button type="submit" name="deactivate" value="<?=$p->getId()?>">Deactivate</button>
          <?php } else { ?>
          <button type="submit" name="activate" value="<?=$p->getId()?>">Activate</button>
          <?php } ?>
          </td>
          <td>
          <button type="submit" name="delete" value="<?=$p->getId()?>">Delete</button>
          </td>
          </tr>
          <?php 
        }
      }

    }
    } catch (Exception $ex) {
      echo "Exception: " . $ex->getMessage() . "<br />";
      echo "Trace: <pre>" . $ex->getTraceAsString() . "</pre><br />";
      var_dump(json_decode($ex->getData()));
    }

    ?>
    </tbody>
    </table></form>
    <?php 
    ?>
    <br /><br />
        <form method="post" action="">
    	  <h2>PayPal Billing Plan</h2>
    	  <table>
    	  <caption></caption>
    	  <tbody>
    	  <tr><th>Plan name</th><td><input type="text" name="name" maxlength="128" size="128" /></td>
<!--     	  <td> -->
<!--     	    The billing plan name.<br /> -->
<!--     	    Maximum length: 128. -->
<!--     	  </td> -->
    	  </tr>

    	  <tr><th>Description</th><td><input type="text" name="description" maxlength="128" size="128" /></td>
<!--     	  <td> -->
<!--     	    The billing plan description.<br /> -->
<!--     	    Maximum length: 128. -->
<!--     	    </td> -->
    	  </tr>
    	  
    	  <tr><th>Type</th><td>
    	    <select name="type">
    	      <option value="FIXED">Fixed</option>
    	      <option value="INFINITE">Infinite</option>
    	    </select>
    	  </td>
<!--     	  <td> -->
<!--     	    The billing plan type, which determines whether you must set -->
<!--     	    the number of cycles in the payment definition. Value is FIXED -->
<!--     	    or INFINITE. A fixed billing plan has a set number of cycles. -->
<!--     	    An infinite billing plan has 0, or infinite, cycles.<br /> -->
<!--     	    Allowed values: FIXED, INFINITE.<br /> -->
<!--     	    Maximum length: 20. -->
<!--     	  </td> -->
    	  </tr>
    	  
    	  <!-- Payment definitions for this billing plan. -->
    	  
    	  <tr><th>Definition name</th><td>
    	    <input type="text" name="def_name" maxlength="128" size="128" />
    	  </td>
<!--     	  <td> -->
<!--     	    The payment definition name.<br /> -->
<!--     	    Maximum length: 128. -->
<!--     	  </td> -->
    	  </tr>

    	  <tr><th>Definition type</th><td>
    	    <select name="def_type">
    	      <option value="TRIAL">Trial</option>
    	      <option value="REGULAR">Regular</option>
    	    </select>
    	  </td>
<!--     	  <td> -->
<!--     	    The payment definition type.<br /> -->
<!--     	    Possible values: TRIAL, REGULAR. -->
<!--     	  </td> -->
    	  </tr>

    	  <tr><th>Frequency</th><td>
    	    <input type="number" name="frequency_interval" />
    	    <select name="frequency">
    	      <option value="WEEK">Week</option>
    	      <option value="DAY">Day</option>
    	      <option value="YEAR">Year</option>
    	      <option value="MONTH">Month</option>
    	    </select>
    	  </td>
<!--     	  <td> -->
<!--     	    The frequency of the offered payment definition.<br /> -->
<!--     	    Possible values: WEEK, DAY, YEAR, MONTH. -->

<!--     	    The frequency interval at which the customer can be charged.<br /> -->
<!--     	    Value cannot be greater than 12 months. -->

<!--     	  </td> -->
    	  </tr>

    	  <tr><th>Cycles</th><td><input type="number" name="cycles" /></td>
<!--     	  <td> -->
<!--     	    The number of cycles in this payment definition.<br /> -->
<!--     	    For INFINITE type plans with a REGULAR payment definition type, -->
<!--     	    set cycles to 0. -->
<!--     	  </td> -->
    	  </tr>
    	    
    	  	<!-- For INFINITE type plans with a REGULAR payment definition type, set cycles to 0. -->

          <tr>
            <th>Amount</th>
            <td>
              <input type="text" name="amount_value" />
              <?php self::currencySelector("amount_currency") ?>
            </td>
<!--           <td>The amount to charge at the end of each cycle for this payment definition.</td> -->
          </tr>
            
          <!-- charge_models - array (contains the charge_models:v1 object) -->
          <!--  A charge model for a payment definition. -->
          <!--  A charge model defines shipping and tax information. -->
          
          
          <!-- Merchant Preferences -->
          
          <tr><th>Return URL</th><td><input type="url" name="return_url" size="80" /></td><td></td></tr>
          <tr><th>Cancel URL</th><td><input type="url" name="cancel_url" size="80" /></td>
<!--           <td> -->
<!--             ReturnURL and CancelURL are not required and used when creating billing agreement with payment_method as "credit_card". -->
<!--             However, it is generally a good idea to set these values, in case you plan to create billing agreements which accepts "paypal" as payment_method. -->
<!--             This will keep your plan compatible with both the possible scenarios on how it is being used in agreement. -->
<!--           </td> -->
          </tr>
          
    	  <tr><th>Auto Bill Amount</th><td>
    	    <select name="auto_bill_amount">
    	      <option value="YES">Yes</option>
    	      <option selected="selected" value="NO">No</option>
    	    </select>
    	  </td>
<!--     	  <td> -->
<!--     	    Indicates whether auto-billing is allowed for the outstanding -->
<!--     	    balance of the agreement in the next cycle. Value is YES or NO.<br /> -->
<!--     	    Default is NO.<br /> -->
<!--     	    Possible values: YES, NO. -->
<!--     	  </td> -->
    	  </tr>
          
    	  <tr><th>Initial Fail Amount Action</th><td>
    	    <select name="initial_fail_amount_action">
    	      <option selected="selected" value="CONTINUE">Continue</option>
    	      <option value="CANCEL">Cancel</option>
    	    </select>
    	  </td>
<!--     	  <td> -->
<!--     	    The action to take when the initial payment fails. Default is CONTINUE.<br /> -->
<!--     	    Possible values: CONTINUE, CANCEL. -->
<!--     	  </td> -->
    	  </tr>

          <tr><th>Max Fail Attempts</th><td><input type="number" name="max_fail_attempts" /></td>
<!--           <td> -->
<!--             The maximum number of allowed failed payment attempts.<br /> -->
<!--             Default is 0, which represents an infinite number of failed attempts. -->
<!--           </td> -->
          </tr>
            
          <tr>
            <th>Setup fee</th>
            <td>
              <input type="text" name="setup_fee_value" />
              <?php self::currencySelector("setup_fee_currency") ?>
            </td>
<!--           <td> -->
<!--             The set-up fee amount. Default is 0. -->
<!--           </td> -->
          </tr>
          </tbody>
          
          <tfoot>
          <tr><td colspan="2" style="text-align: center;"><input type="submit" name="submit_plan" value="OK" /></td></tr>
          </tfoot>
            
    	  </table>
  	    </form>
    <?php
  }
  
  public static function agreement_page() {
    if (key_exists('submit_agreement', $_POST)) {
      $params = array();
      foreach ($_POST as $k => $v) {
        if (!empty($v)) {
          $params[$k] = $v;
        }
      }

      $output = self::createBillingAgreement($params);
      $approvalLink = $output->getApprovalLink();
      ?>
      <b>Billing agreement</b>: <?=$output->toJSON()?><br />
      <a href="<?=$approvalLink?>">Approve</a><br />
      <?php
      return;
    }
    ?>
    <form action="<?php the_permalink(); ?>" method="post">
    	<h2>PayPal Billing Agreement</h2>
    	<table>
    		<caption>Billing agreement</caption>
    		<tbody>
    			<tr>
    				<th>Plan</th>
    				<td>
    				  <select name="plan_id">
    				    <?php
    				      $plans = self::getBillingPlans('ACTIVE');
    				      foreach ($plans->getPlans() as $p) {
    				        ?>
    				        <option value="<?=$p->getId()?>"><b><?=$p->getName()?></b>: <?=$p->getDescription()?></option>
    				        <?php
    				      }
    				    ?>
    				</select>
    		      </td>
    			</tr>
    			<tr>
    				<th>Name</th>
    				<td><input type="text" name="aname" maxlength="128" size="128" /></td>
    			</tr>
    			<tr>
    				<th>Description</th>
    				<td><input type="text" name="description" maxlength="128" size="128" /></td>
    			</tr>
    			<tr>
    				<th>Start date</th>
    				<td><input type="text" name="start_date" placeholder="yyyy-MM-dd z" /></td>
    			</tr>
    			<!-- agreement_details -->
    			<tr>
    				<!-- payer -->
    				<th>Payment method</th>
    				<td><select name="payment_method">
    					<!-- <option value="credit_card">Credit Card</option> -->
    					<option value="paypal">Paypal</option>
    				</select></td>
  				</tr>
  				<!-- shipping_address -->
  				<!-- override_merchant_preferences -->
  				<!-- override_charge_models -->
  				<!--
  				  charge_id - string - The ID of charge model.
  				  amount - object - The updated amount to associate with this charge model. 
  				 -->
    		</tbody>
          <tfoot>
          <tr><td colspan="2" style="text-align: center;"><input type="submit" name="submit_agreement" value="OK" /></td></tr>
          </tfoot>
    	</table>
    </form>
    <?php
  }
  
  private static function createBillingAgreement($params) {
    $plan = new Plan();
    $plan->setId($params['plan_id']);
    
    // Add Payer
    $payer = new Payer();
    $payer->setPaymentMethod($params['payment_method']);
    
    if ($params['payment_method'] == 'credit_card') {
      $addr = new Address();
      $addr->setLine1('111 First Street')
           ->setCity('Saratoga')
           ->setState('CA')
           ->setPostalCode('95070')
           ->setCountryCode('US')
      ;
      
      $payer_info = new PayerInfo();
      $payer_info->setEmail("jaypatel512-facilitator@hotmail.com")
                 // ->setBillingAddress($addr)
      ;

      $credit_card = new CreditCard();
      $credit_card->setType('visa')
                  ->setNumber('4417119669820331')
                  ->setExpireMonth('12')
                  ->setExpireYear('2017')
                  ->setCvv2('128')
      ;
      $fi = new FundingInstrument();
      $fi->setCreditCard($credit_card);
      
      $payer->setPayerInfo($payer_info)
            ->addFundingInstrument($fi)
      ;
    }
    
    $agreement = new Agreement();
    $agreement->setPlan($plan)
              ->setName($params['aname'])
              ->setDescription($params['description'])
              ->setStartDate($params['start_date'])
              ->setPayer($payer);
              

    // Add Shipping Address
    $shippingAddress = new ShippingAddress();
    $shippingAddress->setLine1('111 First Street')
                    ->setCity('Saratoga')
                    ->setState('CA')
                    ->setPostalCode('95070')
                    ->setCountryCode('US');
    $agreement->setShippingAddress($shippingAddress);
              
    echo "Agreement: " . $agreement . "<br />";

    try {
      return $agreement->create(self::getApiContext());
    } catch (Exception $ex) {
      echo "Exception: " . $ex->getMessage() . "<br />";
      echo "Trace: <pre>" . $ex->getTraceAsString() . "</pre><br />";
      var_dump(json_decode($ex->getData()));
    }
    
//     // ### Create Agreement
//     //     try {
//     // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
//     $agreement = $agreement->create($apiContext);
    
//     // ### Get redirect url
//     // The API response provides the url that you must redirect
//     // the buyer to. Retrieve the url from the $agreement->getApprovalLink()
//     // method
//     $approvalUrl = $agreement->getApprovalLink();
//     //     } catch (Exception $ex) {
//     //       exit(1);
//     //     }
    
//     echo "<b>Agreement</b>: " . $agreement->toJSON() . "\n";
    
//     $approvalLink = $agreement->getApprovalLink();
    
    ?><a href="<?=$approvalLink?>">Approve</a><?php

  }

  public static function billing_page_XXX($createdPlan) {
    echo "<b>Updated plan</b>: " . $plan->toJSON() . "<br />";
    /* Create a new instance of Agreement object
     {
     "name": "Base Agreement",
     "description": "Basic agreement",
     "start_date": "2015-06-17T9:45:04Z",
     "plan": {
     "id": "P-1WJ68935LL406420PUTENA2I"
     },
     "payer": {
     "payment_method": "paypal"
     },
     "shipping_address": {
     "line1": "111 First Street",
     "city": "Saratoga",
     "state": "CA",
     "postal_code": "95070",
     "country_code": "US"
     }
     }*/
    $agreement = new Agreement();
    
    $agreement->setName('Base Agreement')
              ->setDescription('Basic Agreement')
              ->setStartDate('2019-06-17T9:45:04Z');
    
    // Add Plan ID
    // Please note that the plan Id should be only set in this case.
    $plan = new Plan();
    $plan->setId($createdPlan->getId());
    $agreement->setPlan($plan);
    
    // Add Payer
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $agreement->setPayer($payer);
    
    // Add Shipping Address
    $shippingAddress = new ShippingAddress();
    $shippingAddress->setLine1('111 First Street')
      ->setCity('Saratoga')
      ->setState('CA')
      ->setPostalCode('95070')
      ->setCountryCode('US');
    $agreement->setShippingAddress($shippingAddress);
    
    // ### Create Agreement
//     try {
      // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
      $agreement = $agreement->create($apiContext);
    
      // ### Get redirect url
      // The API response provides the url that you must redirect
      // the buyer to. Retrieve the url from the $agreement->getApprovalLink()
      // method
      $approvalUrl = $agreement->getApprovalLink();
//     } catch (Exception $ex) {
//       exit(1);
//     }
    
    echo "<b>Agreement</b>: " . $agreement->toJSON() . "\n";
    
    $approvalLink = $agreement->getApprovalLink();
    
    ?><a href="<?=$approvalLink?>">Approve</a><?php 
    
  }
  
  private static function getBillingPlans($status = 'ACTIVE') {
    return Plan::all(array( 'status' => $status ), self::getApiContext());
  }
  
  private static function getBillingPlan($planId) {
    return Plan::get($planId, self::getApiContext());
  }

  private static function activateBillingPlan($plan) {
    $patch = new Patch();
  
    $value = new PayPalModel('{ "state":"ACTIVE" }');
  
    $patch->setOp('replace')
          ->setPath('/')
          ->setValue($value);
    $patchRequest = new PatchRequest();
    $patchRequest->addPatch($patch);
  
    return $plan->update($patchRequest, self::getApiContext());
  }

  private static function deactivateBillingPlan($plan) {
    $patch = new Patch();
  
    $value = new PayPalModel('{ "state":"INACTIVE" }');
  
    $patch->setOp('replace')
    ->setPath('/')
    ->setValue($value);
    $patchRequest = new PatchRequest();
    $patchRequest->addPatch($patch);
  
    return $plan->update($patchRequest, self::getApiContext());
  }
  
  private static function deleteBillingPlan($plan) {
    $patch = new Patch();
  
    $value = new PayPalModel('{ "state":"DELETED" }');
  
    $patch->setOp('replace') -> setPath('/') -> setValue($value);
    $patchRequest = new PatchRequest();
    $patchRequest->addPatch($patch);
  
    return $plan->update($patchRequest, self::getApiContext());
  }
  
  private static function updateBillingPlan() {
    $patch = new Patch();
    
    $value = new PayPalModel('{ "state":"ACTIVE" }');
    
    $patch->setOp('replace')
    ->setPath('/')
    ->setValue($value);
    $patchRequest = new PatchRequest();
    $patchRequest->addPatch($patch);
    
    $createdPlan->update($patchRequest, self::getApiContext());
  }
  
  private static function makePaymentDefinition($params) {
    
    $amount = new Currency(array(
        'value' => floatval($params['amount_value']),
        'currency' => $params['amount_currency']
    ));

    // # Payment definitions for this billing plan.
    $paymentDefinition = new PaymentDefinition();
    
    // The possible values for such setters are mentioned in the setter method documentation.
    // Just open the class file. e.g. lib/PayPal/Api/PaymentDefinition.php and look for setFrequency method.
    // You should be able to see the acceptable values in the comments.
    $paymentDefinition->setName($params['def_name'])
                      ->setType($params['def_type'])
                      ->setFrequency($params['frequency'])
                      ->setFrequencyInterval(intval($params['frequency_interval']))
                      ->setAmount(new Currency(array(
                          'value' => floatval($params['amount_value']),
                          'currency' => $params['amount_currency'])));
    if (key_exists('cycles', $params)) {
      $paymentDefinition->setCycles(intval($params['cycles']));
    }

    // Charge Models
//    $chargeModel = new ChargeModel();
//    $chargeModel->setType('SHIPPING')
//    ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
    
//    $paymentDefinition->setChargeModels(array($chargeModel));
    /*
     * charge_models - array (contains the charge_models:v1 object)
     *    A charge model for a payment definition.
     *    A charge model defines shipping and tax information.
     */
    
    return $paymentDefinition;
  }
  
  
  private static function successUrl() {
    return get_url_by_slug( 'execute-agreement' ) . '?success=true';
  }


  private static function cancelUrl() {
    return get_url_by_slug( 'execute-agreement' ) . '?success=false';
  }
  
  
  private static function makeMerchantPreferences($params) {
    
    $merchantPreferences = new MerchantPreferences();
    
    $returnUrl = key_exists('return_url', $params) ? $params['return_url'] : self::successUrl();
    $cancelUrl = key_exists('cancel_url', $params) ? $params['cancel_url'] : self::cancelUrl();
    
    $merchantPreferences->setReturnUrl($returnUrl)
                        ->setCancelUrl($cancelUrl)
                        ->setAutoBillAmount($params['auto_bill_amount'])
                        ->setInitialFailAmountAction($params['initial_fail_amount_action'])
                        ->setMaxFailAttempts(intval($params['max_fail_attempts']));
    if (key_exists('setup_fee_value', $params)) {
      $setupFee = new Currency(array(
          'value' => floatval($params['setup_fee_value']),
          'currency' => $params['setup_fee_currency']));
      $merchantPreferences->setSetupFee($setupFee);
    }
    
    return $merchantPreferences;
  }
  
  private static function createBillingPlan($params) {
    
    $apiContext = self::getApiContext();
    
    // Create a new instance of Plan object
    $plan = new Plan();
    
    // # Basic Information
    // Fill up the basic information that is required for the plan
    $plan->setName($params['name'])
         ->setDescription($params['description'])
         ->setType($params['type'])
         ->setPaymentDefinitions(array(self::makePaymentDefinition($params)))
         ->setMerchantPreferences(self::makeMerchantPreferences($params));
         
    return $plan->create($apiContext);
  }
  
}