<?php

namespace common\services\article\repositories;

use common\models\Article\ArticleAuthor;
use common\services\article\builders\ArticleAuthorDtoBuilder;
use common\services\article\dtos\ArticleAuthorDto;
use common\services\PaginationHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ArticleAuthorRepository
{
    public function findAllAsDataProvider(): ActiveDataProvider
    {
        $params = Yii::$app->request->get();

        $query = ArticleAuthor::find();

        if (isset($params['full_name'])) {
            $query
                ->andFilterWhere(['LIKE', 'full_name', $params['full_name']])
            ;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => PaginationHelper::getPerPage(),
            ],
            'sort' => [
                'defaultOrder' => ['full_name' => SORT_ASC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'full_name' => [
                        'asc' => ['full_name' => SORT_ASC],
                        'desc' => ['full_name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'birth_year' => [
                        'asc' => ['birth_year' => SORT_ASC],
                        'desc' => ['birth_year' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                ],
            ],
        ]);

        $builtModels = ArticleAuthorDtoBuilder::buildFromActiveRecords($dataProvider->getModels());
        $dataProvider->setModels($builtModels);

        return $dataProvider;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findSingleAsDto($id): ArticleAuthorDto
    {
        $model = ArticleAuthor::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Автор не найден');
        }

        return ArticleAuthorDtoBuilder::buildFromActiveRecord($model);
    }
}