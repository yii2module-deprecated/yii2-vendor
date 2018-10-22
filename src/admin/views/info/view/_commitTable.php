<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 * @var $commits \yii2module\vendor\domain\entities\CommitEntity[]
 */

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii2lab\extension\yii\helpers\Html;
use yii2module\vendor\domain\entities\CommitEntity;
use yii2module\vendor\domain\helpers\VersionHelper;

$columns = [
	[
		'label' => Yii::t('main', 'message'),
		'format' => 'raw',
		'value' => function(CommitEntity $commitEntity) use($entity){
			$tagHtml = '';
			if(!empty($commitEntity->tag)) {
				$tagHtml =  " <span class='label label-default'>{$commitEntity->tag->name}</span>";
			}
			$url = VersionHelper::generateUrl($entity, 'viewCommit', [
				'package' => $entity->package,
				'hash' => $commitEntity->sha,
			]);
			return Html::a($commitEntity->message, $url, [
				'target' => '_blank',
			]) . SPC . $tagHtml;
		},
	],
	[
		'label' => Yii::t('main', 'author'),
		'attribute' => 'author_name',
	],
	[
		'label' => Yii::t('main', 'created_at'),
		'attribute' => 'date',
	],
	[
		'label' => Yii::t('vendor/main', 'sha'),
		'format' => 'raw',
		'value' => function(CommitEntity $commitEntity) {
			return Html::tag('span', substr($commitEntity->sha, 0, 8), ['title' => $commitEntity->sha]);
		},
	],
];
$dataProvider = new ArrayDataProvider([
	'models' => $commits,
]);
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'columns' => $columns,
]) ?>
