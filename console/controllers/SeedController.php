<?php

namespace console\controllers;

use Bilions\FakerImages\FakerImageProvider;
use common\models\Article\Article;
use common\models\Article\ArticleAuthor;
use common\models\Article\ArticleCategory;
use common\services\FileService;
use Faker\Factory;
use Faker\Generator;
use Throwable;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\helpers\BaseConsole;
use yii\helpers\Console;
use yii\helpers\StringHelper;

final class SeedController extends Controller
{
    private const COUNT_ARTICLE_AUTHORS = 10;
    private const COUNT_ARTICLE_CATEGORIES = 10;
    private const COUNT_ARTICLES = 20;

    private Generator $faker;

    public bool $add = false;

    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), ['add']);
    }

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
            if (!$this->add) {
                // TODO: emptyTablesAndRemoveImages
                $this->emptyTables();
            }

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
        $this->stdout(
            $this->ansiFormat('Очищаем базовые таблицы (статьи, категории, авторы)...') . PHP_EOL
        );

        Article::deleteAll();
        ArticleCategory::deleteAll();
        ArticleAuthor::deleteAll();

        $this->stdout(
            $this->ansiFormat('Таблицы очищены') . PHP_EOL
        );

        $this->stdout(PHP_EOL);
    }

    /**
     * @throws Exception
     */
    private function seedArticleAuthors(): array
    {
        $this->stdout(
            $this->ansiFormat('Генерация авторов...') . PHP_EOL
        );

        Console::startProgress(0, self::COUNT_ARTICLE_AUTHORS);

        $genders = ['male', 'female'];
        $authors = [];

        for ($i = 0; $i < self::COUNT_ARTICLE_AUTHORS; $i++) {
            $gender = $genders[array_rand($genders)];

            $author = new ArticleAuthor();
            $author->full_name = $this->faker->lastName($gender)
                . ' ' . $this->faker->firstName($gender)
                . ' ' . $this->faker->middleName($gender)
            ;
            $author->birth_year = $this->faker->year();
            $author->bio = $this->faker->text();
            $author->save();

            $authors[] = $author;

            Console::updateProgress($i + 1,self::COUNT_ARTICLE_AUTHORS);
        }

        Console::endProgress('Готово' . PHP_EOL . PHP_EOL);

        return $authors;
    }

    /**
     * @throws Exception
     */
    private function seedArticleCategories(): array
    {
        $this->stdout(
            $this->ansiFormat('Генерация категорий...') . PHP_EOL
        );

        Console::startProgress(0, self::COUNT_ARTICLE_CATEGORIES);

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

            Console::updateProgress($i + 1,self::COUNT_ARTICLE_CATEGORIES);
        }

        Console::endProgress('Готово' . PHP_EOL . PHP_EOL);

        return $categories;
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    private function seedArticles(array $authors, array $categories): void
    {
        $this->stdout(
            $this->ansiFormat('Генерация статей...') . PHP_EOL
        );

        $this->faker->addProvider(new FakerImageProvider($this->faker));

        Console::startProgress(0, self::COUNT_ARTICLES);

        for ($i = 0; $i < self::COUNT_ARTICLES; $i++) {
            $article = new Article();
            $article->title = $this->faker->words(5, true);
            $article->text = $this->faker->text();
            $article->preview = StringHelper::truncateWords($article->text, 15);

            $imageFolder = FileService::prepareUploadFolder();
            $imageServerPath = $this->faker->image($imageFolder);
            $article->image = FileService::getRelativeUploadPath($imageServerPath);

            $article->link('author', $this->faker->randomElement($authors));

            for ($j = 0; $j < rand(1, 3); $j++) {
                $article->link('categories', $this->faker->randomElement($categories));
            }

            $article->save();

            Console::updateProgress($i + 1,self::COUNT_ARTICLES);
        }

        Console::endProgress('Готово' . PHP_EOL . PHP_EOL);
    }
}