<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 */

?>

    <br/>

<?php if($entity->has_changes) { ?>
    <div class="alert alert-warning">
		<?= Yii::t('vendor/info', 'package_has_changes') ?>
    </div>
<?php } ?>

<?= $this->render('_commitTable', [
	'entity' => $entity,
	'commits' => $entity->commits,
]) ?>