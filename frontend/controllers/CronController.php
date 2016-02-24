<?php
 
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
 
/**
 * Test controller
 */
class CronController extends Controller {
 
    public function actionPdf() {
        $members = User::findAll(['status' => User::STATUS_ACTIVE]);
        
        foreach ($members as $member) {
            $pdfs_listing = Yii::$app->PdfsComponent->getAvailablePDFs($member);
            
            if (!empty($pdfs_listing)) {
                foreach ($pdfs_listing as $pdf) {
                    echo $pdf->name . ' is available for user: ' . $member->firstname . ' ' . $member->lastname . '<br/>';
                }
            } else {
                echo 'No Pdfs were found for user: ' . $member->firstname . ' ' . $member->lastname . '<br/>';
            }
        }
    }
}