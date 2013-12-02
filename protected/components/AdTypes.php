<?php

/**
 * AdTypes details the various ad types available, their parameters,
 * how to customize them and how to render them
 */
class AdTypes extends CComponent {

    public static function getList() {
        Yii::import('application.components.AdTypes.*');

        //We open the paymentprovider's dir..
        $path = dirname(__FILE__) . '/AdTypes/';
        $dir = dir($path);

        //We browse it
        $array = array();
        while (false !== ($entry = $dir->read())) {
            if (!in_array($entry, array('.', '..', 'AdType.php'))) {
                //We load the class
                $classname = substr($entry, 0, -4);
                $array[] = new $classname(); //And add it to the list!
            }
        }

        return $array;
    }

    /**
     * Selects the right class corresponding to an ad space's type of ad
     * @param type $type The type of ad of the ad space
     * @return AdType The corresponding class
     * @throws CException
     */
    public static function getTypeClass($type) {
        Yii::import('application.components.AdTypes.*');

        //We load the right class...
        try {
            $adclass = new $type;
        } catch (Exception $ex) {
            var_dump($ex);
            throw new CException('AdType not supported');
        }
        return $adclass;

        /*
          $adclass = NULL;
          switch($type) {
          case 'Textline':
          require_once('TextLine.php');
          $adclass = new TextLine();
          break;
          case 'Banner':
          require_once('Banner.php');
          $adclass = new Banner();
          break;
          default:
          throw new CException('AdType not supported');
          break;
          }
         * */

        return $adclass;
    }

}

?>
