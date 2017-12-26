<?php

/* @var $this yii\web\View
 * @var $entity yii2lab\domain\BaseEntity
 */
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
		'label' => Yii::t('main', 'sha'),
		'format' => 'raw',
		'value' => function($data) {
			return Html::tag('span', substr($data['sha'], 0, 8), ['title' => $data['sha']]);
		},
	],
];
$dataProvider = new ArrayDataProvider([
	'models' => ArrayHelper::toArray($entity->tags),
]);
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'columns' => $columns,
]); ?>
