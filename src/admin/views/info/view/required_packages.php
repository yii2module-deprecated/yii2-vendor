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
		'attribute' => 'alias',
	],
];
$collection = [];
foreach($entity->required_packages as $item) {
	$collection[] = [
		'alias' => $item,
	];
}
$dataProvider = new ArrayDataProvider([
	'models' => ArrayHelper::toArray($collection),
]);
?>

<br/>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'columns' => $columns,
]); ?>
