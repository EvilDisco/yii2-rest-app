<?php

namespace backend\controllers\api\v1;

use common\services\article\ArticleAuthorService;
use common\services\article\dtos\ArticleAuthorDto;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    public function __construct($id, $module, readonly private ArticleAuthorService $authorService, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): array
    {
        $dataProvider = $this->authorService->getAllAsDataProvider();

        return $dataProvider->getModels();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): ArticleAuthorDto
    {
        return $this->authorService->getSingleAsDto($id);
    }
}