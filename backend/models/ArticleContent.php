<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_content".
 *
 * @property int $id
 * @property string $detail 内容
 * @property int $article_id 文章Id
 */
class ArticleContent extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail' => '内容',
            'article_id' => '文章Id',
        ];
    }
}
