<?php

/* @var $this yii\web\View */

use yii2lab\helpers\yii\Html;

?>

<div class="list-group">

	<?= Html::a(t('vendor/info', 'list'), '/vendor/info/list', ['class' => 'list-group-item']) ?>
 
	<?= Html::a(t('vendor/info', 'list_changed'), '/vendor/info/list-changed', ['class' => 'list-group-item']) ?>

	<?/*= Html::a(t('vendor/info', 'pull_packages'), '/vendor/info/pull', [
		'data-method' => 'post',
		'class' => ['list-group-item' . (empty($sh) ? '' : ' disabled')],
	])*/ ?>

	<?= Html::a(t('vendor/info', 'generate_bat'), '/vendor/info/generate', [
		'data-method' => 'post',
		'class' => ['list-group-item'],
	]) ?>

</div>
