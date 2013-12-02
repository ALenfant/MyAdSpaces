<?php

class VoucherProvider extends PaymentProvider {

    public $enabled = true;

    public function getName() {
        return Yii::t('paymentprovider', 'Voucher');
    }

    public function getDescription() {
        return Yii::t('paymentprovider', 'Use a voucher that was given to you');
    }

    public function getLogoFilename() {
        return 'voucher.png';
    }

    public function getMinimumAmount() {
        return 1;
    }

    public function processDisplay($sub_page, $amount) {
        echo Yii::t('paymentprovider', 'Please enter your voucher\'s code below.');
        echo '<br/>';
        echo Yii::t('paymentprovider', 'The total value of your voucher will then be added to your account.');
        echo '<br/><br/>';

        echo '<div align="center" class="form">';
        echo CHtml::form();
        echo CHtml::label(Yii::t('paymentprovider', 'Voucher code'), 'code');
        echo CHtml::textField('code') . '<br/>';
        echo CHtml::submitButton(Yii::t('paymentprovider', 'Add this amount'));
        echo CHtml::endForm();
        echo '</div>';

        echo '<br/>';
        echo Yii::t('paymentprovider', 'If you don\'t have any vouchers left to use, please use the link below to finish the process.');
    }

    public function processPayment($sub_page) {
        if (!empty($_POST['code'])) {
            $code = $_POST['code'];
            
            //We check if the code exists
            $voucher = Voucher::model()->find('code = :code', array(':code' => $code));
            $user_id = Yii::app()->user->id;
            
            if ($voucher == null) {
                Yii::app()->user->setflash('error', Yii::t('paymentprovider', 'The voucher code you entered is invalid.'));
                return;
            }
            if ($voucher->used == 1) {
                Yii::app()->user->setflash('error', Yii::t('paymentprovider', 'The voucher code you entered was already used.'));
                return;
            }
            if (!empty($voucher->user_id) && $voucher->user_id != $user_id) {
                Yii::app()->user->setflash('error', Yii::t('paymentprovider', 'The voucher code you entered is reserved to another account.'));
                return;
            }
            
            //We process the transaction
            $this->recordTransaction($voucher->code, $user_id, $voucher->amount);
            
            //We set the voucher as used
            $voucher->used = 1;
            $voucher->save();
            
            //Valid voucher
            Yii::app()->user->setflash('success', Yii::t('paymentprovider', 'The voucher\'s amount of :amount € was added to your account.', array(':amount', $voucher->amount)));
        }
    }

    public function processNotification() {
        ;
    }

}

?>
