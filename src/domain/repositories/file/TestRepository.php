<?php

namespace yii2module\vendor\domain\repositories\file;

use yii2lab\domain\repositories\BaseRepository;
use yii2module\vendor\domain\entities\TestEntity;
use yii2module\vendor\domain\helpers\TestShell;

class TestRepository extends BaseRepository {

    /**
     * @param $directory
     * @return TestEntity
     */
	public function run($directory) {
		$repo = new TestShell($directory);
		$result = $repo->codeceptionRun();
		if(strpos($result, 'No tests executed!')) {
            $data = [
				'tests' => 0,
				'assertions' => 0,
				'text' => $result,
			];
		} elseif(preg_match('#OK \((\d+) tests?, (\d+) assertions?\)#', $result, $matches)) {
            $data = [
				'tests' => $matches[1],
				'assertions' => $matches[2],
				'text' => $result,
			];
		} elseif(preg_match('#ERRORS!\s+(Tests:.+)$#', $result, $matchesParent)) {
			$parts = explode(',', $matches[1]);
            preg_match('#Tests?: (\d+), Assertions?: (\d+), Errors?: (\d+)#', $result, $matches);
            $data = [
                'tests' => intval($matches[1]),
                'assertions' => intval($matches[2]),
                'error' => intval($matches[3]),
				'text' => $result,
			];
            if(preg_match('#Failures?: (\d+)#', $result, $matches)) {
	            $data['error'] = $data['error'] + $matches[1];
            }
		} else {
            $data = [
                'tests' => 0,
                'assertions' => 0,
                'error' => 1,
                'text' => $result,
            ];
        }
        $data['directory'] = $directory;
		return $this->forgeEntity($data);
	}
	
}
