<?php

namespace yii2module\vendor\console\controllers;

use Yii;
use yii2lab\extension\console\helpers\input\Select;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\Output;
use yii2mod\helpers\ArrayHelper;

class PrettyController extends Controller
{
	
	public function actionDomain()
	{
		$domainAliases = Yii::$domain->vendor->pretty->all();
		$domainAlias = Select::display('Select domain', $domainAliases);
		$domainAlias = ArrayHelper::first($domainAlias);
		Yii::$domain->vendor->pretty->updateById($domainAlias, []);
		Output::block('Success pretty');
	}
	
	/*public function actionDomain()
	{
		$domainAliases = Yii::$domain->vendor->pretty->all();
		$domainAlias = Select::display('Select domain', $domainAliases);
		$domainAlias = ArrayHelper::first($domainAlias);
		$domainEntity = Yii::$domain->vendor->pretty->oneById($domainAlias);
		prr($domainEntity,1,1);
	}*/
	
}
