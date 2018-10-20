<?php

/* @var $this yii\web\View
 * @var $entity yii2lab\domain\BaseEntity
 */

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$columns = [
	[
		'label' => Yii::t('main', 'name'),
		'format' => 'raw',
		'value' => function($data) use($entity){
			return Html::a(
				$data['name'],
				"https://github.com/{$entity->package}/releases/tag/{$data['name']}",
				['target' => '_blank',]
			);
		},
	],
	[
		'label' => Yii::t('vendor/main', 'sha'),
		'format' => 'raw',
		'value' => function($data) {
			return Html::tag('span', substr($data['sha'], 0, 8), ['title' => $data['sha']]);
		},
	],
	[
		'label' => Yii::t('main', 'action'),
		'format' => 'raw',
		'value' => function($data) use($entity) {
			return Html::a(
				Yii::t('vendor/git', 'checkout'),
				Url::to('/vendor/git/checkout?id='.$entity->id.'&branch=' . $data['name']),
				[
					'class' => 'btn btn-default',
					'data-method' => 'post',
				]
			);
	//tags/v1.0
		},
	],
];
$dataProvider = new ArrayDataProvider([
	'models' => ArrayHelper::toArray($entity->tags),
]);
?>

<br/>

<?= Html::a(
	Yii::t('vendor/git', 'checkout') . SPC . '<b>master</b>',
	Url::to('/vendor/git/checkout?id='.$entity->id.'&branch=master'),
	[
		'class' => 'btn btn-default',
		'data-method' => 'post',
	]
); ?>

<br/>
<br/>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'columns' => $columns,
]); ?>
