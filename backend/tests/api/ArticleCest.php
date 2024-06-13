<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

class ArticleCest
{
    private const API_BASE_PATH = '/articles';

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
                'parent' => 'array|null',
            ],
            '$.[0].categories[0]'
        );
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
                'parent' => 'array|null',
            ],
            '$.categories[0]'
        );
    }

    public function testDeleteArticleMethodIsDisabled(ApiTester $I): void
    {
        $I->sendDelete(self::API_BASE_PATH . '/1');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED);
    }
}