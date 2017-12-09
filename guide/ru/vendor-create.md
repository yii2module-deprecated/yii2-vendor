Создание
==============

## Создание исходников

Для создания стороннего расширения необходимо:
 
* создать репозиторий
* клонировать
* создать файлы `.gitignore` и `composer.json`
* создать папку `src` для исходного кода
* написать код
* отправить изменения в репозиторий

Теперь Вы имеете готовое расширение для подключения его к своему проекту.

Файл `.gitignore` дожен содержать примерно следующее:

```
# phpstorm project files
.idea

# netbeans project files
nbproject
/nbproject/private/

# zend studio for eclipse project files
.buildpath
.project
.settings

# windows thumbnail cache
Thumbs.db

# Mac DS_Store Files
.DS_Store

# local phpunit config
/phpunit.xml
```

Теперь приступим к формированию конфигурации расширения. 
Открываем файл `composer.json` и заполяем его следующими строками:

```json
{
    "name": "wooppay/yii2-extname",
    "type": "yii2-extension",
    "minimum-stability": "dev",
    "autoload": {
        "psr-4": {
             "woop\\extname\\": "src"
         }
    }
}
```

## Загрузка расширения

Чтобы загрузить Ваше расширение в проект, необходимо:

* объявить расширение в файле `composer.json` Вашего проекта
* обновить зависимости `composer update`

Редактируем файл `composer.json` Вашего проекта.

В сегменте `require` добавляем следующее:

```json
"wooppay/yii2-extname": "dev-master"
```
Если Вы не хотите регистрировать свое расширение в __Composer__, то можно прописать адрес репозитория в сегменте `repositories`:

```json
{
    "type": "vcs",
    "url": "https://github.com/wooppay/yii2-extname"
}
```
Это указывает, из какого репозитория загружать расширение.
