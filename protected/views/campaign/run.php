<?php

$this->breadcrumbs = array(
    'Campaigns' => array('manage'),
    $campaign->name => array('details', 'id' => $campaign->id),
    'Run',
);

$this->menu = array(
    array('label' => 'List Campaign', 'url' => array('index')),
    array('label' => 'Manage Campaign', 'url' => array('admin')),
);

echo '<h1>' . Yii::t('app', 'Run an ad campaign') . '</h1>';
echo '<em>' . Yii::t('app', 'This wizard will guide you in order to run the selected campaign on one of the available ad spaces') . '</em>';

switch ($page) {
    case 1:
        echo '<h2>' . Yii::t('app', 'Choose a website and ad space') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please choose a website and ad space on which you wish to display this ad campaign') . '</em>';
        echo '<br/><br/>';

        $websites = Website::model()->findAll('enabled = 1');
        foreach ($websites as $website) {
            $adspaces = Adspace::model()->findAll('enabled = 1 AND website_id = :websiteid AND type = :type', array(':websiteid' => $website->id, 'type' => $campaign->type));

            echo '<h3 style="margin-bottom:0px;">' . htmlspecialchars($website->name) . '&nbsp;&ndash;&nbsp;';
            echo '<em>' . CHtml::link($website->url, $website->url, array('target' => '_blank', 'style' => 'opacity: 0.5')) . '</em></h3>';
            if (!empty($website->description))
                echo $website->description . '<br/>';
            
            foreach ($adspaces as $adspace) {
                echo '<br />';
                
                $validated = CampaignController::validateForAdspace($campaign, $adspace);
                if (!$validated) //If it's not validated
                    echo '<div style="opacity:0.5;">';
                else
                    echo '<div>';
                
                echo '<h4 style="margin-bottom:0px;">' . htmlspecialchars($adspace->name) . '&nbsp;&ndash;&nbsp;';
                echo '<em>' . CHtml::link($adspace->url, $adspace->url, array('target' => '_blank', 'style' => 'opacity: 0.5')) . '</em></h4>';
                if (!empty($adspace->description))
                    echo $adspace->description . '<br/>';
                
                //Check if the campaign can be displayed on this ad space
                if ($validated)
                    echo CHtml::link(Yii::t('app', 'Run your ad on :name', array(':name' => $adspace->name)), array('campaign/run', 'campaign_id' => $campaign_id, 'page' => 2, 'adspace_id' => $adspace->id));
                else
                    echo Yii::t('app', 'Incompatible  with this ad campaign').' - ' . CHtml::link(Yii::t('app', 'Create a new ad campaign for :name', array(':name' => $adspace->name)), array('campaign/create', 'page' => 4, 'adspace_id' => $adspace->id)) . '<br/>';
                    
                echo '</div>';
            }
            echo '<br/>';
        }
        break;

    case 2:
        echo '<h2>' . Yii::t('app', 'Choose a pricing model') . '</h2>';
        echo '<em>' . Yii::t('app', 'Please choose the pricing model you want to use for this ad campaign') . '</em>';
        echo '<br/><br/>';

        echo '<div class="form">';
        echo CHtml::beginForm(Yii::app()->createUrl('campaign/run', array('campaign_id' => $campaign_id, 'page' => 2, 'adspace_id' => $adspace->id)), 'get');

        if (!empty($error))
            echo '<div class="' . CHtml::$errorSummaryCss . '"><strong>' . $error . '</strong></div>';

        if ($adspace->paybyclick_enabled == 1) {
            echo '<div class="row">';
            echo CHtml::radioButton('paytype', false, array('value' => 'click', 'id' => 'paytype_click'));
            echo CHtml::label(Yii::t('app', 'By clicks'), 'paytype_click', array('style' => 'display:inline; margin-left:5px;'));
            echo '<div style="margin-left: 20px;">';
            echo Yii::t('app', 'Price per click : :price €', array(':price' => $adspace->paybyclick_price)) . '<br/>';
            echo Yii::t('app', 'Minimum clicks : :minimum', array(':minimum' => $adspace->paybyclick_minimum)) . '<br/>';
            echo Yii::t('app', 'Clicks to buy : ');
            echo CHtml::textField('paytype_click_amount', '100', array('style' => 'width:60px'));
            echo Yii::t('app', ' x :price €', array(':price' => $adspace->paybyclick_price));
            echo '</div></div>';
        }

        if ($adspace->paybyview_enabled == 1) {
            echo '<div class="row">';
            echo CHtml::radioButton('paytype', false, array('value' => 'view', 'id' => 'paytype_view'));
            echo CHtml::label(Yii::t('app', 'By views'), 'paytype_view', array('style' => 'display:inline; margin-left:5px;'));
            echo '<div style="margin-left: 20px;">';
            echo Yii::t('app', 'Price per 1000 views : :price €', array(':price' => $adspace->paybyview_price)) . '<br/>';
            echo Yii::t('app', 'Minimum views : :minimum', array(':minimum' => $adspace->paybyview_minimum)) . '<br/>';
            echo Yii::t('app', 'Views to buy : ');
            echo CHtml::textField('paytype_view_amount', '2000', array('style' => 'width:60px'));
            echo Yii::t('app', ' x :price €', array(':price' => $adspace->paybyview_price / 1000));
            echo '</div></div>';
        }

        if ($adspace->paybyduration_enabled == 1) {
            echo '<div class="row">';
            echo CHtml::radioButton('paytype', false, array('value' => 'duration', 'id' => 'paytype_duration'));
            echo CHtml::label(Yii::t('app', 'By duration'), 'paytype_duration', array('style' => 'display:inline; margin-left:5px;'));
            echo '<div style="margin-left: 20px;">';
            echo Yii::t('app', 'Price per day : :price €', array(':price' => $adspace->paybyduration_price)) . '<br/>';
            echo Yii::t('app', 'Minimum days : :minimum', array(':minimum' => $adspace->paybyduration_minimum)) . '<br/>';
            echo Yii::t('app', 'Days to buy : ');
            echo CHtml::textField('paytype_duration_amount', '100', array('style' => 'width:60px'));
            echo Yii::t('app', ' x :price €', array(':price' => $adspace->paybyduration_price));
            echo '</div></div>';
        }

        echo '<div class="row submit">';
        echo CHtml::submitButton(Yii::t('app', 'Select'));
        echo '</div>';

        echo CHtml::endForm();
        echo '</div><!-- form -->';

        break;

    case 3:
        echo '<h2>' . Yii::t('app', 'Invoice') . '</h2>';
        echo '<em>' . Yii::t('app', 'Here is the total price you have to pay in order to display this ad campaign') . '</em>';
        echo '<br/><br/>';

        echo '<table>';
        echo '<tr><th>' . Yii::t('app', 'Campaign') . '</th><th>' . Yii::t('app', 'Ad space') . '</th><th>' . Yii::t('app', 'Pricing model') . '</th><th>' . Yii::t('app', 'Amount') . '</th><th>' . Yii::t('app', 'Price per unit') . '</th><th>' . Yii::t('app', 'Price') . '</th></tr>';
        echo '<tr><td>' . htmlspecialchars($campaign->name) . '</td>';
        echo '<td>' . htmlspecialchars($adspace->name) . '</td>';
        echo '<td>' . $invoicedata['paytype_name'] . '</td>';
        echo '<td>' . $invoicedata['paytype_amount'] . '</td>';
        echo '<td>' . $invoicedata['paytype_price'] . '</td>';
        echo '<td>' . $invoicedata['price'] . ' €</td>';
        echo '</table>';

        echo '<table><tr><td>';
        echo '<h4>' . Yii::t('app', 'Coupon') . '</h4>';
        echo Yii::t('app', 'If you wish to use a coupon, please enter it below') . '<br/>';
        echo CHtml::form($this->createUrl('', $_GET), 'get'); //We Get to the same url with the same parameters
        echo '<strong>' . CHtml::label(Yii::t('app', 'Coupon'), '') . ' :</strong>';
        echo CHtml::textField('coupon');
        echo CHtml::submitButton(Yii::t('app', 'Use'));
        echo CHtml::endForm();
        echo '</td><td>';
        if (!empty($invoicedata['coupon_savings'])) {
            echo '<span style="font-size:18px">' . Yii::t('app', 'Coupon Savings') . ' : <strong>' . $invoicedata['coupon_savings'] . ' €</strong></span><br/>';
        }
        echo '<span style="font-size:18px">' . Yii::t('app', 'Final Price') . ' : <strong>' . $invoicedata['final_price'] . ' €</strong></span><br/>';
        echo '<span style="font-size:18px">' . Yii::t('app', 'Account Balance') . ' : <strong>' . $invoicedata['account_balance'] . ' €</strong></span>&nbsp;';
        echo CHtml::link('[' . Yii::t('app', 'Add money') . ']', $this->createUrl('member/fund', array('amount' => $invoicedata['needed_fund_amount'] * 100, 'return_url' => Yii::app()->request->requestUri))) . '</span><br/>';

        //TODO: Option to auto-repeat after expiration of the campaign
        if ($invoicedata['account_balance'] < $invoicedata['final_price']) {
            //Not enough money
            echo CHtml::button(Yii::t('app', 'Pay and display'), array('disabled' => true));
        } else {
            echo CHtml::form();
            echo CHtml::submitButton(Yii::t('app', 'Pay and display'), array('url' => 'site/index',));
            echo CHtml::endForm();
        }
        echo '</td></tr>';
        echo '</table>';
        break;

    case 4:
        echo '<h2>' . Yii::t('app', 'Finished') . '</h2>';
        echo '<em>' . Yii::t('app', 'End of the wizard') . '</em>';
        echo '<br/><br/>';

        echo Yii::t('app', 'Your campaign has been enabled and will be displayed on the selected ad space in a few minutes, as soon as our cache updates.');
        break;
}

echo '<br/><br/><div align="center">';
echo CHtml::link(Yii::t('app', 'Return to the panel'), $this->createUrl('member/panel'));
echo '</div>';
?>