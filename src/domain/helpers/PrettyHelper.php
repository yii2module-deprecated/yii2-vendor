<?php

namespace yii2module\vendor\domain\helpers;

use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\generator\RepositoryInterfaceGenerator;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2lab\extension\code\helpers\parser\DocCommentHelper;
use yii2lab\extension\code\helpers\parser\TokenCollectionHelper;
use yii2lab\extension\code\helpers\parser\TokenHelper;
use yii2lab\extension\common\helpers\ClassHelper;
use yii2lab\extension\yii\helpers\FileHelper;

class PrettyHelper {
	
	public static function refreshDomain($namespace) {
		$namespace = str_replace(SL, BSL, $namespace);
		self::generateVirtualRepositoryInterface($namespace);
		self::updateDomainDocComment($namespace);
		self::updateDomainContainerDocComment($namespace);
	}
	
	private static function updateDomainContainerDocComment($namespace) {
		$fileName = FileHelper::getAlias('@common/locators/DomainLocator');
		$tokenCollection = TokenHelper::load($fileName . DOT . 'php');
		$docCommentIndexes = TokenCollectionHelper::getDocCommentIndexes($tokenCollection);
		$docComment = $tokenCollection[$docCommentIndexes[0]]->value;
		$entity = DocCommentHelper::parse($docComment);
		$classDomain = $namespace.'\\Domain';
		foreach(\App::$domain->components as $id => $instance) {
			$isThisInstance =
				(is_object($instance) && $instance instanceof $classDomain) ||
				(is_array($instance) && $instance['class'] == $classDomain);
			if($isThisInstance) {
				$entity = DocCommentHelper::addAttribute($entity, [
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'value' => [
						'\\' . $classDomain,
						'$' . $id,
					],
				]);
			}
		}
		
		$doc = DocCommentHelper::generate($entity);
		if($tokenCollection) {
			$tokenCollection[$docCommentIndexes[0]]->value = $doc;
		}
		TokenHelper::save($fileName . DOT . 'php', $tokenCollection);
	}
	
	private static function updateDomainDocComment($namespace) {
		$one = \App::$domain->vendor->pretty->oneById($namespace);
		$fileName = FileHelper::getAlias('@' . $namespace . '\\Domain');
		$tokenCollection = TokenHelper::load($fileName . DOT . 'php');
		$tokenCollection = TokenCollectionHelper::addDocComment($tokenCollection);
		$docCommentIndexes = TokenCollectionHelper::getDocCommentIndexes($tokenCollection);
		// todo: если нет докблока, то вставлять
		$docComment = $tokenCollection[$docCommentIndexes[0]]->value;
		$entity = DocCommentHelper::parse($docComment);
		$services = ArrayHelper::getValue($one, 'interfaces.services');
		if(!empty($services)) {
			$servs = array_keys($services);
			foreach($servs as $serv) {
				$entity = DocCommentHelper::addAttribute($entity, [
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'value' => [
						'\\'.$namespace.'\\interfaces\\services\\'.ucfirst($serv).'Interface',
						'$' . $serv,
					],
				]);
			}
		}
		$entity = DocCommentHelper::addAttribute($entity, [
			'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
			'value' => [
				'\\' . $namespace . '\\interfaces\\repositories\\RepositoriesInterface',
				'$repositories',
			],
		]);
		$doc = DocCommentHelper::generate($entity);
		$index = $docCommentIndexes[0];
		$tokenEntity = $tokenCollection[$index];
		$tokenEntity->value = $doc;
		TokenHelper::save($fileName . DOT . 'php', $tokenCollection);
	}
	
	private static function generateVirtualRepositoryInterface($namespace) {
		$one = \App::$domain->vendor->pretty->oneById($namespace);
		$repositories = ArrayHelper::getValue($one, 'interfaces.repositories');
		if(empty($repositories)) {
			return;
		}
		$repos = array_keys($repositories);
		$repositoryDocBlock = [];
		foreach($repos as $repo) {
			if($repo != 'repositories') {
				$repositoryDocBlock[] = [
					'name' => DocBlockParameterEntity::NAME_PROPERTY_READ,
					'type' => '\\' . $namespace . '\\interfaces\\repositories\\' . ucfirst($repo) . 'Interface',
					'value' => $repo,
				];
			}
		}
		$generator = new RepositoryInterfaceGenerator();
		$generator->name = $namespace . '\\interfaces\\repositories\\RepositoriesInterface';
		$generator->docBlockParameters = $repositoryDocBlock;
		$generator->extends = [];
		$generator->defaultUses = [];
		$generator->run();
	}
	
	public static function scanDomain($dir, $types) {
		if(!is_dir($dir)) {
			return null;
		}
		$data = FileHelper::scanDir($dir);
		$data = array_flip($data);
		$result = [];
		foreach($data as $driver => $value) {
			$path = $dir . DS . $driver;
			if(is_dir($path)) {
				$result[$driver] = self::scanDomain($path, $types);
			} else {
				$name = self::parseClassName($driver, $types);
				$result[$name] = null;
			}
		}
		return $result;
	}
	
	private static function parseClassName($name, $types) {
		$class = FileHelper::fileRemoveExt($name);
		$isMatch = preg_match('#^([a-zA-Z]+)('.implode('|', $types).')#i', $class, $matches);
		if(!$isMatch) {
			return $name;
		}
		$class = $matches[1];
		$class{0} = strtolower($class{0});
		return $class;
	}
	
}
