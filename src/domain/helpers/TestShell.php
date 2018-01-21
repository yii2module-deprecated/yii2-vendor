<?php

namespace yii2module\vendor\domain\helpers;

use yii2lab\helpers\yii\FileHelper;
use yii2lab\misc\exceptions\ShellException;
use yii2lab\misc\helpers\BaseShell;
use yii2mod\helpers\ArrayHelper;

class TestShell extends BaseShell {
	
	public function pullWithInfo($remote = null) {
		$result = $this->extractFromCommand("codeception run", 'trim');
		$result = implode(PHP_EOL, $result);
		$result = trim($result);
		return $result;
	}
	
}
