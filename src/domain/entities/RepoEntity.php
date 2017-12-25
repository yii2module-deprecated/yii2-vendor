<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2mod\helpers\ArrayHelper;

class RepoEntity extends BaseEntity {

	protected $owner;
	protected $name;
	protected $tags;
	
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
		return $versionList[0];
	}

	public function fields() {
		$fields = parent::fields();
		$fields['full_name'] = 'full_name';
		$fields['alias'] = 'alias';
		$fields['version'] = 'version';
		return $fields;
	}
}
