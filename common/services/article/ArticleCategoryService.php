<?php

namespace common\services\article;

use common\services\article\dtos\ArticleCategoryDto;
use common\services\article\repositories\ArticleCategoryRepository;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

readonly class ArticleCategoryService
{
    public function __construct(
        private ArticleCategoryRepository $repo
    ) {}

    public function getAllAsDataProvider(): ActiveDataProvider
    {
        return $this->repo->findAllAsDataProvider();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getSingleAsDto($id): ArticleCategoryDto
    {
        return $this->repo->findSingleAsDto($id);
    }
}