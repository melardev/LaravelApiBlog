<?php

namespace App\Dtos\Response\Article\Partials;

use App\Dtos\Response\User\Partials\UserUsernameAndPicDto;

class ArticleSummaryDto
{

    public static function build($article)
    {
        return [
            'id' => $article->id,
            'user' => UserUsernameAndPicDto::build($article->user),
            'title' => $article->title,
            'slug' => $article->slug,
            'description' => $article->description,
            'likes_count' => (int)$article->likes_count,
            'comments_count' => (int)$article->comments_count,
            'views' => (int)$article->views,
        ];
    }

}
