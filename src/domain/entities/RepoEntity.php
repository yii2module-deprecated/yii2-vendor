<?php

namespace yii2module\vendor\domain\entities;

use Yii;
use yii2lab\domain\BaseEntity;
use yii2mod\helpers\ArrayHelper;

class RepoEntity extends BaseEntity {

	protected $owner;
	protected $name;
	protected $package;
	protected $tags;
	protected $commits;
	protected $has_changes = false;
	
	public function fieldType() {
		return [
			'tags' => [
				'type' => TagEntity::className(),
				'isCollection' => true,
			],
			'commits' => [
				'type' => CommitEntity::className(),
				'isCollection' => true,
			],
			'has_changes' => 'boolean',
		];
	}
	
	public function setCommits($value) {
		$value = Yii::$app->vendor->factory->entity->create('commit', $value);
		$this->commits = $this->attachTagToCommit($value);
	}
	
	public function getHeadCommit() {
		$commit = ArrayHelper::first($this->commits);
		return $commit;
	}
	
	public function getNeedRelease() {
		$lastCommit = $this->getHeadCommit();
		if(empty($lastCommit)) {
			return null;
		}
		return !is_object($lastCommit->tag);
	}
	
	public function getPackage() {
		if(!empty($this->package)) {
			return $this->package;
		}
		return $this->owner . SL . 'yii2-' . $this->name;
	}
	
	public function getAlias() {
		return $this->owner . SL . $this->name;
	}
	
	public function getVersion() {
		if(empty($this->tags)) {
			return null;
		}
		$versionList = ArrayHelper::flatten($this->tags);
		rsort($versionList);
		$last = $versionList[0];
		$last = trim($last->name, 'v');
		return $last;
	}

	public function fields() {
		$fields = parent::fields();
		$fields['alias'] = 'alias';
		$fields['version'] = 'version';
		$fields['need_release'] = 'need_release';
		$fields['head_commit'] = 'head_commit';
		return $fields;
	}
	
	private function attachTagToCommit($commits) {
		if(!empty($this->tags) && !empty($commits)) {
			$tags = ArrayHelper::index($this->tags, 'sha');
			foreach($commits as $commit) {
				$sha = $commit->sha;
				if(isset($tags[$sha])) {
					$commit->tag = $tags[$sha];
				}
			}
		}
		return $commits;
	}
}
