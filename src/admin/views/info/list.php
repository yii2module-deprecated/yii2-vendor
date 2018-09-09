<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii2lab\extension\yii\helpers\Html;

$this->title = Yii::t('vendor/info', 'list');

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
	/*[
		'label' => Yii::t('main', 'title'),
		'attribute' => 'package',
	],*/
	[
		'label' => Yii::t('main', 'version'),
		'format' => 'raw',
		'value' => function($data){
			if(empty($data->version)) {
				return null;
			}
			$html = '';
			if($data->version) {
				$html .= Html::a(
					$data->version,
					'https://github.com/' . $data->package . '/releases/tag/v' . $data->version,
					['target' => '_blank']
				);
			}
			if($data->need_release) {
				$html .= Html::a(
						Html::fa('plus'),
						'https://github.com/' . $data->package . '/releases/new',
						['target' => '_blank', 'style' => 'margin-left: 5px']
					);
			}
			return $html ? $html : null;
		},
	],
	[
		'label' => Yii::t('vendor/info', 'has_readme'),
		'format' => 'raw',
		'value' => function($data){
			if($data->has_readme) {
				return Html::a(Html::fa('file-text'), 'https://github.com/'.$data->package.'/blob/master/README.md', ['target' => '_blank']);
			}
			return '';
		},
	],
	[
		'label' => Yii::t('vendor/info', 'has_changelog'),
		'format' => 'raw',
		'value' => function($data){
			if($data->has_changelog) {
				return Html::a(Html::fa('file-text'), 'https://github.com/'.$data->package.'/blob/master/CHANGELOG.md', ['target' => '_blank']);
			}
			return '';
		},
	],
	[
		'label' => Yii::t('vendor/info', 'has_guide'),
		'format' => 'raw',
		'value' => function($data){
			if($data->has_guide) {
				return Html::a(Html::fa('book'), 'https://github.com/'.$data->package.'/blob/master/guide/ru/README.md', ['target' => '_blank']);
			}
			return '';
		},
	],
	[
		'label' => Yii::t('vendor/info', 'has_license'),
		'format' => 'raw',
		'value' => function($data){
			if($data->has_license) {
				return Html::a(Html::fa('balance-scale'), 'https://github.com/'.$data->package.'/blob/master/LICENSE', ['target' => '_blank']);
			}
			return '';
		},
	],
	[
		'label' => Yii::t('vendor/info', 'has_test'),
		'format' => 'raw',
		'value' => function($data){
			if($data->has_test) {
				return Html::a(Html::fa('coffee'), 'https://github.com/'.$data->package.'/blob/master/tests', ['target' => '_blank']);
			}
			return '';
		},
	],
	[
		'label' => Yii::t('vendor/main', 'branch'),
		'attribute' => 'branch',
	],
	[
		'label' => Yii::t('vendor/info', 'last_commit'),
		'attribute' => 'head_commit.message',
	],
	/*[
		'label' => Yii::t('vendor/info', 'need_release'),
		'attribute' => 'need_release',
	],
	[
		'label' => Yii::t('main', 'head_sha'),
		'attribute' => 'head_commit.sha',
	],*/
];
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{summary}{items}',
	'columns' => $columns,
	'tableOptions' => ['class' => 'table table-striped table-bordered  table-hover table-condensed'],
]); ?>
