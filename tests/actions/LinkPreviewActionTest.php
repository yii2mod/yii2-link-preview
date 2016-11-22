<?php

namespace yii2mod\linkpreview\tests\actions;

use Yii;
use yii2mod\linkpreview\actions\LinkPreviewAction;
use yii2mod\linkpreview\tests\TestCase;

/**
 * Class LinkPreviewActionTest
 *
 * @package yii2mod\linkpreview\tests\actions
 */
class LinkPreviewActionTest extends TestCase
{
    /**
     * Runs the action.
     *
     * @param array $config
     *
     * @return array|\yii\web\Response response
     */
    protected function runAction(array $config = [])
    {
        $action = new LinkPreviewAction('link-preview', $this->createController(), $config);

        return $action->run();
    }

    // Tests:

    public function testViewLinkPreview()
    {
        Yii::$app->request->bodyParams = [
            'content' => 'some content https://github.com/yii2mod',
        ];

        $response = $this->runAction();
        $this->assertEquals('@vendor/yii2mod/yii2-link-preview/views/template', $response['view']);
        $this->assertNotEmpty($response['params']['pageInfo']->getTitle());
    }

    public function testNoPreview()
    {
        Yii::$app->request->bodyParams = [
            'content' => 'some content',
        ];

        $response = $this->runAction();
        $this->assertEmpty($response['params']['pageInfo']);
    }
}
