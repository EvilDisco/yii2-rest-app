<?php

namespace common\models\Article;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property int|null $parent_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ArticleCategory|null $parent
 * @property Article[] $articles
 */
final class ArticleCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%article_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'desc'], 'required'],
            [['name', 'desc'], 'string'],
            [['parent_id'], 'integer'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [
                ['parent_id'],
                'exist',
                'skipOnEmpty' => true,
                'targetClass' => ArticleCategory::class,
                'targetAttribute' => ['parent_id' => 'id'],
            ],
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
            'name' => 'Название',
            'desc' => 'Описание',
            'parent_id' => 'Родительская категория',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'desc',
            'parent', // FIXME: ограничение глубины
        ];
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasOne(ArticleCategory::class, ['id' => 'parent_id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getArticles(): ActiveQuery
    {
        return $this->hasMany(Article::class, ['id' => 'article_id'])
            ->viaTable(Article::CATEGORY_JUNCTION_TABLE_NAME, ['category_id' => 'id'])
        ;
    }
}