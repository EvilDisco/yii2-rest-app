<?php

namespace backend\controllers;

use common\models\Article\ArticleAuthor;
use yii\rest\ActiveController;

class ArticleAuthorController extends ActiveController
{
    public $modelClass = ArticleAuthor::class;

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