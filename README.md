Yii2 Link Preview Widget
==========

LinkPreview widget automatically retrieves some information from the content of the link.

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
1. Execute init migration
```php
       php yii migrate/up --migrationPath=@vendor/yii2mod/yii2-link-preview/migrations
```    

2. Define preview action in your controller:
```php
       public function actions()
       {
           return [
               'link-preview' => LinkPreviewAction::className()
           ];
       }
```     
  
3. Add widget to your page as follows:
```php
       echo LinkPreview::widget([
           'id' => 'your-input-id',
           'clientOptions' => [
               'previewActionUrl' => \yii\helpers\Url::to(['link-preview'])
           ],
       ])
```   