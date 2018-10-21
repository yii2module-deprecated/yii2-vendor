<?php

/* @var $this yii\web\View
 * @var $entity yii2lab\domain\BaseEntity
 */
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$columns = [
	[
		'label' => Yii::t('main', 'name'),
		'attribute' => 'name',
	],
	[
		'label' => Yii::t('main', 'version'),
		'attribute' => 'version',
	],
];

$dataProvider = new ArrayDataProvider([
	'models' => ArrayHelper::toArray($entity->required_packages),
]);
?>

<br/>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'columns' => $columns,
]); ?>
