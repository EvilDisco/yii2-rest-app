<?php

namespace backend\controllers;

use common\models\Article\ArticleCategory;
use yii\data\ActiveDataFilter;
use yii\rest\ActiveController;

class ArticleCategoryController extends ActiveController
{
    public $modelClass = ArticleCategory::class;

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