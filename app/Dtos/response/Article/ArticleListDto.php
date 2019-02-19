<?php

namespace App\Dtos\Response\Article;

use App\Dtos\Response\Article\Partials\ArticleListDataSection;
use App\Dtos\Response\Shared\PageMeta;
use App\Dtos\Response\Shared\SuccessResponse;

class ArticleListDto
{

    public static function build($articles, $base_path = '/articles')
    {
        $articleListDataSection = ArticleListDataSection::build(PageMeta::build($articles, $base_path), $articles->items());
        return array_merge(SuccessResponse::build(), [
            'data' => $articleListDataSection
        ]);
    }


}
