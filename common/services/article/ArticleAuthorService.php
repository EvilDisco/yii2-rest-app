<?php

namespace common\services\article;

use common\services\article\dtos\ArticleAuthorDto;
use common\services\article\repositories\ArticleAuthorRepository;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

readonly class ArticleAuthorService
{
    public function __construct(
        private ArticleAuthorRepository $repo
    ) {}

    public function getAllAsDataProvider(): ActiveDataProvider
    {
        return $this->repo->findAllAsDataProvider();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getSingleAsDto($id): ArticleAuthorDto
    {
        return $this->repo->findSingleAsDto($id);
    }
}