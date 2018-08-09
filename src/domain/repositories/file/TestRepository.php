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
		} elseif(preg_match('#ERRORS#', $result)) {
            preg_match('#Tests?: (\d+), Assertions?: (\d+), Errors?: (\d+).#', $result, $matches);
		    $data = [
                'tests' => $matches[1],
                'assertions' => $matches[2],
                'error' => $matches[3],
				'text' => $result,
			];
		} else {
            //prr($result);
            $data = [
                'text' => $result,
            ];
        }
		return $this->forgeEntity($data);
	}
	
}
