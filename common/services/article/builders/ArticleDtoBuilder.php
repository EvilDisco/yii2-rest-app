<?php

namespace common\services\article\builders;

use common\models\Article\Article;
use common\services\article\dtos\ArticleDto;
use common\services\FileService;

class ArticleDtoBuilder
{
    public static function buildFromActiveRecord(Article $model): ArticleDto
    {
        return new ArticleDto(
            $model->id,
            $model->title,
            $model->text,
            $model->preview,
            // FIXME: разобраться с путем для thumbnails
            //Yii::$app->thumbnailer->get('/uploads/' . $model->image),
            FileService::getAbsoluteUploadPath($model->image),
            ArticleAuthorDtoBuilder::buildFromActiveRecord($model->author),
            ArticleCategoryDtoBuilder::buildFromActiveRecords($model->categories),
        );
    }

    public static function buildFromActiveRecords(array $models): array
    {
        $dtos = [];

        foreach ($models as $model) {
            if (!($model instanceof Article)) {
                continue;
            }

            $dtos[] = self::buildFromActiveRecord($model);
        }

        return $dtos;
    }
}