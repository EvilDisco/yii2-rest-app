<?php

namespace common\services\article\dtos;

class ArticleAuthorDto
{
    public function __construct(
        public int $id,
        public string $full_name,
        public int $birth_year,
        public string $bio
    ) {}
}