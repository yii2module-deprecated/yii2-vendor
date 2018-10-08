<?php

namespace yii2module\vendor\admin\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii2lab\domain\data\Query;
use yii2lab\domain\web\ActiveController as Controller;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\navigation\domain\widgets\Alert;
use yii2module\vendor\domain\enums\VendorPermissionEnum;

class GitController extends Controller {
	
	public $service = 'vendor.git';
	public $titleName = 'package';
	
	public function behaviors()
	{
		return [
			'access' => Behavior::access(VendorPermissionEnum::MANAGE),
			'verbs' => Behavior::verb([
				'checkout' => ['POST'],
				'pull' => ['POST'],
				'push' => ['POST'],
				'synch' => ['POST'],
			]),
		];
	}
	
	public function actionCheckout($id, $branch) {
		$entity = \App::$domain->vendor->info->oneById($id);
		\App::$domain->vendor->git->checkout($entity, $branch);
		\App::$domain->navigation->alert->create(['vendor/git', 'checkout_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionSynch($id) {
		$entity = \App::$domain->vendor->info->oneById($id);
		\App::$domain->vendor->git->pull($entity);
		\App::$domain->vendor->git->push($entity);
		\App::$domain->navigation->alert->create(['vendor/git', 'synch_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionPull($id) {
		$entity = \App::$domain->vendor->info->oneById($id);
		$result = \App::$domain->vendor->git->pull($entity);
		if(!empty($result)) {
			\App::$domain->navigation->alert->create(['vendor/git', 'pull_success {data}', ['data' => nl2br($result)]], Alert::TYPE_SUCCESS);
		} else {
			\App::$domain->navigation->alert->create(['vendor/git', 'pull_no_changes'], Alert::TYPE_INFO);
		}
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
	
	public function actionPush($id) {
		$entity = \App::$domain->vendor->info->oneById($id);
		\App::$domain->vendor->git->push($entity);
		\App::$domain->navigation->alert->create(['vendor/git', 'push_success'], Alert::TYPE_SUCCESS);
		return $this->redirect(Url::to('/vendor/info/view?id=' . $id));
	}
}
