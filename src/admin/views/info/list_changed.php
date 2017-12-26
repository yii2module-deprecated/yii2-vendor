<?php

/* @var $this yii\web\View */

use yii\grid\GridView;

$this->title = Yii::t('vendor/info', 'list_changed');

$columns = [
	[
		'label' => Yii::t('main', 'title'),
		'attribute' => 'package',
	],
];
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{summary}{items}',
	'columns' => $columns,
	'tableOptions' => ['class' => 'table table-striped table-bordered  table-hover table-condensed'],
]); ?>
