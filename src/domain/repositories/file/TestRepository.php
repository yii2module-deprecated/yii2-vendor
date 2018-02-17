<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2module\vendor\domain\helpers\TestShell;

class TestRepository extends BaseRepository {
	
	public function run($directory) {
		$repo = new TestShell($directory);
		$result = $repo->codeceptionRun();
		if(preg_match('#OK \((\d+) tests?, (\d+) assertions?\)#', $result, $matches)) {
			return [
				'result' => true,
				'testCount' => $matches[1],
				'assertionCount' => $matches[2],
				'text' => $result,
			];
		} else {
			return [
				'result' => false,
				'text' => $result,
			];
		}
	}
	
}
