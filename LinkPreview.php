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
 *          'selector' => '#your-input-id or .someclass',
 *          'clientOptions' => [
 *              'previewActionUrl' => \yii\helpers\Url::to(['link-preview'])
 *          ],
 *      ])
 *  ~~~
 */
class LinkPreview extends Widget
{
    /**
     * @var string input selector
     */
    public $selector;

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
     * @var string pjax container id
     */
    public $pjaxContainerId = 'link-preview-container';

    /**
     * Init function
     */
    public function init()
    {
        if (empty($this->id)) {
            throw new InvalidConfigException("The 'id' property is required.");
        }
        if (empty($this->pjaxContainerId)) {
            throw new InvalidConfigException("The 'pjaxContainerId' property is required.");
        }
        echo $this->render($this->view, [
            'pjaxContainerId' => $this->pjaxContainerId
        ]);
        parent::init();
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        $view = $this->getView();
        LinkPreviewAsset::register($view);
        $options = $this->getClientOptions();
        $view->registerJs("$('{$this->selector}').linkPreview({$options});", $view::POS_END);
        parent::run();
    }

    /**
     * Get client options
     * @return string
     */
    protected function getClientOptions()
    {
        $this->clientOptions['pjaxContainer'] = '#' . $this->pjaxContainerId;
        return Json::encode($this->clientOptions);
    }

}
