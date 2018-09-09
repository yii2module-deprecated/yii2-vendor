<?php

namespace yii2module\vendor\domain\commands\generators;

use yii2lab\extension\store\Store;
use yii2module\vendor\domain\commands\Base;

class Package extends Base {

	public function run() {
		$this->generateComposer($this->data);
		$this->generateGitIgnore($this->data);
	}
	
	protected function generateComposer($data) {
		$config = $this->generateComposerConfig($data);
		$fileName = $this->packageFile($data['owner'], $data['name'], 'composer.json');
		$store = new Store('json');
		$store->save($fileName, $config);
	}
	
	protected function generateGitIgnore($data) {
		$this->copyFile($data, '.gitignore');
	}
	
	protected function generateComposerConfig($data) {
		$config['name'] = $data['owner'] . SL . 'yii2-' . $data['name'];
		$config['type'] = 'yii2-extension';
		$config['keywords'] = ['yii2', $data['name']];
		$config['license'] = $data['license'];
		$config['authors'][] = [
			'name' => $data['author'],
			'email' => $data['email'],
		];
		$config['minimum-stability'] = 'dev';
		$config['autoload']['psr-4'][$data['owner'] . '\\' . $data['nameAlias'] . '\\'] = 'src';
		$config['autoload']['psr-4'][$data['owner'] . '\\' . $data['nameAlias'] . '\\tests\\'] = 'tests';
		$config['require'] = [
			'yiisoft/yii2' => '*',
			'php' => '>=5.4.0',
		];
		return $config;
	}
	
}
