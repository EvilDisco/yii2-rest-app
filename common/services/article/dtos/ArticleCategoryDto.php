<?php

namespace common\services\article\dtos;

class ArticleCategoryDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $desc,
        public ArticleCategoryDto|string|null $parent = null
    ) {}
}