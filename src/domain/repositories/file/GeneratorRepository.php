<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\extension\scenario\helpers\ScenarioHelper;
use yii2lab\domain\repositories\BaseRepository;

class GeneratorRepository extends BaseRepository {
	
	const GENERATOR_DIR = 'yii2module\vendor\domain\commands\generators\\';
	const INSTALL_DIR = 'yii2module\vendor\domain\commands\install\\';
	
	/**
	 * @param $config
	 * @param $name
	 *
	 * @return mixed
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function generate($config, $name) {
		$config['class'] = self:: GENERATOR_DIR. $name;
		return ScenarioHelper::run($config);
	}
	
	/**
	 * @param $config
	 * @param $name
	 *
	 * @return mixed
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function install($config, $name) {
		$config['class'] = self::INSTALL_DIR . $name;
		return ScenarioHelper::run($config);
	}
	
}
