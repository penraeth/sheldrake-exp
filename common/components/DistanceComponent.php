<?php
namespace common\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidConfigException;


class DistanceComponent extends Object
{
	private $earth_radius = [
		'mi' => 3959,
		'km' => 6371,
		'ft' => 20902464,
	];
	
	
	public function calculate($lat1, $lon1, $lat2, $lon2, $unit='ft') {
		if ($lat1 == '' || $lon1 == '' || $lat2 == '' || $lon2 == '') {
			return -1;
		}
		if ($lat1== null || $lon1== null || $lat2== null || $lon2== null) {
			return -1;
		}
	
		// convert degrees to radians
		$lat1 = $lat1 * pi()/180;
		$lon1 = $lon1 * pi()/180;
		$lat2 = $lat2 * pi()/180;
		$lon2 = $lon2 * pi()/180;
		
		// determine coordinates on sphere
		$x1 = cos($lon1) * cos($lat1);
		$y1 = sin($lon1) * cos($lat1);
		$z1 =              sin($lat1);
		$x2 = cos($lon2) * cos($lat2);
		$y2 = sin($lon2) * cos($lat2);
		$z2 =              sin($lat2);
		
		// good ol' pythagoras to the rescue
		$chord = sqrt(  pow(($x1-$x2), 2) + pow(($y1-$y2), 2) + pow(($z1-$z2), 2)  );
		
		return $chord * $this->earth_radius[$unit];
	}
	
}
