<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/* @var $pageInfo \Embed\Adapters\AdapterInterface data from request */
/* @var $linkPreviewModel \yii2mod\linkpreview\models\LinkPreviewModel */
/* @var $pjaxContainerId string */

?>
<?php Pjax::begin(['timeout' => 5000, 'enablePushState' => false, 'id' => $pjaxContainerId]); ?>
<?php if (!empty($pageInfo)): ?>
    <div class="preview-container">
        <div class="media">
            <span title="Close" id="close-preview" class="close-preview-btn"></span>
            <?php if ($pageInfo->getImage()): ?>
                <?php echo Html::img($pageInfo->getImage(), ['id' => 'preview-image', 'class' => 'pull-left']); ?>
            <?php endif; ?>
            <div class="media-body fnt-smaller">
                <h4 class="media-heading" id="preview-title">
                    <?php echo Html::a($pageInfo->getTitle(), $pageInfo->getUrl(), ['target' => '_blank']); ?>
                </h4>
                <p id="preview-description">
                    <?php echo StringHelper::truncateWords($pageInfo->getDescription(), 50); ?>
                </p>
                <span id="preview-url">
                    <?php echo $pageInfo->getRequest()->getHost(); ?>
                </span>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'image', ['value' => $pageInfo->getImage()]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'title', ['value' => $pageInfo->getTitle()]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'url', ['value' => $pageInfo->getUrl()]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'canonicalUrl', ['value' => $pageInfo->getRequest()->getHost()]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'description', ['value' => $pageInfo->getDescription()]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'code', ['value' => $pageInfo->getCode()]); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php Pjax::end(); ?>
