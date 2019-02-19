<?php

namespace App\Dtos\Response\Article\Partials;
class ArticleListDataSection
{

    public static function build($pageMeta, $articles) {
        $articleSummaryDtos = [];
        foreach ($articles as $key => $article) {
            $articleSummaryDtos[] = ArticleSummaryDto::build($article);
        }
        return [
            'page_meta' => $pageMeta,
            'articles' => $articleSummaryDtos
        ];
    }
}
