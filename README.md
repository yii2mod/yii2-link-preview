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
**Example of usage with the ActiveForm and saving the page info**

1) Create the basic form in the view:
```php
<?php $form = \yii\widgets\ActiveForm::begin() ?>
    <div class="form-group">
        <label for="preview">Preview</label>
        <input name="preview" class="form-control" id="preview" placeholder="Preview">
    </div>
    <?php echo \yii2mod\linkpreview\LinkPreview::widget([
        'selector' => '#preview',
        'clientOptions' => [
            'previewActionUrl' => \yii\helpers\Url::to(['link-preview'])
        ],
    ]) ?>
    <div class="form-group">
        <?= \yii\helpers\Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
<?php \yii\widgets\ActiveForm::end() ?>
```

2) Add the following code to your action for the saving page info:
```php
$model = new LinkPreviewModel();
if ($model->load(Yii::$app->request->post()) && $model->validate()) {
    $model->save();
}

// or the short version 

$linkPreviewId = LinkPreviewModel::saveAndGetId(Yii::$app->request->post());

```

#### GitHub Preview
-----
![Alt text](http://res.cloudinary.com/zfort/image/upload/v1436190465/Preview.png "Example preview")
