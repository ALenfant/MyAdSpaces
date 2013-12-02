<h2><?php echo yii::t('app', 'Campaign run created'); ?></h2>
<?php echo yii::t('app', 'Your new campaign run for the campaign {campaign} on {sitename} has been successfully created.', array('{campaign}' => $campaign->name, '{sitename}' => Yii::app()->name)); ?><br/>
<?php echo yii::t('app', 'Date and time of creation {datetime}', array('{datetime}' => $campaignrun->time_created)); ?><br/>
<br />
<h3><?php echo yii::t('app', 'Invoice details :'); ?></h3><br/>
<table>
    <tr><td><?php echo Yii::t('app', 'Base Price for {paytype_amount} {paytype_name}', array('{paytype_name}' => $invoice_data['paytype_name'], '{paytype_amount}' => $campaignrun->paytype_amount)); ?></td>
        <td><?php echo Yii::t('app', '{base_price} €', array('{base_price}' => $invoice_data['price'])); ?></td></tr>
    <?php if ($invoice_data['coupon_savings'] > 0) { ?>
        <tr><td><?php echo Yii::t('app', 'Coupon Savings'); ?></td>
            <td><?php echo Yii::t('app', '-{coupon_savings} €', array('{coupon_savings}' => $invoice_data['coupon_savings'])); ?></td></tr>
    <?php } ?>
    <tr><td><?php echo Yii::t('app', 'Final Price'); ?></td>
        <td><?php echo Yii::t('app', '{final_price} €', array('{final_price}' => $invoice_data['price'])); ?></td></tr>
    <tr><td><?php echo Yii::t('app', 'Account balance after the transaction'); ?></td>
        <td><?php echo Yii::t('app', '{balance_after} €', array('{balance_after}' => $user->balance)); ?></td></tr>
</table>
<br />
<?php echo yii::t('app', 'Your campaign will appear in a few minutes on the selected ad space.'); ?><br/>
<br />
<?php echo yii::t('app', 'Thank you,'); ?><br/>
<?php echo yii::t('app', 'The {sitename} team', array('{sitename}' => Yii::app()->name)); ?>