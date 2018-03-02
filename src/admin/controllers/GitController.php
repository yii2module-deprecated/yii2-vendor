<?php

namespace yii2module\vendor\admin\controllers;

use common\enums\rbac\PermissionEnum;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii2lab\domain\data\Query;
use yii2lab\domain\web\ActiveController as Controller;
use yii2lab\helpers\Behavior;
use yii2lab\notify\domain\widgets\Alert;

class GitController extends Controller {
	
	public $serviceName = 'vendor.git';
	public $titleName = 'package';
	
	public function behaviors()
	{
		return [
			'access' => Behavior::access(PermissionEnum::VENDOR_MANAGE),
			'verbs' => Behavior::verb([
				'checkout' => ['POST'],
				'pull' => ['POST'],
				'push' => ['POST'],
				'synch' => ['POST'],
			]),
		];
	}
	
	public function actionCheckout($id, $branch) {
		$entity = Yii::$app->vendor->info->oneById($id);
		Yii::$app->vendor->git->checkout($entity, $branch);
		Yii::$app->navigation->alert->create(['vendor/git', 'checkout_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionSynch($id) {
		$entity = Yii::$app->vendor->info->oneById($id);
		Yii::$app->vendor->git->pull($entity);
		Yii::$app->vendor->git->push($entity);
		Yii::$app->navigation->alert->create(['vendor/git', 'synch_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionPull($id) {
		$entity = Yii::$app->vendor->info->oneById($id);
		$result = Yii::$app->vendor->git->pull($entity);
		if(!empty($result)) {
			Yii::$app->navigation->alert->create(['vendor/git', 'pull_success {data}', ['data' => nl2br($result)]], Alert::TYPE_SUCCESS);
		} else {
			Yii::$app->navigation->alert->create(['vendor/git', 'pull_no_changes'], Alert::TYPE_INFO);
		}
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionPush($id) {
		$entity = Yii::$app->vendor->info->oneById($id);
		Yii::$app->vendor->git->push($entity);
		Yii::$app->navigation->alert->create(['vendor/git', 'push_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
}
