<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 * @var $versionVariations array
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii2lab\extension\clipboardJs\ClipboardJsAsset;

$this->title = $entity->package;

ClipboardJsAsset::register($this);

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
		
		<?php foreach($versionVariations as $version) { ?>
            <button class="btn btn-default btn-copy" title="copy version" data-clipboard-text="v<?= $version ?>">
                v<?= $version ?>
                <i class="fa fa-clipboard" aria-hidden="true"></i>
            </button>
            &nbsp;
		<?php } ?>

    </div>
<?php } ?>

<?php if($entity->has_changes) { ?>
    <div class="alert alert-warning">
	    <?= Yii::t('vendor/info', 'package_has_changes') ?>
    </div>
<?php } ?>

<?= Html::a(
	Yii::t('vendor/git', 'synch'),
	Url::to('/vendor/git/synch?id='.$entity->id),
    [
		'class' => 'btn btn-default',
	    'data-method' => 'post',
	]
); ?>

<?= Html::a(
	Yii::t('vendor/git', 'pull'),
	Url::to('/vendor/git/pull?id='.$entity->id),
	[
		'class' => 'btn btn-default',
		'data-method' => 'post',
	]
); ?>

<?= Html::a(
	Yii::t('vendor/git', 'push'),
	Url::to('/vendor/git/push?id='.$entity->id),
	[
		'class' => 'btn btn-default',
		'data-method' => 'post',
	]
); ?>

<h4><?= Yii::t('vendor/main', 'commits') ?></h4>

<?= $this->render('view/commit', compact('entity')) ?>

<h4><?= Yii::t('vendor/main', 'tags') ?></h4>

<?= $this->render('view/tag', compact('entity')) ?>

<h4><?= Yii::t('vendor/info', 'required_packages') ?></h4>

<?= $this->render('view/required_packages', compact('entity')) ?>
