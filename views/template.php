<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $result array data from request */
/* @var $linkPreviewModel \yii2mod\linkpreview\models\LinkPreviewModel */
?>
<?php Pjax::begin([
    'timeout' => 20000,
    'enablePushState' => false,
    'id' => 'link-preview-pjax-container',
]); ?>
<?php if (!empty($result)): ?>
    <div class="preview-container">
        <div class="media">
            <?php if (!empty($result['image'])): ?>
                <?php echo Html::img($result['image'], ['id' => 'preview-image', 'class' => 'pull-left']); ?>
            <?php endif; ?>
            <div class="media-body fnt-smaller">
                <span title="Close" id="close-preview" class="close-preview-btn"></span>
                <h4 class="media-heading" id="preview-title"><?php echo $result['title']; ?></h4>
                <span id="preview-url"><?php echo $result['canonicalUrl']; ?></span>

                <p class="hidden-xs" id="preview-description"><?php echo $result['description']; ?></p>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'image', ['value' => $result['image']]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'title', ['value' => $result['title']]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'url', ['value' => $result['url']]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'canonicalUrl', ['value' => $result['canonicalUrl']]); ?>
                <?php echo Html::activeHiddenInput($linkPreviewModel, 'description', ['value' => $result['description']]); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php Pjax::end(); ?>