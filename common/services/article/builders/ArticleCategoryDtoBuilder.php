<?php

namespace common\services\article\builders;

use common\models\Article\ArticleCategory;
use common\services\article\dtos\ArticleCategoryDto;

class ArticleCategoryDtoBuilder
{
    public static function buildFromActiveRecord(ArticleCategory $model): ArticleCategoryDto
    {
        $parent = null;
        if ($model->parent_id) {
            // TODO: универсальное получение IRI
            $parent = '/api/v1/categories/' . $model->parent_id;
        }

        return new ArticleCategoryDto(
            $model->id,
            $model->name,
            $model->desc,
            $parent
        );
    }

    public static function buildFromActiveRecords(array $models): array
    {
        $dtos = [];

        foreach ($models as $model) {
            if (!($model instanceof ArticleCategory)) {
                continue;
            }

            $dtos[] = self::buildFromActiveRecord($model);
        }

        return $dtos;
    }
}