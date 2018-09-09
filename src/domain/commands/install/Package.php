<?php

namespace yii2module\vendor\domain\commands\install;

use Yii;
use yii2lab\extension\store\Store;
use yii2module\vendor\domain\commands\Base;

class Package extends Base {

	public function run() {
		$config = $this->loadConfig();
		foreach($config['autoload']['psr-4'] as $alias => $path) {
			$alias = str_replace('\\', '/', $alias);
			$alias = trim($alias, '/');
			try {
				Yii::getAlias('@' . $alias);
			} catch(\InvalidArgumentException $e) {
				$this->makeConfig('@' . $alias, '@vendor/' . $this->data['full_name'] . SL . $path);
			}
		}
	}
	
	protected function makeConfig($alias, $full_name) {
		$value = "'{$full_name}'";
		$file = '@common/config/main.php';
		$this->insertConfig($file, 'aliases', $alias, $value);
	}
	
	protected function loadConfig() {
		$composerConfig = $this->packageFile($this->data['owner'], $this->data['name'], 'composer.json');
		$store = new Store('json');
		$config = $store->load($composerConfig);
		return $config;
	}
}
