<?php

namespace yii2module\vendor\domain\commands;

use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\extension\console\helpers\CopyFiles;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\yii\helpers\FileHelper;

abstract class Base extends BaseScenario {
	
	public $data;
	
	protected function insertConfig($file, $part, $name, $value) {
		if($this->isExistsConfig($file, $part, $name)) {
			return;
		}
		$newLine = "\t\t'{$name}' => {$value},";
		$search = "'{$part}' => [";
		$this->insertLineConfig($file, $search, $newLine);
	}
	
	protected function isExistsConfig($file, $part, $name) {
		$fileName = Yii::getAlias($file);
		$all = include($fileName);
		$config = ArrayHelper::getValue($all, $part, []);
		return isset($config[$name]);
	}
	
	protected function insertLineConfig($fileAlias, $search, $newLine) {
		$fileName = Yii::getAlias($fileAlias);
		$content = FileHelper::load($fileName);
		$content = str_replace($search, $search . "\n" . $newLine, $content);
		FileHelper::save($fileName, $content);
	}
	
	protected function getBaseAlias($data) {
		$alias = '@' . $data['owner'] . SL .$data['nameAlias'];
		try {
			Yii::getAlias($alias);
		} catch(InvalidArgumentException $e) {
			Yii::setAlias($alias, Yii::getAlias('@vendor' . SL . $data['owner'] . SL . 'yii2-' . $data['name'] . SL . 'src'));
		}
		return $alias;
	}
	
	protected function copyDir($data, $dirName) {
		$from = $this->packageDirMini('yii2module', 'vendor') . '/src/domain/data/' . $dirName;
		$to = $this->packageDirMini($data['owner'], $data['name']) . '/' . $dirName;
		$copy = new CopyFiles;
		$copy->copyAllFiles($from, $to);
		$files = $copy->getFileList($to);
		$files = $this->addDirInFileList($files, $dirName);
		$this->replaceFileContentList($data, $files);
		return $files;
	}
	
	protected function copyFile($data, $fileName) {
		$sourceFileName = $this->packageFile('yii2module', 'vendor', 'src/domain/data/' . $fileName);
		$targetFileName = $this->packageFile($data['owner'], $data['name'], $fileName);
		FileHelper::copy($sourceFileName, $targetFileName);
		$this->replaceFileContent($data, $fileName);
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
	
	protected function packageFile($owner, $name, $fileName) {
		return $this->packageDir($owner, $name) . DS . $fileName;
	}
	
	protected function packageDir($owner, $name) {
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
