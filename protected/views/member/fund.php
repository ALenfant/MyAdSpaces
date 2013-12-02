<?php

$this->breadcrumbs = array(
    Yii::t('app', 'Fund'),
);

echo '<h1>' . Yii::t('app', 'Fund your account') . '</h1>';
echo '<em>' . Yii::t('app', 'This wizard will help you add money to your account balance on the service') . '</em>';

switch ($page) {
    case 1:
        echo '<h2>' . Yii::t('app', 'Enter an amount') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please enter the amount you wish to add to your account') . '</em>';
        echo '<br/><br/>';

        echo Yii::t('app', 'Your current account balance is :balance €', array(':balance' => $user->balance));
        echo '<br/><br/>';

        echo '<div class="form">';
        echo CHtml::form();
        if (!empty($error))
            echo '<div class="' . CHtml::$errorSummaryCss . '"><strong>' . $error . '</strong></div>';
        echo '<strong>' . Yii::t('app', 'Amount to add') . '</strong><br/>';
        echo CHtml::textField('amount', $display_amount) . ' €' . '<br/>';
        echo CHtml::submitButton(Yii::t('app', 'Add this amount'));
        echo CHtml::endForm();
        echo '</div>';
        break;

    case 2:
        echo '<h2>' . Yii::t('app', 'Choose a mean of payment') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please choose the mean of payment you wish to use to add :amount € to your account', array(':amount' => $display_amount)) . '</em>';
        echo '<br/><br/>';

        echo '<table>';
        $providers = PaymentProviders::getList();
        foreach ($providers as $provider) {
            if ($provider->isEnabled()) {
                echo '<tr style="cursor:pointer;" onclick="window.location = \'' . $this->createUrl('', array('page' => 3, 'provider' => $provider->getCodeName(), 'return_url' => $return_url)) . '\'"><td style="width:100px">';
                //Logo
                echo '<img src="images/payment/providers/' . $provider->getLogoFilename() . '"></img>';
                echo '</td><td>';
                //Title+description
                echo '<strong>' . $provider->getName() . '</strong><br/>';
                echo $provider->getDescription();
                echo '</td></tr>';
            }
        }
        echo '</table>';
        break;

    case 3:
        echo '<h2>' . Yii::t('app', 'Payment process') . '</h2>';
        echo '<em>' . Yii::t('app', 'You will now go through the payment provider\'s process in order to add :amount € to your account (:amount_remaining € remaining)', array(':amount' => $display_amount, ':amount_remaining' => $display_amount_remaining)) . '</em>';
        echo '<br/><br/>';

        $provider->processDisplay($sub_page, $display_amount_remaining);
        break;

    case 4:
        echo '<h2>' . Yii::t('app', 'Funding finished') . '</h2>';
        echo '<em>' . Yii::t('app', 'You finished funding the necessary amount to your account.') . '</em>';
        echo '<br/><br/>';

        if (!empty($return_url)) {
            echo '<div align="center">';
            echo CHtml::link(Yii::t('app', 'Return to the page you were on before funding'), urldecode($return_url));
            echo '</div>';
        }
        break;
}

echo '<br/><br/><div align="center">';
echo CHtml::link(Yii::t('app', 'Return to the panel'), $this->createUrl('member/panel'));
echo '</div>';
?>
