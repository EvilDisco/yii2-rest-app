<?php

namespace common\services\article;

use common\services\article\dtos\ArticleDto;
use common\services\article\repositories\ArticleRepository;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

readonly class ArticleService
{
    public function __construct(
        private ArticleRepository $repo
    ) {}

    public function getAllAsDataProvider(): ActiveDataProvider
    {
        return $this->repo->findAllAsDataProvider();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getSingleAsDto($id): ArticleDto
    {
        return $this->repo->findSingleAsDto($id);
    }
}