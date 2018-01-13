<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\designPattern\command\helpers\CommandHelper;

class GeneratorRepository extends BaseRepository {
	
	const GENERATOR_DIR = 'yii2module\vendor\domain\commands\generators\\';
	const INSTALL_DIR = 'yii2module\vendor\domain\commands\install\\';
	
	public function generate($config, $name) {
		$config['class'] = self:: GENERATOR_DIR. $name;
		return CommandHelper::run($config);
	}
	
	public function install($config, $name) {
		$config['class'] = self::INSTALL_DIR . $name;
		return CommandHelper::run($config);
	}
	
}
