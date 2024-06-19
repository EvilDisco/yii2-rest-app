<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

class ArticleAuthorCest
{
    private const API_BASE_PATH = '/api/v1/authors';

    public function testGetArticleAuthorsList(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'full_name' => 'string',
                'birth_year' => 'integer',
                'bio' => 'string',
            ],
            '$.[0]'
        );
    }

    public function testGetArticleAuthorsListPagination(ApiTester $I): void
    {
        $response = $I->sendGet(self::API_BASE_PATH . '?per-page=1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->assertEquals(1, count(json_decode($response, true)));
    }

    public function testGetSingleArticleAuthor(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH . '/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'full_name' => 'string',
            'birth_year' => 'integer',
            'bio' => 'string',
        ]);
    }

    public function testNotFoundSingleArticleAuthor(ApiTester $I): void
    {
        $I->sendDelete(self::API_BASE_PATH . '/528491');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}