<?php

/* @var $this yii\web\View
 * @var $entity \yii2module\vendor\domain\entities\RepoEntity
 * @var $versionVariations array
 */

use yii2module\vendor\domain\helpers\VersionHelper;

$releaseUrl = VersionHelper::generateUrl($entity, 'newTag', [
	'package' => $entity->package,
]);

?>

<br/>

<?php if($entity->need_release) { ?>
    <div class="alert alert-info">
		<?= Yii::t('vendor/info', 'package_need_of_release') ?>
    </div>
<?php } ?>

<?php foreach($versionVariations as $version) { ?>
    <a href="<?= $releaseUrl ?>" target="_blank"
       class="btn btn-<?= $version['is_recommended'] ? 'primary' : 'default' ?> btn-copy" title="copy version"
       data-clipboard-text="v<?= $version['version'] ?>">
        v<?= $version['version'] ?>
        <i class="fa fa-clipboard" aria-hidden="true"></i>
    </a>
    &nbsp;
<?php } ?>

<?php

$commits = VersionHelper::filterNewVersionCommits($entity->commits);

?>

<h4><?= Yii::t('vendor/info', 'new_commits') ?></h4>

<?= $this->render('_commitTable', [
	'entity' => $entity,
	'commits' => $commits,
]) ?>

