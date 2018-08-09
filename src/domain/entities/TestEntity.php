<?php

namespace yii2module\vendor\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * @property $result
 * @property $tests
 * @property $assertions
 * @property $error
 * @property $text
 */
class TestEntity extends BaseEntity {

	protected $tests = 0;
    protected $assertions = 0;
    protected $error = 0;
    protected $text;

    public function getIsHasErrors() {
        return !empty($this->error);
    }

}
