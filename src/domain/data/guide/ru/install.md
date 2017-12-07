Установка
===

Устанавливаем зависимость:

```
composer require {owner}/yii2-{name}
```

Объявляем модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{name}\console\Module',
		// ...
	],
];
```

Объявляем домен:

```php
return [
	'components' => [
		// ...
		'{name}' => [
			'class' => 'yii2lab\domain\Domain',
			'path' => '{owner}\{name}\domain',
			'repositories' => [
				'default',
			],
			'services' => [
				'default',
			],
		],
		// ...
	],
];
```
