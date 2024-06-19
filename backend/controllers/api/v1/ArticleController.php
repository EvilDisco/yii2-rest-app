<?php

namespace backend\controllers\api\v1;

use common\services\article\ArticleService;
use common\services\article\dtos\ArticleDto;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller
{
    public function __construct($id, $module, readonly private ArticleService $articleService, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): array
    {
        $dataProvider = $this->articleService->getAllAsDataProvider();

        return $dataProvider->getModels();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): ArticleDto
    {
        return $this->articleService->getSingleAsDto($id);
    }
}