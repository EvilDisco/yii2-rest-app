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
 * @property string $title
 * @property string $preview
 * @property string $text
 * @property string $image
 * @property int $author_id
 * @property int[] $category_ids
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ArticleAuthor $author
 * @property ArticleCategory[] $categories
 */
final class Article extends ActiveRecord
{
    public const CATEGORY_JUNCTION_TABLE_NAME = 'article_article_category';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%article}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'preview', 'text', 'image', 'author_id'], 'required'],
            [['title', 'preview', 'text', 'image'], 'string'],
            [['author_id'], 'integer'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ArticleAuthor::class,
                'targetAttribute' => ['author_id' => 'id'],
            ],
            // TODO: валидация категории через junction-таблицу
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
            'title' => 'Название',
            'preview' => 'Анонс',
            'text' => 'Текст',
            'image' => 'Изображение',
            'author' => 'Автор',
            'categories' => 'Категории',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'title',
            'preview',
            'text',
            'image',
            'author',
            'categories',
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(ArticleAuthor::class, ['id' => 'author_id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(ArticleCategory::class, ['id' => 'category_id'])
            ->viaTable(self::CATEGORY_JUNCTION_TABLE_NAME, ['article_id' => 'id'])
        ;
    }
}