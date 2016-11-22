<?php

namespace yii2mod\linkpreview\tests\data;

/**
 * Class Controller
 *
 * @package yii2mod\linkpreview\tests\data
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function render($view, $params = [])
    {
        return [
            'view' => $view,
            'params' => $params,
        ];
    }
}
