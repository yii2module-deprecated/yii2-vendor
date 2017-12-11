<?php

namespace yii2module\vendor\domain\services;

use yii2lab\console\helpers\CopyFiles;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\helpers\yii\FileHelper;

class GeneratorService extends ActiveBaseService {

    const DATA_PATH = 'vendor/yii2module/yii2-vendor/src/domain/data';

	public $author;
	public $email;
	public $ownerList;

	public function generate($owner, $name) {
        $path = $this->getFullName($owner, $name);
        $files = $this->copy($path);
		foreach($files as $fileName1) {
			$fileName = $path . DS . $fileName1;
			$this->replace($name, $owner, $fileName);
		}
	}

	private function getFullName($owner, $name) {
        return 'vendor' . SL . $owner . SL . 'yii2-' . $name;
    }

    private function copy($to) {
        $from = self::DATA_PATH;
        $copy = new CopyFiles;
        $copy->copyAllFiles($from, $to);
        $files = $copy->getFileList($to);
        return $files;
    }

    private function replace($name, $owner, $fileName) {
        $fileData = FileHelper::load($fileName);
        $fileData = $this->replaceData($name, $owner, $fileData);
        FileHelper::save($fileName, $fileData);
    }

	private function replaceData($name, $owner, $data) {
        $list = [
            '{name}' => $name,
            '{owner}' => $owner,
            '{author}' => $this->author,
            '{email}' => $this->email,
            '{year}' => date('Y'),
        ];
        $search = array_keys($list);
        $replace = array_values($list);
        $data = str_replace($search, $replace, $data);
        return $data;
    }
	
}
