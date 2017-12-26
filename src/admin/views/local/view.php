<?php

/* @var $this yii\web\View
 * @var $entity
 */
use yii\helpers\Html;

?>

<h3>
	<?= $entity->package ?>
	<small class="label label-default"><?= $entity->version ?></small>
</h3>

<?php if($entity->need_release) { ?>
	<div class="alert alert-info">
		Need create tag.
		<?= Html::a(
			'Draft a new release',
			'https://github.com/' . $entity->package . '/releases/new',
			[
				'class' => 'btn btn-primary',
				'target' => '_blank',
			]
		) ?>
	</div>

<?php } ?>

<h4>Commits</h4>

<?= $this->render('view/commit', compact('entity')) ?>

<h4>Tags</h4>

<?= $this->render('view/tag', compact('entity')) ?>
