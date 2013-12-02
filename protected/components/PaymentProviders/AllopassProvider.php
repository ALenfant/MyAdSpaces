<?php

class AllopassProvider extends PaymentProvider {

    public $enabled;
    private $site_id;
    private $product_id;
    private $lang; //Button language (en, fr, ...)

    public function AllopassProvider() {
        //Load the parameters from the application's configuration...
        $this->enabled = Yii::app()->params['paymentProviders']['allopass']['enabled'];
        $this->site_id = Yii::app()->params['paymentProviders']['allopass']['site_id'];
        $this->product_id = Yii::app()->params['paymentProviders']['allopass']['product_id'];
        $this->lang = Yii::app()->params['paymentProviders']['allopass']['lang'];
    }

    public function getName() {
        return 'Allopass';
    }

    public function getDescription() {
        return Yii::t('paymentprovider', 'Allopass allows you to add funds via micropayment, using SMS, phone call, credit card or other means of payment');
    }

    public function getLogoFilename() {
        return 'allopass.png';
    }

    public function getMinimumAmount() {
        return 1;
    }

    public function processPayment($sub_page) {
        //Load the Allopass PHP5 API...
        Yii::import('application.vendors.allopass.*');
        require_once('api/AllopassAPI.php');

        //Are we back from a payment?
        if (!empty($_GET['transaction_id'])) {
            $transaction_id = $_GET['transaction_id'];
            $api = new AllopassAPI();
            try {
                $transaction = $api->getTransaction($transaction_id); //Get the detail (API call)
                if ($transaction->getStatus() != AllopassTransactionDetailResponse::TRANSACTION_SUCCESS) {
                    throw new Exception(Yii::t('paymentprovider', 'The transaction wan\'t reported as successful'));
                }
                $this->recordAllopassTransaction($transaction);
            } catch (Exception $ex) {
                Yii::app()->user->setFlash('error', Yii::t('paymentprovider', 'The transaction couldn\'t be processed : :error', array(':error' => $ex->getMessage())));
            }
        }
    }

    public function processDisplay($sub_page, $amount) {
        $site_id = $this->site_id;
        $product_id = $this->product_id;
        $lang = $this->lang;
        $data = Yii::app()->user->id; //We store the user id in the data field

        $data = urlencode($data);

        echo Yii::t('paymentprovider', 'Since Allopass works with codes of different value, every payment will fund your account with the exact amount we receive from it. You may have to do more than one payment to add a large amount of money to your account.');
        echo '<br/>';
        echo Yii::t('paymentprovider', 'Check the amount remaining above to see how much you still need to add.');
        echo '<br/><br/>';

        echo '<div align="center">';
        echo '<!-- Begin Allopass Checkout-Button Code -->
        <script type="text/javascript" src="https://payment.allopass.com/buy/checkout.apu?ids=' . $site_id . '&idd=' . $product_id . '&lang=' . $lang . '&data=' . $data . '"></script>
        <noscript>
        <a href="https://payment.allopass.com/buy/buy.apu?ids=' . $site_id . '&idd=' . $product_id . '&data=' . $data . '" style="border:0">
            <img src="https://payment.allopass.com/static/buy/button/' . $lang . '/162x56.png" style="border:0" alt="Buy now!" />
        </a>
        </noscript>
        <!-- End Allopass Checkout-Button Code -->';
        echo '</div>';
    }

    public function processNotification() {
        $this->processPayment(-1); //the payment is processed just like a normal one (same GET parameters)
    }

    private function recordAllopassTransaction($transaction) {
        //We record the transaction
        $transaction_id = $transaction->getTransactionId();
        $user_id = $transaction->getData();
        $amount_funded = $transaction->getPaid()->getReferenceAmount();
        $codes = $transaction->getCodes();
        return $this->recordTransaction($transaction_id, $user_id, $amount_funded, $codes);
    }

}

?>
