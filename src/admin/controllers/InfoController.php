<?php

namespace yii2module\vendor\admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii2lab\domain\data\Query;
use yii2lab\domain\web\ActiveController as Controller;
use yii2lab\extension\web\helpers\Behavior;
use yii2module\vendor\domain\entities\CommitEntity;
use yii2module\vendor\domain\entities\RepoEntity;
use yii2module\vendor\domain\enums\VendorPermissionEnum;
use yii2module\vendor\domain\helpers\VersionHelper;

class InfoController extends Controller {

	public $service = 'vendor.info';
	public $titleName = 'package';
	
	public function behaviors()
	{
		return [
			'access' => Behavior::access(VendorPermissionEnum::MANAGE),
		];
	}
	
	public function actions() {
		$actions = parent::actions();
		unset($actions['index']);
		unset($actions['view']);
		return $actions;
	}

	public function actionIndex() {
		return $this->render('index');
	}
	
	public function actionView($id) {
		$query = Query::forge();
		$query->with('tags');
		$query->with('commits');
		$query->with('required_packages');
		$query->with('has_changes');
		$query->with('remote_url');
		
		/** @var RepoEntity $entity */
		$entity = \App::$domain->vendor->info->oneById($id, $query);
		
		//prr($entity->remote_url,1,1);
		
		$versionVariations = VersionHelper::getVariations($entity);
		
		return $this->render('view', [
			'entity' => $entity,
			'versionVariations' => $versionVariations,
		]);
	}
	
	public function actionList() {
		$query = Query::forge();
		$query->with('tags');
		$query->with('commits');
		$query->with('branch');
		$query->with('has_readme');
		$query->with('has_changelog');
		$query->with('has_guide');
		$query->with('has_license');
		$query->with('has_test');
		$collection = \App::$domain->vendor->info->all($query);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => false,
		]);
		return $this->render('list', ['dataProvider' => $dataProvider]);
	}
	
	public function actionListForRelease() {
		$query = Query::forge();
		/*$query->with('tags');
		$query->with('commits');
		$query->with('branch');
		$query->with('has_readme');
		$query->with('has_changelog');
		$query->with('has_guide');
		$query->with('has_license');
		$query->with('has_test');*/
		$collection = \App::$domain->vendor->info->allForRelease($query);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => [
				'pageSize' => 1000,
			],
		]);
		return $this->render('list_for_release', ['dataProvider' => $dataProvider]);
	}
	
	public function actionListChanged() {
		$collection = \App::$domain->vendor->info->allChanged();
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => [
				'pageSize' => 1000,
			],
		]);
		return $this->render('list_changed', ['dataProvider' => $dataProvider]);
	}

	/*public function actionPull() {
		$this->service->allPull();
		\App::$domain->navigation->alert->create(['vendor/info', 'packages_success_pulled']);
		return $this->redirect('/vendor/info');
	}*/
	
}
