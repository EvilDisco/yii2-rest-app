<?php

namespace common\services\article\builders;

use common\models\Article\ArticleAuthor;
use common\services\article\dtos\ArticleAuthorDto;

class ArticleAuthorDtoBuilder
{
    public static function buildFromActiveRecord(ArticleAuthor $model): ArticleAuthorDto
    {
        return new ArticleAuthorDto(
            $model->id,
            $model->full_name,
            $model->birth_year,
            $model->bio
        );
    }

    public static function buildFromActiveRecords(array $models): array
    {
        $dtos = [];

        foreach ($models as $model) {
            if (!($model instanceof ArticleAuthor)) {
                continue;
            }

            $dtos[] = self::buildFromActiveRecord($model);
        }

        return $dtos;
    }
}