<?php

class PayPalProvider extends PaymentProvider {

    public $enabled;
    private $sandbox; //Use the PayPal sandbox to test ?
    private $account_email;
    private $pdt_token;
    private $lang; //Button language (en_US, fr_FR...)
    private $currency_code; //Currency code
    
    public function PayPalProvider() {
        //Load the parameters from the application's configuration...
        $this->enabled = Yii::app()->params['paymentProviders']['paypal']['enabled'];
        $this->sandbox = Yii::app()->params['paymentProviders']['paypal']['sandbox'];
        $this->account_email = Yii::app()->params['paymentProviders']['paypal']['account_email'];
        $this->pdt_token = Yii::app()->params['paymentProviders']['paypal']['pdt_token'];
        $this->lang = Yii::app()->params['paymentProviders']['paypal']['lang'];
        $this->currency_code = Yii::app()->params['paymentProviders']['paypal']['currency_code'];
    }

    public function getName() {
        return 'PayPal';
    }

    public function getDescription() {
        return Yii::t('paymentprovider', 'PayPal allows you to add funds from a Paypal account or using your credit card');
    }

    public function getLogoFilename() {
        return 'paypal.png';
    }

    public function getMinimumAmount() {
        return 1;
    }

    public function processDisplay($sub_page, $amount) {
        $data = Yii::app()->user->id; //We store the user id in the data field

        echo Yii::t('paymentprovider', 'Click the button below to proceed with the PayPal payment.');
        echo '<br/>';

        echo '<div align="center">';
        echo '<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">';
        echo '<input type="hidden" name="cmd" value="_xclick">';
        echo '<input type="hidden" name="business" value="' . $this->account_email . '">';
        echo '<input type="hidden" name="currency_code" value="' . $this->currency_code . '">';
        echo '<input type="hidden" name="item_name" value="' . Yii::t('paymentprovider', 'Account funding') . '">';
        echo '<input type="hidden" name="amount" value="' . $amount . '">';
        echo '<input name="custom" type="hidden" id="custom" value="' . $data . '">';
        echo '<input type="hidden" name="return" value="' . Yii::app()->createAbsoluteUrl('member/fund', $_GET) . '">';
        echo '<input type="hidden" name="notify_url" value="' . Yii::app()->createAbsoluteUrl('payment/notification', array('provider' => $this->getCodeName())) . '">';
        echo '<input type="image" src="http://www.paypal.com/' . $this->lang . '/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">';
        echo '</form>';
        echo '</div>';

        echo '<br/>';
        echo Yii::t('paymentprovider', 'If you just did a PayPal payment and it hasn\'t been taken into account yet, please refresh the page.');
    }

    /**
     * Process a PayPal IPN notification (made directly by Paypal's servers)
     */
    public function processNotification() {
        if (!empty($_POST)) {
            /* ini_set('log_errors', true);
              ini_set('error_log', 'ipn_errors.log'); */

            Yii::import('application.vendors.paypal.ipn.*');
            require_once('ipnlistener.php');
            $listener = new IpnListener();

            $listener->use_sandbox = $this->sandbox; //We'll use the sandbox if necessary
            //We process and check the PayPal IPN POST...
            try {
                $listener->requirePostMethod();
                $verified = $listener->processIpn();
            } catch (Exception $e) {
                file_put_contents('protected/logs/paypal_ipn.txt', "\n" . 'ERROR ' . $e->getMessage(), FILE_APPEND | LOCK_EX);
                exit(0);
            }

            if ($verified) { //Valid query made by PayPal
                $errmsg = '';

                $return_values = $listener->getValuesArray();
                //file_put_contents('OUTPUT_DEBUG.txt', var_export($return_values, true));
                //1.Valid status check 
                if ($return_values['payment_status'] != 'Completed') {
                    // simply ignore any IPN that is not completed
                    exit(0);
                }

                //2.Valid seller email check
                if ($return_values['receiver_email'] != $this->account_email) {
                    $errmsg .= "'receiver_email' does not match: ";
                    $errmsg .= $_POST['receiver_email'] . "\n";
                }

                //3.Valid amount check (not necessary here)
                /* if ($return_values['mc_gross'] != '9.99') {
                  $errmsg .= "'mc_gross' does not match: ";
                  $errmsg .= $_POST['mc_gross'] . "\n";
                  } */

                //4.Check the currency
                if ($return_values['mc_currency'] != $this->currency_code) {
                    $errmsg .= "'mc_currency' does not match: ";
                    $errmsg .= $_POST['mc_currency'] . "\n";
                }

                //5.Check the transaction type
                if ($return_values['txn_type'] != 'web_accept') {
                    $errmsg .= "'txn_type' does not match: ";
                    $errmsg .= $_POST['txn_type'] . "\n";
                }

                if (!empty($errmsg)) {
                    //If there's an error message
                    // manually investigate errors from the fraud checking
                    $body = "IPN failed fraud checks: \n$errmsg\n\n";
                    $body .= $listener->getTextReport();
                    file_put_contents('protected/logs/paypal_ipn.txt', "\n" . 'WRONG TRANSACTION (FRAUD?) ' . $body, FILE_APPEND | LOCK_EX);
                } else {
                    //Else, everything is valid, take into account the transaction
                    //file_put_contents('protected/logs/paypal_ipn.txt', "\n" . 'VALID (custom=' . @$_POST['custom'] . ') ' . $listener->getTextReport(), FILE_APPEND | LOCK_EX);
                    $this->processPaymentData($return_values); //Payment processing
                }
            } else { //Invalid query!
                file_put_contents('protected/logs/paypal_ipn.txt', "\n" . 'INVALID ' . $listener->getTextReport(), FILE_APPEND | LOCK_EX);
            }
        }
    }

    /**
     * Process Paypal PDT return (user finished payment and is back on the website)
     * @param type $sub_page
     */
    public function processPayment($sub_page) {
        if (!empty($_GET['tx'])) {
            ini_set('log_errors', true);
            ini_set('error_log', 'pdt_errors.log');

            Yii::import('application.vendors.paypal.pdt.*');
            require_once('pdtlistener.php');
            $listener = new PdtListener();

            $listener->use_sandbox = $this->sandbox; //We'll use the sandbox if necessary
            //We process and check the PayPal PDT POST...
            try {
                //$listener->requirePostMethod(); //Useless here
                $verified = $listener->processPdt($this->pdt_token);
            } catch (Exception $e) {
                file_put_contents('protected/logs/paypal_pdt.txt', "\n" . 'ERROR ' . $e->getMessage(), FILE_APPEND | LOCK_EX);
                exit(0);
            }

            if ($verified) { //Valid query made by PayPal
                $errmsg = '';

                $return_values = $listener->getValuesArray();

                //1.Valid status check 
                if ($return_values['payment_status'] != 'Completed') {
                    // simply ignore any IPN that is not completed
                    exit(0);
                }

                //2.Valid seller email check
                if ($return_values['receiver_email'] != $this->account_email) {
                    $errmsg .= "'receiver_email' does not match: ";
                    $errmsg .= $_POST['receiver_email'] . "\n";
                }

                //3.Valid amount check (not necessary here)
                /* if ($return_values['mc_gross'] != '9.99') {
                  $errmsg .= "'mc_gross' does not match: ";
                  $errmsg .= $_POST['mc_gross'] . "\n";
                  } */

                //4.Check the currency
                if ($return_values['mc_currency'] != $this->currency_code) {
                    $errmsg .= "'mc_currency' does not match: ";
                    $errmsg .= $_POST['mc_currency'] . "\n";
                }

                //5.Check the transaction type
                if ($return_values['txn_type'] != 'web_accept') {
                    $errmsg .= "'txn_type' does not match: ";
                    $errmsg .= $_POST['txn_type'] . "\n";
                }

                if (!empty($errmsg)) {
                    //If there's an error message
                    // manually investigate errors from the fraud checking
                    $body = "IPN failed fraud checks: \n$errmsg\n\n";
                    $body .= $listener->getTextReport();
                    file_put_contents('protected/logs/paypal_pdt.txt', "\n" . 'WRONG TRANSACTION (FRAUD?) ' . $body, FILE_APPEND | LOCK_EX);
                } else {
                    //Else, everything is valid, take into account the transaction
                    //file_put_contents('protected/logs/paypal_pdt.txt', "\n" . 'VALID (custom=' . @$_POST['custom'] . ') ' . $listener->getTextReport(), FILE_APPEND | LOCK_EX);
                    $this->processPaymentData($return_values); //Payment processing
                }
            } else { //Invalid query!
                file_put_contents('protected/logs/paypal_pdt.txt', "\n" . 'INVALID ' . $listener->getTextReport(), FILE_APPEND | LOCK_EX);
            }
        }
    }

    /**
     * Process a payment, either from Paypal IPN or PDT
     * @param type $data
     * @return type
     */
    private function processPaymentData($data) {
        //We record the transaction
        $transaction_id = $data['txn_id'];
        $user_id = $data['custom'];
        $amount_funded = $data['mc_gross'];
        $details = array(
            'payer_id' => $data['payer_id'],
            'payer_email' => $data['payer_email'],
            'address_status' => $data['address_status'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'payer_business_name' => @$data['payer_business_name'],
            'address_name' => $data['address_name'],
            'address_street' => $data['address_street'],
            'address_zip' => $data['address_zip'],
            'address_city' => $data['address_city'],
            'address_state' => $data['address_state'],
            'address_country' => $data['address_country'],
            'contact_phone' => @$data['contact_phone'],
            'mc_currency' => $data['mc_currency'],
            'mc_gross_x' => @$data['mc_gross_x'],
            'exchange_rate' => @$data['exchange_rate'],
            'mc_gross' => $data['mc_gross'],
            'mc_fee' => $data['mc_fee'],
            'payment_date' => $data['payment_date'],
        );
        return $this->recordTransaction($transaction_id, $user_id, $amount_funded, $details);
    }

}

?>
