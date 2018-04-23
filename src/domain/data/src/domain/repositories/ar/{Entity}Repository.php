<?php

namespace {owner}\{name}\domain\repositories\ar;

use yii2lab\domain\repositories\ActiveArRepository;
use {owner}\{name}\domain\interfaces\repositories\{Entity}Interface;

/**
 * Class {Entity}Repository
 *
 * @package {owner}\{name}\domain\repositories\ar
 *
 * @property-read \{owner}\{name}\domain\Domain $domain
 */
class {Entity}Repository extends ActiveArRepository implements {Entity}Interface {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return '{name}_{entity}';
	}
	
}
