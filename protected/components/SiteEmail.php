<?php

/**
 * Siteemail setups the base parameters used in all the website's emails
 */
class SiteEmail extends CComponent {
    /**
     * Creates an email with default settings
     * @return \YiiMailMessage
     */
    public static function CreateEmail() {
        Yii::import('ext.yii-mail.YiiMailMessage');
        $email = new YiiMailMessage();
        $email->getHeaders()->addMailboxHeader('From');
        $email->setFrom(Yii::app()->params['senderEmail']);
        
        return $email;
    }
}

?>
