<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 */
use yii\helpers\Html;

$this->title = $entity->package;

?>

<h3>
	<?= $entity->package ?>
	<small class="label label-default"><?= $entity->version ?></small>
</h3>

<?php if($entity->need_release) { ?>
	<div class="alert alert-info">
		<?= Yii::t('vendor/info', 'package_need_of_release') ?>
		<?= Html::a(
			Yii::t('vendor/info', 'draft_new_release'),
			'https://github.com/' . $entity->package . '/releases/new',
			[
				'class' => 'btn btn-primary',
				'target' => '_blank',
			]
		) ?>
	</div>
<?php } ?>

<?php if($entity->has_changes) { ?>
    <div class="alert alert-warning">
	    <?= Yii::t('vendor/info', 'package_has_changes') ?>
    </div>
<?php } ?>

<h4><?= Yii::t('vendor/main', 'commits') ?></h4>

<?= $this->render('view/commit', compact('entity')) ?>

<h4><?= Yii::t('vendor/main', 'tags') ?></h4>

<?= $this->render('view/tag', compact('entity')) ?>

<h4><?= Yii::t('vendor/info', 'required_packages') ?></h4>

<?= $this->render('view/required_packages', compact('entity')) ?>
