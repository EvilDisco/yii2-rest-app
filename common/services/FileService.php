<?php

namespace common\services;

use DateTime;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Url;

// TODO: helper?
class FileService
{
    /**
     * @throws Exception
     */
    public static function prepareUploadFolder(): string
    {
        $imageFolder = Yii::getAlias('@uploads') . (new DateTime())->format('/Y/m/d');
        FileHelper::createDirectory($imageFolder, 0777);

        return $imageFolder;
    }

    public static function getRelativeUploadPath(string $serverPath): string
    {
        return str_replace(Yii::getAlias('@uploads') . '/', '', $serverPath);
    }

    public static function getAbsoluteUploadPath(string $relativePath): string
    {
        return Url::base(true) . '/uploads/' . $relativePath;
    }
}