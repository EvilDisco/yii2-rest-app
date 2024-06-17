<?php

namespace backend\controllers\api\v1;

use common\models\Article\Article;
use yii\data\ActiveDataFilter;
use yii\rest\ActiveController;

class ArticleController extends ActiveController
{
    public $modelClass = Article::class;

    public function actions(): array
    {
        $actions = parent::actions();

        unset(
            $actions['delete'],
            $actions['create'],
            $actions['update'],
        );

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
            'searchModel' => $this->modelClass,
        ];

        return $actions;
    }
}