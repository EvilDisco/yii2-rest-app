<?php

namespace backend\controllers\api\v1;

use common\services\article\ArticleCategoryService;
use common\services\article\dtos\ArticleCategoryDto;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class CategoryController extends Controller
{
    public function __construct($id, $module, readonly private ArticleCategoryService $categoryService, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): array
    {
        $dataProvider = $this->categoryService->getAllAsDataProvider();

        return $dataProvider->getModels();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): ArticleCategoryDto
    {
        return $this->categoryService->getSingleAsDto($id);
    }
}