<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2mod\helpers\ArrayHelper;

class RepoEntity extends BaseEntity {

	protected $owner;
	protected $name;
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
	
	public function getFullName() {
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
		$fields['full_name'] = 'full_name';
		$fields['alias'] = 'alias';
		$fields['version'] = 'version';
		return $fields;
	}
}
