<?php

namespace backend\controllers\api\v1;

use common\models\Article\ArticleAuthor;
use yii\data\ActiveDataFilter;
use yii\rest\ActiveController;

class AuthorController extends ActiveController
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

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
            'searchModel' => $this->modelClass,
        ];

        return $actions;
    }
}