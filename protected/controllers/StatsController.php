<?php

class StatsController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        // return the filter configuration for this controller, e.g.:
        return array(
            'accessControl', // perform access control for CRUD operations
            array(
                'ext.runactions.components.ERunActionsIntervalFilter + process', //Limit the rate of processing
                'interval' => 15, //seconds
                //'perClient' => true, //default = false
                'httpErrorNo' => 200, //default = 403
            //'httpErrorMessage' => 'Forbidden',  (=default) 
            ),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow everybody to display or click the ads
                'actions' => array('process'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('graph'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Processes the stats (clicks, views)
     */
    public function actionProcess() {
        $db_connection = Yii::app()->db;
        $campaignrun_changes = Array(); //This array will store the new views and clicks for each campaignrun
        /* 1) Processing the views */
        //We only get the unique views
        $sql = 'SELECT `campaignrun_id`, COUNT(*) AS `count`, `time` FROM ( SELECT `campaignrun_id`, DATE_FORMAT(`time`, \'%Y-%m-%d %H:00:00\') AS `time` FROM `temp_view` GROUP BY `campaignrun_id`, `binary_ip`, DATE_FORMAT(`time`, \'%Y-%m-%d %H:00:00\')) AS `tmp` GROUP BY campaignrun_id, time';
        $new_views = $db_connection->createCommand($sql)->queryAll();
        $this->truncateArchiveTable('temp_view'); //We remove all the processed data
        //We add them to the aggregated view stats
        $db_transaction = $db_connection->beginTransaction(); //We create a transaction to facilitate the inserts
        foreach ($new_views as $new_view) { //For each row, we add the info to the final stats table and record it in the array
            $sql = 'INSERT INTO `stats_views`(`campaignrun_id`, `time`, `amount`) VALUES(:campaignrunid, :time, :amount) ON DUPLICATE KEY UPDATE `amount` = `amount` + :amount';
            $db_connection->createCommand($sql)->bindValues(array(':campaignrunid' => $new_view['campaignrun_id'], ':time' => $new_view['time'], ':amount' => $new_view['count']))->execute();
            if (!isset($campaignrun_changes[$new_view['campaignrun_id']]['views']))
                $campaignrun_changes[$new_view['campaignrun_id']]['views'] = 0;
            $campaignrun_changes[$new_view['campaignrun_id']]['views'] += $new_view['count']; //We add the new views
        }
        $db_transaction->commit(); //We commit those inserts and updates

        /* 2) Processing the clicks */
        //We only get the unique clicks
        $sql = 'SELECT `campaignrun_id`, COUNT(*) AS `count`, `time` FROM ( SELECT `campaignrun_id`, DATE_FORMAT(`time`, \'%Y-%m-%d %H:00:00\') AS `time` FROM `temp_click` GROUP BY `campaignrun_id`, `binary_ip`, DATE_FORMAT(`time`, \'%Y-%m-%d %H:00:00\')) AS `tmp` GROUP BY campaignrun_id, time';
        $new_clicks = $db_connection->createCommand($sql)->queryAll();
        $this->truncateArchiveTable('temp_click'); //We remove all the processed data
        //We add them to the aggregated click stats
        $db_transaction = $db_connection->beginTransaction(); //We create a transaction to facilitate the inserts
        foreach ($new_clicks as $new_click) { //For each row, we add the info to the final stats table and record it in the array
            $sql = 'INSERT INTO `stats_clicks`(`campaignrun_id`, `time`, `amount`) VALUES(:campaignrunid, :time, :amount) ON DUPLICATE KEY UPDATE `amount` = `amount` + :amount';
            $db_connection->createCommand($sql)->bindValues(array(':campaignrunid' => $new_click['campaignrun_id'], ':time' => $new_click['time'], ':amount' => $new_click['count']))->execute();
            if (!isset($campaignrun_changes[$new_click['campaignrun_id']]['clicks']))
                $campaignrun_changes[$new_click['campaignrun_id']]['clicks'] = 0;
            $campaignrun_changes[$new_click['campaignrun_id']]['clicks'] += $new_click['count']; //We add the new views
        }
        $db_transaction->commit(); //We commit those inserts and updates

        /* 3) Adding the changes to the cached stats in the Campaignrun table */
        $db_transaction = $db_connection->beginTransaction();
        foreach ($campaignrun_changes as $id => $value) {
            $views = (isset($value['views']) ? $value['views'] : 0);
            $clicks = (isset($value['clicks']) ? $value['clicks'] : 0);
            $sql = 'UPDATE `campaignrun` SET `stats_views` = `stats_views` + :views, `stats_clicks` = `stats_clicks` + :clicks WHERE `id` = :id';
            $db_connection->createCommand($sql)->bindValues(array(':id' => $id, ':views' => $views, ':clicks' => $clicks))->execute();
        }
        $db_transaction->commit();

        $this->disableFinishedCampaigns(); //We check if we have to disable some campaigns
    }

    /**
     * Executed after the stats, processes the finished campaigns in order to disable them
     */
    private function disableFinishedCampaigns() {
        Yii::import('ext.flushable.FlushableDependency'); //Extension to flush the cache when needed

        $db_connection = Yii::app()->db;

        //$sql = 'UPDATE `campaignrun` SET `status` = \'disabled\', `time_disabled` = :now WHERE `status` = \'running\' AND (( `paytype` = \'click\' AND `stats_clicks` >= `paytype_amount` ) OR ( `paytype` = \'view\' AND `stats_views` >= `paytype_amount` ) OR ( `paytype` = \'duration\' AND :now >= `time_enabled` + INTERVAL `paytype_amount` DAY ))';
        $sql = 'SELECT `id`, `adspace_id` FROM `campaignrun` WHERE `status` = \'running\' AND (( `paytype` = \'click\' AND `stats_clicks` >= `paytype_amount` ) OR ( `paytype` = \'view\' AND `stats_views` >= `paytype_amount` ) OR ( `paytype` = \'duration\' AND NOW() >= `time_enabled` + INTERVAL `paytype_amount` DAY ))';
        $rows = $db_connection->createCommand($sql)->bindValues(array(':now' => date('Y-m-d H:i:s')))->queryAll();
        $db_transaction = $db_connection->beginTransaction();
        foreach ($rows as $row) {
            //We set the campaignrun as disabled and put its disabled_time
            $sql = 'UPDATE `campaignrun` SET `status`=\'disabled\', `time_disabled` = :now WHERE `id` = :id';
            $db_connection->createCommand($sql)->bindValues(array(':id' => $row['id'], ':now' => date('Y-m-d H:i:s')))->execute();

            //We invalidate the relevant caches
            FlushableDependency::flushItem($row['adspace_id'], 'Adspace_Campaignruns'); //List of enabled campaignruns for the relevant adspace
            FlushableDependency::flushItem($row['id'], 'Campaignrun'); //Campaignrun
        }
        $db_transaction->commit();
    }

    public function actionGraph($campaign_id = -1, $campaignrun_id = -1) {
        if ($campaign_id == -1 && $campaignrun_id == -1) //No parameters given
            throw new CHttpException(404, 'The requested page does not exist.');
        
        //Owner check
        $ownercampaign_id = $campaign_id;
        if ($campaignrun_id != -1) {
            $dependency = new FlushableDependency($id, 'Campaignrun'); //Dependency for this campaignrun
            $campaignrun = Campaignrun::model()->with('campaign')->cache(1000, $dependency)->findByPk($id);
            $ownercampaign_id = $campaignrun->campaign_id;
        }
        $campaign = Campaign::model()->findByPk($ownercampaign_id);
        if ($campaign == null || $campaign->user_id != Yii::app()->user->id) {
            throw new CHttpException(404, Yii::t('app', 'The selected ad campaign doesn\'t exist or doesn\'t belongs to you!'));
        }
        
        if ($campaign_id != -1) {
            $sql = 'SELECT * FROM
(SELECT `stats_clicks`.`time`, SUM(`stats_clicks`.`amount`) AS `amount`, "clicks" AS `type` FROM `campaignrun`
INNER JOIN `stats_clicks`
ON `campaignrun`.`id` = `stats_clicks`.`campaignrun_id`
WHERE `campaign_id` = :campaignid
GROUP BY `stats_clicks`.`time`
UNION
SELECT `stats_views`.`time`, SUM(`stats_views`.`amount`) AS `amount`, "views" AS `type` FROM `campaignrun`
INNER JOIN `stats_views`
ON `campaignrun`.`id` = `stats_views`.`campaignrun_id`
WHERE `campaign_id` = :campaignid
GROUP BY `stats_views`.time
) AS `tmp`
ORDER BY `time` ASC';
            $data = Yii::app()->db->createCommand($sql)->bindValue(':campaignid', $campaign_id)->queryAll();
        } elseif ($campaignrun_id != -1) {
            $sql = 'SELECT * FROM
(SELECT `stats_clicks`.`time`, SUM(`stats_clicks`.`amount`) AS `amount`, "clicks" AS `type` FROM `campaignrun`
INNER JOIN `stats_clicks`
ON `campaignrun`.`id` = `stats_clicks`.`campaignrun_id`
WHERE `campaignrun`.`id` = :campaignrunid
GROUP BY `stats_clicks`.`time`
UNION
SELECT `stats_views`.`time`, SUM(`stats_views`.`amount`) AS `amount`, "views" AS `type` FROM `campaignrun`
INNER JOIN `stats_views`
ON `campaignrun`.`id` = `stats_views`.`campaignrun_id`
WHERE `campaignrun`.`id` = :campaignrunid
GROUP BY `stats_views`.time
) AS `tmp`
ORDER BY `time` ASC';
            $data = Yii::app()->db->createCommand($sql)->bindValue(':campaignrunid', $campaignrun_id)->queryAll();
        }

        $finaldata = Array(Array(), Array());
        if (isset($data[0])) {
            $previoustime = Array($data[0]['time'], $data[0]['time']); //Ordered by time, so first row : earliest
            foreach ($data as $row) {
                if ($row['type'] == 'views') {
                    $arrayid = 0;
                } else {
                    $arrayid = 1;
                }

                //We add the points with a 0 value to fill the gaps with no data
                if (strtotime($previoustime[$arrayid]) < strtotime($row['time'] . ' - 1 hour')) {
                    //Some points missing (0 views or clicks)
                    do {
                        $previoustime[$arrayid] = date('Y/m/d H:i:s', strtotime($previoustime[$arrayid] . ' +1 hour'));
                        $finaldata[$arrayid][] = Array($previoustime[$arrayid], (int) 0);
                    } while (strtotime($previoustime[$arrayid]) < strtotime($row['time'] . ' - 1 hour'));
                }

                $finaldata[$arrayid][] = Array($row['time'], (int) $row['amount']);
                $previoustime[$arrayid] = $row['time'];
            }
        }

        echo json_encode($finaldata);

        //echo '[["2008-08-12 4:00PM",4],["2008-09-12 4:00PM",6],["2008-10-12 4:00PM",5],["2008-11-12 4:00PM",9],["2008-12-12 4:00PM",8]]';
        exit();
    }

    /**
     * Replicates the TRUNCATE TABLE functionnality for a MySQL ARCHIVE table
     * @param type $table_name
     */
    private function truncateArchiveTable($table_name) {
        $sql_create = Yii::app()->db->createCommand('SHOW CREATE TABLE `' . $table_name . '`')->queryAll();
        $sql_create = $sql_create[0]['Create Table'];
        Yii::app()->db->createCommand('DROP TABLE `' . $table_name . '`')->execute();
        Yii::app()->db->createCommand($sql_create)->execute();
    }

}