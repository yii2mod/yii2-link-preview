<?php

namespace yii2mod\linkpreview\models;

use Yii;
use yii\db\ActiveRecord;
use yii2mod\behaviors\PurifyBehavior;

/**
 * This is the model class for table "LinkPreview".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $canonicalUrl
 * @property string $image
 * @property string $code
 * @property integer $createdAt
 * @property integer $updatedAt
 */
class LinkPreviewModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'LinkPreview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'url', 'canonicalUrl', 'image', 'code'], 'trim'],
            [['url', 'canonicalUrl'], 'required'],
            [['image', 'title', 'description', 'code'], 'string'],
            [['createdAt', 'updatedAt'], 'integer'],
            [['url', 'canonicalUrl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'url' => Yii::t('app', 'Url'),
            'canonicalUrl' => Yii::t('app', 'Canonical Url'),
            'image' => Yii::t('app', 'Image'),
            'code' => Yii::t('app', 'Code'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt'
            ],
            'purify' => [
                'class' => PurifyBehavior::className(),
                'attributes' => ['title', 'description']
            ]
        ];
    }

    /**
     * Save model and return id
     *
     * @param array $params
     * @return integer|null
     */
    public static function saveAndGetId($params)
    {
        $model = new static;

        if ($model->load($params) && $model->save()) {
            return $model->id;
        }

        return null;
    }
}