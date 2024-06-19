<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

class ArticleCest
{
    private const API_BASE_PATH = '/api/v1/articles';

    public function testGetArticlesList(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'title' => 'string',
                'preview' => 'string',
                'text' => 'string',
                'image' => 'string:url',
                'author' => [
                    'id' => 'integer',
                    'full_name' => 'string',
                    'birth_year' => 'integer',
                    'bio' => 'string',
                ],
                'categories' => 'array',
            ],
            '$.[0]'
        );
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'name' => 'string',
                'desc' => 'string',
                'parent' => 'string|null',
            ],
            '$.[0].categories[0]'
        );
    }

    public function testGetArticlesListPagination(ApiTester $I): void
    {
        $response = $I->sendGet(self::API_BASE_PATH . '?per-page=1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->assertEquals(1, count(json_decode($response, true)));
    }

    public function testGetSingleArticle(ApiTester $I): void
    {
        $I->sendGet(self::API_BASE_PATH . '/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string',
            'preview' => 'string',
            'text' => 'string',
            'image' => 'string:url',
            'author' => [
                'id' => 'integer',
                'full_name' => 'string',
                'birth_year' => 'integer',
                'bio' => 'string',
            ],
            'categories' => 'array',
        ]);
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'name' => 'string',
                'desc' => 'string',
                'parent' => 'string|null',
            ],
            '$.categories[0]'
        );
    }

    public function testNotFoundSingleArticle(ApiTester $I): void
    {
        $I->sendDelete(self::API_BASE_PATH . '/528491');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}