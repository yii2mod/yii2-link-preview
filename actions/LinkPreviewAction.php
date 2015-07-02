<?php

namespace yii2mod\linkpreview\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii2mod\linkpreview\Crawler;
use yii2mod\linkpreview\models\LinkPreviewModel;

/**
 * Class LinkPreviewAction
 * @package yii2mod\linkpreview\actions
 */
class LinkPreviewAction extends Action
{
    /**
     * Template view path
     * @var string
     */
    public $view = '@vendor/yii2mod/yii2-link-preview/views/template';

    /**
     * Runs the action
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $linkPreviewModel = new LinkPreviewModel();
        $content = Yii::$app->request->post('content');
        $linkPreview = new Crawler([
            'content' => $content
        ]);
        $result = $linkPreview->getPagePreview();
        return $this->controller->render($this->view, [
            'result' => $result,
            'linkPreviewModel' => $linkPreviewModel
        ]);
    }

} 
