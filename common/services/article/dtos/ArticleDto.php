<?php

namespace common\services\article\dtos;

readonly class ArticleDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public string $preview,
        public string $image,
        public ArticleAuthorDto $author,
        public iterable $categories
    ) {}
}