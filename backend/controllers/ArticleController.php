<?php

namespace backend\controllers;

use common\models\Article\Article;
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

        return $actions;
    }
}