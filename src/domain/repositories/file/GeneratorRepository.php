<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\misc\helpers\CommandHelper;

class GeneratorRepository extends BaseRepository {
	
	const GENERATOR_DIR = 'yii2module\vendor\domain\generators\\';
	const INSTALL_DIR = 'yii2module\vendor\domain\install\\';
	
	public function generate($config, $name) {
		return CommandHelper::run($config, self::GENERATOR_DIR . $name);
	}
	
	public function install($config, $name) {
		return CommandHelper::run($config, self::INSTALL_DIR . $name);
	}
	
}
