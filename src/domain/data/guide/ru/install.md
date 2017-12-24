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
		'{name}' => '{owner}\{name}\frontend\Module',
		// ...
	],
];
```

Объявляем backend модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{name}\backend\Module',
		// ...
	],
];
```

Объявляем api модуль:

```php
return [
	'modules' => [
		// ...
		'{name}' => '{owner}\{name}\api\Module',
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
		'{name}' => '{owner}\{name}\domain\Domain',
		// ...
	],
];
```
