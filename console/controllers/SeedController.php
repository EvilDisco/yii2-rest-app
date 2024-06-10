<?php

namespace console\controllers;

use common\models\Article\Article;
use common\models\Article\ArticleAuthor;
use common\models\Article\ArticleCategory;
use Faker\Factory;
use Faker\Generator;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\helpers\BaseConsole;

final class SeedController extends Controller
{
    private const COUNT_ARTICLE_AUTHORS = 15;
    private const COUNT_ARTICLE_CATEGORIES = 15;
    private const COUNT_ARTICLES = 50;

    private Generator $faker;

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function actionArticles(): int
    {
        $this->faker = Factory::create('ru_RU');

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $this->emptyTables();

            $authors = $this->seedArticleAuthors();
            $categories = $this->seedArticleCategories();
            $this->seedArticles($authors, $categories);

            $transaction->commit();

            $this->stdout(
                $this->ansiFormat(
                    'Данные успешно записаны в базу' . PHP_EOL,
                    BaseConsole::FG_GREEN
                )
            );
        } catch (Exception|Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return ExitCode::OK;
    }

    private function emptyTables(): void
    {
        Article::deleteAll();
        ArticleCategory::deleteAll();
        ArticleAuthor::deleteAll();
    }

    /**
     * @throws Exception
     */
    private function seedArticleAuthors(): array
    {
        $authors = [];

        for ($i = 0; $i < self::COUNT_ARTICLE_AUTHORS; $i++) {
            $author = new ArticleAuthor();
            $author->full_name = $this->faker->name();
            $author->birth_year = $this->faker->year();
            $author->bio = $this->faker->text();
            $author->save();

            $authors[] = $author;
        }

        return $authors;
    }

    /**
     * @throws Exception
     */
    private function seedArticleCategories(): array
    {
        $categories = [];

        for ($i = 0; $i < self::COUNT_ARTICLE_CATEGORIES; $i++) {
            $category = new ArticleCategory();
            $category->name = $this->faker->words(3, true);
            $category->desc = $this->faker->text();

            if ($i > 0) {
                $category->parent_id = $this->faker->boolean()
                    ? $this->faker->randomElement($categories)->id
                    : null
                ;
            }

            $category->save();

            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @throws Exception
     */
    private function seedArticles(array $authors, array $categories): void
    {
        for ($i = 0; $i < self::COUNT_ARTICLES; $i++) {
            $article = new Article();
            $article->title = $this->faker->words(5, true);
            $article->preview = $this->faker->text(50);
            $article->text = $this->faker->text();
            $article->image = $this->faker->words(2, true);

            $article->link('author', $this->faker->randomElement($authors));

            for ($j = 0; $j < rand(1, 3); $j++) {
                $article->link('categories', $this->faker->randomElement($categories));
            }

            $article->save();
        }
    }
}