Yii2 Link Preview Widget
===========

LinkPreview widget automatically retrieves some information from the content of the link.

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-link-preview/v/stable)](https://packagist.org/packages/yii2mod/yii2-link-preview) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-link-preview/downloads)](https://packagist.org/packages/yii2mod/yii2-link-preview) [![License](https://poser.pugx.org/yii2mod/yii2-link-preview/license)](https://packagist.org/packages/yii2mod/yii2-link-preview)

Installation 
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2mod/yii2-link-preview "*"
```

or add

```
"yii2mod/yii2-link-preview": "*"
```

to the require section of your `composer.json` file.


Usage
-----
1) Execute init migration:
```php
php yii migrate/up --migrationPath=@vendor/yii2mod/yii2-link-preview/migrations
```    

2) Define preview action in your controller:
```php
public function actions()
{
    return [
        'link-preview' => LinkPreviewAction::className()
    ];
}
```     
 
3) Add widget to your page as follows:
```php
echo LinkPreview::widget([
    'selector' => '#your-input-id or .someclass',
    'clientOptions' => [
        'previewActionUrl' => \yii\helpers\Url::to(['link-preview'])
    ],
])
```  
#### Example preview
-----
![Alt text](http://res.cloudinary.com/zfort/image/upload/v1436190465/Preview.png "Example preview")
