<?php
namespace common\helpers;

use Yii;

class TzHelper
{

	public static function convertLocal($date, $fmt='M j, Y / H:i:s T') {
		if (isset(Yii::$app->session['timezone'])  &&  Yii::$app->session['timezone']) {
			date_default_timezone_set( Yii::$app->session['timezone'] );
			return date($fmt, strtotime("$date America/Los_Angeles"));
		} else {
			return date($fmt, strtotime("$date"));
		}
	}

}
