<?php

namespace yii2module\vendor\domain\commands\generators;

use Yii;
use yii\base\BaseObject;
use yii2lab\console\helpers\CopyFiles;
use yii2lab\helpers\yii\FileHelper;

class Base extends BaseObject {
	
	public $data;
	
	protected function insertLineConfig($fileAlias, $search, $newLine) {
		$fileName = Yii::getAlias($fileAlias);
		$content = FileHelper::load($fileName);
		$content = str_replace($search, $search . "\n" . $newLine, $content);
		FileHelper::save($fileName, $content);
	}
	
	protected function getBaseAlias($data) {
		$alias = '@' . $data['owner'] . SL .$data['name'];
		try {
			$path = Yii::getAlias($alias);
		} catch(\yii\base\InvalidParamException $e) {
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
