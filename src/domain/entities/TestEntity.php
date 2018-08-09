<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * @property $tests
 * @property $assertions
 * @property $error
 * @property $text
 * @property $directory
 */
class TestEntity extends BaseEntity {

	protected $tests = 0;
    protected $assertions = 0;
    protected $error = 0;
    protected $text;
    protected $directory;

    public function getIsHasErrors() {
        return !empty($this->error);
    }

}
