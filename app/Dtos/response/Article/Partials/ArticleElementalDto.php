<?php

namespace App\Dtos\Response\Article\Partials;
class ArticleElementalDto
{
    public static function build($article) {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
        ];
    }
}