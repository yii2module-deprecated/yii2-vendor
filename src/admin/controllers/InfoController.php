<?php

namespace yii2module\vendor\admin\controllers;

use common\enums\rbac\PermissionEnum;
use Yii;
use yii\data\ArrayDataProvider;
use yii2lab\app\domain\helpers\Config;
use yii2lab\domain\data\Query;
use yii2lab\domain\web\ActiveController as Controller;
use yii2lab\notify\domain\widgets\Alert;

class InfoController extends Controller {

	public $serviceName = 'vendor.info';
	public $titleName = 'package';
	
	public function behaviors()
	{
		return [
			'access' => Config::genAccess(PermissionEnum::VENDOR_MANAGE),
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
		$entity = Yii::$app->vendor->info->oneById($id, $query);
		return $this->render('view', ['entity' => $entity]);
	}
	
	public function actionList() {
		$query = Query::forge();
		$query->with('tags');
		$query->with('commits');
		$query->with('branch');
		$query->with('has_readme');
		$query->with('has_guide');
		$query->with('has_license');
		$query->with('has_test');
		$collection = Yii::$app->vendor->info->all($query);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => [
				'pageSize' => 1000,
			],
		]);
		return $this->render('list', ['dataProvider' => $dataProvider]);
	}
	
	public function actionListForRelease() {
		$query = Query::forge();
		$query->with('tags');
		$query->with('commits');
		$query->with('branch');
		$query->with('has_readme');
		$query->with('has_guide');
		$query->with('has_license');
		$query->with('has_test');
		$collection = Yii::$app->vendor->info->allForRelease($query);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => [
				'pageSize' => 1000,
			],
		]);
		return $this->render('list', ['dataProvider' => $dataProvider]);
	}
	
	public function actionListChanged() {
		$collection = Yii::$app->vendor->info->allChanged();
		$dataProvider = new ArrayDataProvider([
			'allModels' => $collection,
			'pagination' => [
				'pageSize' => 1000,
			],
		]);
		return $this->render('list_changed', ['dataProvider' => $dataProvider]);
	}

	public function actionGenerate() {
		$file = 'cmd/git/vendor pull.bat';
		$this->service->generateBat($file);
		Yii::$app->notify->flash->send(['vendor/info', 'bat_success_generated'], Alert::TYPE_SUCCESS, 10000);
		return $this->redirect('/vendor/info');
	}
	
	public function actionPull() {
		$this->service->allPull();
		Yii::$app->notify->flash->send(['vendor/info', 'packages_success_pulled']);
		return $this->redirect('/vendor/info');
	}
	
}
