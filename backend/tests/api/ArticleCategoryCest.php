<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

class ArticleCategoryCest
{
    private const API_BASE_PATH = '/api/v1/categories';

    public function testGetArticleCategoriesList(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'name' => 'string',
                'desc' => 'string',
                'parent' => 'string|null',
            ],
            '$.[0]'
        );
    }

    public function testGetSingleArticleCategory(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH . '/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'name' => 'string',
                'desc' => 'string',
                'parent' => 'string|null',
            ]
        );
    }

    public function testGetArticleCategoriesListPagination(ApiTester $I): void
    {
        $response = $I->sendGet(self::API_BASE_PATH . '?per-page=1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->assertEquals(1, count(json_decode($response, true)));
    }

    public function testNotFoundSingleArticleCategory(ApiTester $I): void
    {
        $I->sendDelete(self::API_BASE_PATH . '/528491');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}