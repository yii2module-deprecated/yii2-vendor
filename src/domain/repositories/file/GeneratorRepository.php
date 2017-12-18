<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\console\helpers\CopyFiles;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\store\Store;

class GeneratorRepository extends BaseRepository {

	public function generateComposer($data) {
		$config['name'] = $data['owner'] . SL . 'yii2-' . $data['name'];
		$config['type'] = 'yii2-extension';
		$config['keywords'] = ['yii2', $data['name']];
		$config['license'] = $data['license'];
		$config['authors'][] = [
			'name' => $data['author'],
			'email' => $data['email'],
		];
		$config['minimum-stability'] = 'dev';
		$config['autoload']['psr-4'][$data['owner'] . '\\' . $data['name'] . '\\'] = 'src';
		$config['require'] = [
			'yiisoft/yii2' => '*',
			'php' => '>=5.4.0',
		];
		$fileName = $this->packageFile($data['owner'], $data['name'], 'composer.json');
		$store = new Store('json');
		$store->save($fileName, $config);
	}
	
	public function generateGitIgnore($data) {
		$this->copyFile($data, '.gitignore');
	}
	
	public function generateLicense($data) {
		$this->copyFile($data, 'LICENSE');
	}
	
	public function generateGuide($data) {
		$this->copyDir($data, 'guide');
	}
	
	public function generateReadme($data) {
		$this->copyFile($data, 'README.md');
	}
	
	public function generateTest($data) {
		$this->copyDir($data, 'tests');
		$this->copyFile($data, 'codeception.yml');
	}
	
	private function replaceFileContent($data, $fileName) {
		$sourceFileName = $this->packageFile('yii2module', 'vendor', 'src/domain/data/' . $fileName);
		$targetFileName = $this->packageFile($data['owner'], $data['name'], $fileName);
		$sourceContent = FileHelper::load($sourceFileName);
		$targetContent = $this->replaceData($data, $sourceContent);
		FileHelper::save($targetFileName, $targetContent);
	}
	
	private function addDirInFileList($files, $dirName) {
		foreach($files as &$fileName1) {
			$fileName1 = $dirName . SL . $fileName1;
		}
		return $files;
	}
	
	private function replaceFileContentList($data, $files) {
		foreach($files as $fileName1) {
			$this->replaceFileContent($data, $fileName1);
		}
	}
	
	private function copyDir($data, $dirName) {
		$from = $this->packageDirMini('yii2module', 'vendor') . '/src/domain/data/' . $dirName;
		$to = $this->packageDirMini($data['owner'], $data['name']) . '/' . $dirName;
		$copy = new CopyFiles;
		$copy->copyAllFiles($from, $to);
		$files = $copy->getFileList($to);
		$files = $this->addDirInFileList($files, $dirName);
		$this->replaceFileContentList($data, $files);
		return $files;
	}
	
	private function copyFile($data, $fileName) {
		$sourceFileName = $this->packageFile('yii2module', 'vendor', 'src/domain/data/' . $fileName);
		$targetFileName = $this->packageFile($data['owner'], $data['name'], $fileName);
		FileHelper::copy($sourceFileName, $targetFileName);
		$this->replaceFileContent($data, $fileName);
	}
	
	private function packageFile($owner, $name, $fileName) {
		return $this->packageDir($owner, $name) . DS . $fileName;
	}
	
	private function packageDir($owner, $name) {
		return ROOT_DIR . DS . $this->packageDirMini($owner, $name);
	}
	
	private function packageDirMini($owner, $name) {
		return VENDOR . DS . $owner . DS . 'yii2-' . $name;
	}
	
	private function replaceData($list, $data) {
		$search = array_keys($list);
		foreach($search as &$searchItem) {
			$searchItem = '{' . $searchItem . '}';
		}
		$replace = array_values($list);
		$data = str_replace($search, $replace, $data);
		return $data;
	}
}
