<?php

/* @var $this yii\web\View */

use yii2lab\helpers\yii\Html;

?>

<div class="list-group">

	<?= Html::a(t('vendor/local', 'list'), '/vendor/local/list', ['class' => 'list-group-item']) ?>
 
	<?= Html::a(t('vendor/local', 'list_changed'), '/vendor/local/list-changed', ['class' => 'list-group-item']) ?>

	<?/*= Html::a(t('vendor/local', 'pull_packages'), '/vendor/local/pull', [
		'data-method' => 'post',
		'class' => ['list-group-item' . (empty($sh) ? '' : ' disabled')],
	])*/ ?>

	<?= Html::a(t('vendor/local', 'generate_bat'), '/vendor/local/generate', [
		'data-method' => 'post',
		'class' => ['list-group-item'],
	]) ?>

</div>
