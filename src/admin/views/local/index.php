<?php

/* @var $this yii\web\View */

use yii2lab\helpers\yii\Html;

?>

<div class="list-group">

	<?= Html::a(t('github/local', 'list'), '/github/local/list', ['class' => 'list-group-item']) ?>
 
	<?= Html::a(t('github/local', 'list_changed'), '/github/local/list-changed', ['class' => 'list-group-item']) ?>

	<?/*= Html::a(t('github/local', 'pull_packages'), '/github/local/pull', [
		'data-method' => 'post',
		'class' => ['list-group-item' . (empty($sh) ? '' : ' disabled')],
	])*/ ?>

	<?= Html::a(t('github/local', 'generate_bat'), '/github/local/generate', [
		'data-method' => 'post',
		'class' => ['list-group-item'],
	]) ?>

</div>
