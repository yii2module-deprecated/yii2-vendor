<?php

namespace yii2module\vendor\domain\helpers;

use yii2lab\misc\exceptions\ShellException;
use yii2lab\misc\helpers\BaseShell;

class TestShell extends BaseShell {
	
	public function codeceptionRun() {
		try {
		    $result = $this->extractFromCommand(
		        \Yii::getAlias('@vendor') . DS . 'codeception' . DS . 'base' . DS .'codecept run',
		        'trim'
		    );
			$result = implode(PHP_EOL, $result);
		} catch(ShellException $e) {
			$result = 'error';
		}
		$result = trim($result);
		return $result;
	}
	
}
