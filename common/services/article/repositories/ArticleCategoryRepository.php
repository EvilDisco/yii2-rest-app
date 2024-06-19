<?php

namespace common\services\article\repositories;

use common\models\Article\ArticleCategory;
use common\services\article\builders\ArticleCategoryDtoBuilder;
use common\services\article\dtos\ArticleCategoryDto;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ArticleCategoryRepository
{
    public function findAllAsDataProvider(int $pageSize = 20): ActiveDataProvider
    {
        $params = Yii::$app->request->get();

        $query = ArticleCategory::find();

        if (isset($params['name'])) {
            $query
                ->andFilterWhere(['LIKE', 'name', $params['name']])
            ;
        }

        if (isset($params['parent_id'])) {
            $query
                ->andFilterWhere(['=', 'parent_id', $params['parent_id']])
            ;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'name' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        $builtModels = ArticleCategoryDtoBuilder::buildFromActiveRecords($dataProvider->getModels());
        $dataProvider->setModels($builtModels);

        return $dataProvider;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findSingleAsDto($id): ArticleCategoryDto
    {
        $model = ArticleCategory::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return ArticleCategoryDtoBuilder::buildFromActiveRecord($model);
    }
}