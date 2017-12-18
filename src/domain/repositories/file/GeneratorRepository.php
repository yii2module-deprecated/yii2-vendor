<?php

namespace yii2module\vendor\domain\repositories\file;

use Yii;
use yii\web\ServerErrorHttpException;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\misc\interfaces\CommandInterface;

class GeneratorRepository extends BaseRepository {
	
	public function runGenerator($config, $name) {
		$generatorNamespace = 'yii2module\vendor\domain\generators\\';
		$config['class'] = $generatorNamespace . $name;
		$generator = Yii::createObject($config);
		if($generator instanceof CommandInterface) {
			$generator->run();
		} else {
			throw new ServerErrorHttpException('Generator not be instance of CommandInterface');
		}
	}
	
}
