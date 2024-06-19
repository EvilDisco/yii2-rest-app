<?php

namespace common\services;

use Yii;

class PaginationHelper
{
    public const DEFAULT_PER_PAGE = 20;

    public static function getPerPage(): int
    {
        $pageSize = self::DEFAULT_PER_PAGE;

        $params = Yii::$app->request->get();
        if (isset($params['per-page']) && filter_var($params['per-page'], FILTER_VALIDATE_INT)) {
            $pageSize = (int) $params['per-page'];
        }

        return $pageSize;
    }
}