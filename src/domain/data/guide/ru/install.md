Установка
===

Устанавливаем зависимость:

```
composer require {owner}/yii2-{name}
```

Создаем полномочие:

```
oExamlpe
```

Объявляем frontend модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{nameAlias}\frontend\Module',
		// ...
	],
];
```

Объявляем backend модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{nameAlias}\backend\Module',
		// ...
	],
];
```

Объявляем api модуль:

```php
return [
	'modules' => [
		// ...
		'{nameAlias}' => '{owner}\{nameAlias}\api\Module',
		// ...
		'components' => [
            'urlManager' => [
                'rules' => [
                    ...
                   ['class' => 'yii\rest\UrlRule', 'controller' => ['{apiVersion}/{name}' => '{name}/default']],
                    ...
                ],
            ],
        ],
	],
];
```

Объявляем консольный модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{nameAlias}\console\Module',
		// ...
	],
];
```

Объявляем домен:

```php
return [
	'components' => [
		// ...
		'{nameAlias}' => '{owner}\{nameAlias}\domain\Domain',
		// ...
	],
];
```
