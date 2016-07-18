<?php

namespace yii2mod\linkpreview\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii2mod\linkpreview\models\LinkPreviewModel;

/**
 * Class LinkPreviewAction
 * @package yii2mod\linkpreview\actions
 */
class LinkPreviewAction extends Action
{
    /**
     * @var array crawler config
     */
    public $crawlerConfig = [
        'class' => 'yii2mod\linkpreview\Crawler'
    ];

    /**
     * @var string template view path
     */
    public $view = '@vendor/yii2mod/yii2-link-preview/views/template';

    /**
     * Runs the action
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $linkPreviewModel = new LinkPreviewModel();
        $crawler = Yii::createObject($this->crawlerConfig);
        $crawler->content = Yii::$app->request->post('content');
        $pageInfo = $crawler->getPageInfo();
        $pjaxContainerId = str_replace('#', '', Yii::$app->request->post('_pjax'));

        return $this->controller->render($this->view, [
            'pageInfo' => $pageInfo,
            'linkPreviewModel' => $linkPreviewModel,
            'pjaxContainerId' => $pjaxContainerId
        ]);
    }
}