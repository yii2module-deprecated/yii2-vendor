<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\extension\console\helpers\input\Enter;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\package\helpers\PackageHelper;

class PackageController extends Controller
{
	
	public function init() {
		parent::init();
		Output::line();
	}

	public function actionDownload()
	{
		$group = Enter::display('Enter vendor name');
        $package = Enter::display('Enter package name');
        PackageHelper::forge($group, $package);
        Output::block('Package downloaded and installed!');
	}

}
