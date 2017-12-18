<?php

namespace yii2module\vendor\domain\install;

use Yii;
use yii2lab\misc\interfaces\CommandInterface;
use yii2lab\store\Store;
use yii2module\vendor\domain\generators\Base;

class Package extends Base implements CommandInterface {

	public function run() {
		$config = $this->loadConfig();
		foreach($config['autoload']['psr-4'] as $alias => $path) {
			$alias = str_replace('\\', '/', $alias);
			$alias = trim($alias, '/');
			try {
				Yii::getAlias('@' . $alias);
			} catch(\yii\base\InvalidParamException $e) {
				$this->makeConfig('@' . $alias, '@vendor/' . $this->data['full_name'] . SL . $path);
			}
		}
	}
	
	protected function makeConfig($alias, $full_name) {
		$aliases = config('aliases', []);
		if(isset($aliases[$alias])) {
			return;
		}
		$newLine = "\t\t'{$alias}' => '{$full_name}',";
		$search = "'aliases' => [";
		$this->insertLineConfig('@common/config/main.php', $search, $newLine);
	}
	
	protected function loadConfig() {
		$composerConfig = $this->packageFile($this->data['owner'], $this->data['name'], 'composer.json');
		$store = new Store('json');
		$config = $store->load($composerConfig);
		return $config;
	}
}
