<?php

namespace yii2module\vendor\domain\services;

use yii2lab\console\helpers\CopyFiles;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\helpers\yii\FileHelper;

class GeneratorService extends ActiveBaseService {

	public $author;
	public $email;
	
	public function generate($owner, $name) {
		$config = [
			'path' => 'vendor' . SL . $owner . SL . 'yii2-' . $name,
			'full_name' => $owner . SL . $name,
		];
		$copy = new CopyFiles;
		$copy->copyAllFiles('vendor/yii2module/yii2-vendor/src/domain/data', $config['path']);
		$files = $copy->getFileList($config['path']);
		foreach($files as $fileName1) {
			$fileName = $config['path'] . DS . $fileName1;
			$fileData = FileHelper::load($fileName);
			$fileData = str_replace([
				'{name}',
				'{owner}',
				'{author}',
				'{email}',
				'{year}',
			], [
				$name,
				$owner,
				$this->author,
				$this->email,
				date('Y'),
			], $fileData);
			FileHelper::save($fileName, $fileData);
		}
	}
	
}
