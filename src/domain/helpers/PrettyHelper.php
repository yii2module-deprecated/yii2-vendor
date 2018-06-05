<?php

namespace yii2module\vendor\domain\helpers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\Domain;
use yii2lab\domain\generator\RepositoryInterfaceGenerator;
use yii2lab\extension\code\entities\DocBlockParameterEntity;
use yii2lab\extension\code\helpers\parser\DocCommentHelper;
use yii2lab\extension\code\helpers\parser\TokenCollectionHelper;
use yii2lab\extension\code\helpers\parser\TokenHelper;
use yii2lab\helpers\ClassHelper;
use yii2lab\helpers\yii\FileHelper;

class PrettyHelper {
	
	public static function refreshDomain($namespace) {
		$namespace = str_replace(SL, BSL, $namespace);
		self::generateVirtualRepositoryInterface($namespace);
		self::updateDomainDocComment($namespace);
		self::updateDomainContainerDocComment($namespace);
	}
	
	public static function scanForDomainRecursive($domainAliasName) {
		$domainAlias = '@' . $domainAliasName;
		$aliases = [];
		try {
			if(self::isDomainDir($domainAlias)) {
				$aliases[] = $domainAliasName;
			} else {
				$versions = self::scanForDomain($domainAlias);
				foreach($versions as $version) {
					$newAliases = self::scanForDomainRecursive($domainAliasName . SL . $version);
					if(!empty($newAliases)) {
						$aliases = ArrayHelper::merge($aliases, $newAliases);
					}
				}
			}
		} catch(InvalidArgumentException $e) {}
		return $aliases;
	}
	
	private static function updateDomainContainerDocComment($namespace) {
		$fileName = FileHelper::getAlias('@yii2lab/domain/yii2/DomainContainer');
		$tokenCollection = TokenHelper::load($fileName . DOT . 'php');
		$docCommentIndexes = TokenCollectionHelper::getDocCommentIndexes($tokenCollection);
		$docComment = $tokenCollection[$docCommentIndexes[0]]->value;
		$entity = DocCommentHelper::parse($docComment);
		$classDomain = $namespace.'\\Domain';
		foreach(Yii::$domain->components as $id => $instance) {
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
		$tokenCollection[$docCommentIndexes[0]]->value = $doc;
		TokenHelper::save($fileName . DOT . 'php', $tokenCollection);
	}
	
	private static function updateDomainDocComment($namespace) {
		$one = Yii::$domain->vendor->pretty->oneById($namespace);
		$fileName = FileHelper::getAlias('@' . $namespace . '\\Domain');
		$tokenCollection = TokenHelper::load($fileName . DOT . 'php');
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
		$tokenCollection[$docCommentIndexes[0]]->value = $doc;
		TokenHelper::save($fileName . DOT . 'php', $tokenCollection);
	}
	
	private static function generateVirtualRepositoryInterface($namespace) {
		$one = Yii::$domain->vendor->pretty->oneById($namespace);
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
	
	private static function isDomainDir($domainDir) {
		$alias = substr($domainDir, 1);
		$alias = str_replace(SL, BSL, $alias);
		$className = FileHelper::fileRemoveExt($alias);
		if($className != $alias) {
			return false;
		}
		$classNameDomain = $className . BSL . 'Domain';
		if(class_exists($classNameDomain)) {
			$domainInstance = ClassHelper::createObject($classNameDomain);
			$isDomainClass = $domainInstance instanceof Domain;
			if($isDomainClass) {
				return true;
			}
		}
		$isServicesDirExists = is_dir(FileHelper::getAlias($domainDir)  . DS . 'services');
		return $isServicesDirExists;
	}
	
	private static function scanForDomain($alias) {
		$domainDir = FileHelper::getAlias($alias);
		if(!is_dir($domainDir)) {
			return [];
		}
		return FileHelper::scanDir($domainDir);
	}
	
	
}
