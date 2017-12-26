<?php

namespace yii2module\vendor\admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii2lab\domain\data\ActiveDataProvider;
use yii2lab\domain\data\Query;
use yii2lab\domain\web\ActiveController as Controller;
use yii2lab\notify\domain\widgets\Alert;

class LocalController extends Controller {

	public $serviceName = 'github.local';
	public $titleName = 'full_name';
	
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'generate' => ['post'],
					'pull' => ['post'],
				],
			]
		];
	}
	
	public function actions() {
		$actions = parent::actions();
		unset($actions['list']);
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
		
		//prr($entity->commits,1,1);
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
		Yii::$app->notify->flash->send(['github/local', 'bat_success_generated'], Alert::TYPE_SUCCESS, 10000);
		return $this->redirect('/github/local');
	}
	
	public function actionPull() {
		$this->service->allPull();
		Yii::$app->notify->flash->send(['github/local', 'packages_success_pulled']);
		return $this->redirect('/github/local');
	}
	
}
