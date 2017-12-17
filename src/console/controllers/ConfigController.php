<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\console\helpers\Output;
use yii2lab\console\yii\console\Controller;
use yii2lab\store\Store;

class ConfigController extends Controller
{
	
	public function actionToDev()
	{
		$fileName = ROOT_DIR . DS . 'composer.json';
		$store = new Store('json');
		$config = $store->load($fileName);
		$config['require'] = $this->toDev($config['require']);
		$config['require-dev'] = $this->toDev($config['require-dev']);
		$store->save($fileName, $config);
		Output::block('Dev');
	}
	
	private function toDev($config) {
		$ownerList = Yii::$app->vendor->generator->ownerList;
		foreach($config as $fullName => &$version) {
			$arr = explode(SL, $fullName);
			if(count($arr) > 1) {
				list($owner, $name) = $arr;
				if(in_array($owner, $ownerList)) {
					$version = 'dev-master';
				}
			}
		}
		return $config;
	}
	
}
