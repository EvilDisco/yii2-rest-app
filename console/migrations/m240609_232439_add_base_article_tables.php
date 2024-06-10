<?php

use common\models\Article\Article;
use common\models\Article\ArticleAuthor;
use common\models\Article\ArticleCategory;
use yii\db\Migration;

/**
 * Class m240609_232439_add_base_article_tables
 */
class m240609_232439_add_base_article_tables extends Migration
{
    private const FOREIGN_KEY_ARTICLE_AUTHOR = 'fk-article-author';
    private const FOREIGN_KEY_ARTICLE_CATEGORY_PARENT = 'fk-article-category-parent';
    private const FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_ARTICLE = 'fk-article-article-category-article-id';
    private const FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_CATEGORY = 'fk-article-article-category-category-id';

    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            ArticleAuthor::tableName(),
            [
                'id' => $this->primaryKey(),
                'full_name' => $this->string()->notNull(),
                'bio' => $this->string()->notNull(),
                'birth_year' => $this->integer(4)->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );

        $this->createTable(
            ArticleCategory::tableName(),
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'desc' => $this->string()->notNull(),
                'parent_id' => $this->integer()->null(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            self::FOREIGN_KEY_ARTICLE_CATEGORY_PARENT,
            ArticleCategory::tableName(),
            'parent_id',
            ArticleCategory::tableName(),
            'id',
            'SET NULL'
        );

        $this->createTable(
            Article::tableName(),
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'preview' => $this->string()->notNull(),
                'text' => $this->string()->notNull(),
                'image' => $this->string()->notNull(),
                'author_id' => $this->integer()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            self::FOREIGN_KEY_ARTICLE_AUTHOR,
            Article::tableName(),
            'author_id',
            ArticleAuthor::tableName(),
            'id',
            'CASCADE'
        );

        $this->createTable(
            Article::CATEGORY_JUNCTION_TABLE_NAME,
            [
                'article_id' => $this->integer()->notNull(),
                'category_id' => $this->integer()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            self::FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_ARTICLE,
            Article::CATEGORY_JUNCTION_TABLE_NAME,
            'article_id',
            Article::tableName(),
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            self::FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_CATEGORY,
            Article::CATEGORY_JUNCTION_TABLE_NAME,
            'category_id',
            ArticleCategory::tableName(),
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(self::FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_CATEGORY, Article::CATEGORY_JUNCTION_TABLE_NAME);
        $this->dropForeignKey(self::FOREIGN_KEY_ARTICLE_CATEGORY_JUNCTION_ARTICLE, Article::CATEGORY_JUNCTION_TABLE_NAME);
        $this->dropTable(Article::CATEGORY_JUNCTION_TABLE_NAME);

        $this->dropForeignKey(self::FOREIGN_KEY_ARTICLE_AUTHOR, Article::tableName());
        $this->dropTable(Article::tableName());

        $this->dropForeignKey(self::FOREIGN_KEY_ARTICLE_CATEGORY_PARENT, ArticleCategory::tableName());
        $this->dropTable(ArticleCategory::tableName());

        $this->dropTable(ArticleAuthor::tableName());
    }
}
