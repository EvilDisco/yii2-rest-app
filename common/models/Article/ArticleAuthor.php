<?php

namespace common\models\Article;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property string $full_name
 * @property int $birth_year
 * @property string $bio
 * @property int $created_at
 * @property int $updated_at
 */
final class ArticleAuthor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%article_author}}';
    }

    public function rules(): array
    {
        return [
            [['full_name', 'bio', 'birth_year'], 'required'],
            [['full_name', 'bio'], 'string'],
            [['birth_year'], 'integer'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'full_name' => 'ФИО',
            'birth_year' => 'Год рождения',
            'bio' => 'Биография',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'full_name',
            'birth_year',
            'bio',
        ];
    }
}