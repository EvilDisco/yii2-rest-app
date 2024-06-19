<?php

namespace common\services\article\repositories;

use common\services\article\builders\ArticleDtoBuilder;
use common\models\Article\Article;
use common\services\article\dtos\ArticleDto;
use common\services\PaginationHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ArticleRepository
{
    public function findAllAsDataProvider(): ActiveDataProvider
    {
        $params = Yii::$app->request->get();

        $query = Article::find()
            ->innerJoinWith('author')
            ->innerJoinWith('categories')
            ->groupBy('article.id')
        ;

        if (isset($params['title'])) {
            $query
                ->andFilterWhere(['LIKE', 'article.title', $params['title']])
            ;
        }

        if (isset($params['author_id'])) {
            $query
                ->andFilterWhere(['=', 'article_author.id', $params['author_id']])
            ;
        }

        if (isset($params['author_full_name'])) {
            $query
                ->andFilterWhere(['LIKE', 'article_author.full_name', $params['author_full_name']])
            ;
        }

        if (isset($params['category_id'])) {
            $query
                ->andFilterWhere(['=', 'article_category.id', $params['category_id']])
            ;
        }

        if (isset($params['category_name'])) {
            $query
                ->andFilterWhere(['LIKE', 'article_category.name', $params['category_name']])
            ;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => PaginationHelper::getPerPage(),
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'title' => [
                        'asc' => ['title' => SORT_ASC],
                        'desc' => ['title' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        $builtModels = ArticleDtoBuilder::buildFromActiveRecords($dataProvider->getModels());
        $dataProvider->setModels($builtModels);

        return $dataProvider;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findSingleAsDto($id): ArticleDto
    {
        $model = Article::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Статья не найдена');
        }

        return ArticleDtoBuilder::buildFromActiveRecord($model);
    }
}