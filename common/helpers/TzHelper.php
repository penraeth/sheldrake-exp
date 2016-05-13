<?php
namespace common\helpers;

use Yii;

class TzHelper
{

	public static function convertLocal($date, $fmt='M j, Y / H:i:s T') {
		if  (!$date) {
			return '-';
		} else if (isset(Yii::$app->session['timezone'])  &&  Yii::$app->session['timezone']) {
			date_default_timezone_set( Yii::$app->session['timezone'] );
			return date($fmt, strtotime("$date ".Yii::$app->params['serverTimezone'] ));
		} else {
			return date($fmt, strtotime("$date"));
		}
	}

}
