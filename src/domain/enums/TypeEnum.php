<?php

namespace yii2module\vendor\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class TypeEnum extends BaseEnum {
	
	const PACKAGE = 'Package';
	const LICENSE = 'License';
	const GUIDE = 'Guide';
	const README = 'Readme';
	const CHANGELOG = 'Changelog';
	const TEST = 'Test';
	const DOMAIN = 'Domain';
	const API_MODULE = 'Api module';
	const ADMIN_MODULE = 'Admin module';
	const WEB_MODULE = 'Web module';
	const CONSOLE_MODULE = 'Console module';
	
}