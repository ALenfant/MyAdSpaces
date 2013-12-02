<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class PaymentProvider extends CComponent {

    public $enabled = false;

    /**
     * Returns if the current payment provider is enabled andcan be used by users
     * @return type
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Returns a payment provider's internal codename
     * @return type
     */
    public function getCodeName() {
        return get_class($this);
    }

    /**
     * Returns a payment provider's name which will be displayed to the user
     */
    abstract public function getName();

    /**
     * Returns a payment provider's description which will be displayed to the user
     */
    abstract public function getDescription();

    /**
     * Returns the minimum amount that can be funded using this payment provider
     */
    abstract public function getMinimumAmount();

    /**
     * Returns the payment provider's logo (in images/payment/providers) which will be displayed to the user
     */
    abstract public function getLogoFilename();

    /**
     * Processes q pqyment's return (after the user has paid)
     * Should check for valid payment with provider's API and then call recordTransaction
     */
    abstract public function processPayment($sub_page);

    /**
     * Displays the payment form for this provider that will lead to its page
     */
    abstract public function processDisplay($sub_page, $amount);

    /**
     * Processes a payment notification, like processPayment but called directly by the provider when the transaction is complete
     * The provider can notify and call this function using Yii::app()->createAbsoluteUrl('payment/notification', array('provider' => $this->getCodeName()))
     * Should check for valid payment with provider's API and then call recordTransaction
     */
    abstract public function processNotification();

    /**
     * Get a transaction using its id
     * @param type $transaction_id
     * @return type
     */
    public function getTransaction($transaction_id) {
        return Paymenttransaction::model()->find('transaction_id = :transactionid AND payment_provider = :paymentprovider', array(':transactionid' => $transaction_id, ':paymentprovider' => $this->getCodeName()));
    }

    /**
     * Check if a transaction exists
     * @param type $transaction_id
     * @return type
     */
    public function transactionExists($transaction_id) {
        return !($this->getTransaction($transaction_id) === null);
    }

    /**
     * Record a successful transaction and add the amount to the user's account
     * NOTE: This should only be called if the payment is successful
     * NOTE: Can (and should!) be called by both processPayment and processNotification for the same transaction, but they must provide the same transaction id to avoid funding double the amount
     * @param type $transaction_id
     * @param type $user_id
     * @param type $amount_funded
     * @param type $details
     * @return boolean
     */
    public function recordTransaction($transaction_id, $user_id, $amount_funded, $details = Array()) {
        if (!$this->transactionExists($transaction_id)) {
            //Add the amount to the user's account
            $user = User::model()->findByPk($user_id);
            $user->balance += $amount_funded;
            $user->fund_amount_funded += $amount_funded;
            $user->save();

            //Save the transaction
            $transaction = new Paymenttransaction();
            $transaction->transaction_id = $transaction_id;
            $transaction->payment_provider = $this->getCodeName();
            $transaction->amount_funded = $amount_funded;
            $transaction->time = date('Y-m-d H:i:s');
            $transaction->user_id = Yii::app()->user->id;
            $transaction->details = json_encode($details);
            return $transaction->save();
        }
        return false; //Nothing changed
    }

}

?>
