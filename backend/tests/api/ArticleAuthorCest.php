<?php

namespace backend\tests\api;

use backend\tests\ApiTester;
use Codeception\Util\HttpCode;

class ArticleAuthorCest
{
    private const API_BASE_PATH = '/authors';

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

    public function testDeleteArticleAuthorMethodIsDisabled(ApiTester $I): void
    {
        $I->sendDelete(self::API_BASE_PATH . '/1');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED);
    }
}