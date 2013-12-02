<?php

/**
 * PaymentProviders details the various ad types available, their parameters,
 * how to customize them and how to render them
 */
class PaymentProviders extends CComponent {

    public static function getList() {
        Yii::import('application.components.PaymentProviders.*');

        //We open the paymentprovider's dir..
        $path = dirname(__FILE__) . '/PaymentProviders/';
        $dir = dir($path);

        //We browse it
        $array = array();
        while (false !== ($entry = $dir->read())) {
            if (!in_array($entry, array('.', '..', 'PaymentProvider.php'))) {
                //We load the class
                $classname = substr($entry, 0, -4);
                $array[] = new $classname(); //And add it to the list!
            }
        }

        return $array;
    }

    /**
     * Selects the right class corresponding to a payment provider
     * @param type $type The payment rpovider's name
     * @return PaymentProvider The corresponding class
     * @throws CException
     */
    public static function getProviderClass($provider) {
        Yii::import('application.components.PaymentProviders.*');
        $adclass = NULL;
        try {
            $classname = $provider;
            $providerclass = new $provider();
        } catch (Exception $ex) {
            throw new CException('Provider not supported');
        }

        if (!$providerclass->isEnabled()) {
            throw new CException('Provider not enabled');
        }

        return $providerclass;
    }

}

?>
