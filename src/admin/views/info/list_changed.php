<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('vendor/info', 'list_changed');

$columns = [
	[
		'label' => Yii::t('main', 'title'),
		'format' => 'raw',
		'value' => function($data) {
			return
				Html::a(
					$data->package,
					['/vendor/info/view', 'id' => $data->id]
				);
		},
	],
];
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{summary}{items}',
	'columns' => $columns,
	'tableOptions' => ['class' => 'table table-striped table-bordered  table-hover table-condensed'],
]); ?>
