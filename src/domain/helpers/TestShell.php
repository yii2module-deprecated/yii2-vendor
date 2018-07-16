<?php

namespace yii2module\vendor\domain\helpers;

use yii2lab\extension\shell\base\BaseShell;
use yii2lab\extension\shell\exceptions\ShellException;

class TestShell extends BaseShell {
	
	public function codeceptionRun() {
		try {
		    $path = \Yii::getAlias('@vendor') . 
		    DIRECTORY_SEPARATOR . 'codeception' . 
		    DIRECTORY_SEPARATOR . 'base' . 
		    DIRECTORY_SEPARATOR . 'codecept';
		    $result = $this->extractFromCommand($path . ' run', 'trim');
			$result = implode(PHP_EOL, $result);
		} catch(ShellException $e) {
			$result = 'error';
		}
		$result = trim($result);
		return $result;
	}
	
}
