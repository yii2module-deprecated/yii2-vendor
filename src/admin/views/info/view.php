<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 * @var $versionVariations array
 */
use yii2lab\extension\clipboardJs\ClipboardJsAsset;
use yii2lab\extension\widget\entityActions\EntityActionsWidget;

$this->title = $entity->package;

ClipboardJsAsset::register($this);

?>

<div class="pull-right">
	<?= EntityActionsWidget::widget([
		'id' => $entity->id,
		'baseUrl' => 'vendor/git',
		'actions' => ['synch', 'pull', 'push'],
		'actionsDefinition' => [
			'pull' => [
				'icon' => 'download',
				'textType' => 'primary',
				'action' => 'pull',
				'title' => ['vendor/git', 'pull'],
				'data' => ['method' => 'post'],
			],
			'push' => [
				'icon' => 'upload',
				'textType' => 'primary',
				'action' => 'push',
				'title' => ['vendor/git', 'push'],
				'data' => ['method' => 'post'],
			],
			'synch' => [
				'icon' => 'refresh',
				'textType' => 'primary',
				'action' => 'synch',
				'title' => ['vendor/git', 'synch'],
				'data' => ['method' => 'post'],
			],
		],
	]) ?>
</div>

<h3>
	<?= $entity->package ?>
	<small class="label label-default">v<?= $entity->version ?></small>
</h3>

<?= \yii\bootstrap\Tabs::widget([
	'encodeLabels' => false,
	'items' => [
		[
			'label' => Yii::t('vendor/main', 'release') . ($entity->need_release ? ' <small class="label label-primary">new</small>' : ''),
			'content' => $this->render('view/release', compact('entity', 'versionVariations')),
		],
        [
			'label' => Yii::t('vendor/main', 'commits') . ($entity->has_changes ? ' <small class="label label-primary">new</small>' : ''),
			'content' => $this->render('view/commit', compact('entity')),
		],
		[
			'label' => Yii::t('vendor/main', 'tags'),
			'content' => $this->render('view/tag', compact('entity')),
		],
		[
			'label' => Yii::t('vendor/info', 'required_packages'),
			'content' => $this->render('view/required_packages', compact('entity')),
		],
	],
]) ?>
