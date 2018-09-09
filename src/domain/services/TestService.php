<?php

namespace yii2module\vendor\domain\services;

use Yii;
use yii2lab\domain\services\ActiveBaseService;
use yii2lab\extension\yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii2module\vendor\domain\repositories\file\GitRepository;
use yii2module\vendor\domain\repositories\file\TestRepository;

/**
 * Class TestService
 * @package yii2module\vendor\domain\services
 *
 * @property TestRepository $repository
 */
class TestService extends ActiveBaseService {
	
	public $ignore;
	public $aliases;
	
	public function run($directory) {
		return $this->repository->run($directory);
	}
	
	public function directoriesWithHasTestAll() {
		$package = $this->directoriesWithHasForPackage();
		$project = $this->directoriesWithHasTestForProject();
		return ArrayHelper::merge($project, $package);
	}
	
	public function directoriesWithHasForPackage() {
		$collection = Yii::$domain->vendor->info->allWithHasTest();
		$result = [];
		foreach($collection as $item) {
			$result[] = [
				'name' => $item->package,
				'directory' => $item->directory,
			];
		}
		return $result;
	}
	
	public function directoriesWithHasTestForProject() {
		$collection = [];
		if(empty($this->aliases)) {
			return [];
		}
		foreach($this->aliases as $alias) {
			$directory = Yii::getAlias($alias);
		    if(FileHelper::has($directory)) {
                $collection[] = [
                    'name' => trim($alias, '@/'),
                    'directory' => $directory,
                ];
            }
		}
		return $collection;
	}
	
}
