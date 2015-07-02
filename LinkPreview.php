<?php

namespace yii2mod\linkpreview;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * LinkPreview widget renders page preview
 *
 *  ~~~
 * 1. Define preview action in your controller:
 *      public function actions()
 *      {
 *          return [
 *              'link-preview' => LinkPreviewAction::className()
 *          ];
 *      }
 * 2. Add widget to your page as follows:
 *      echo \app\components\preview\LinkPreview::widget([
 *          'id' => 'your-input-id',
 *          'clientOptions' => [
 *              'previewActionUrl' => \yii\helpers\Url::to(['link-preview'])
 *          ],
 *      ])
 *  ~~~
 */
class LinkPreview extends Widget
{
    /**
     * @var string your input or textArea id
     */
    public $id;

    /**
     * Template view name
     * @var string
     */
    public $view = 'template';

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * Init function
     */
    public function init()
    {
        if ($this->id === null) {
            throw new InvalidConfigException("The 'id' property is required.");
        }
        echo $this->render($this->view);
        parent::init();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $id = $this->id;
        $view = $this->getView();
        LinkPreviewAsset::register($view);
        $options = Json::encode($this->clientOptions);
        $view->registerJs("$('#{$id}').linkPreview({$options});", $view::POS_END);
        parent::run();
    }

}
