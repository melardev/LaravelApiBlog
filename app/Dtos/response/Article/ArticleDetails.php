<?php

use App\Dtos\response\Category\Partials\BasicCategoryDto;
use App\Dtos\response\Tag\Partial\BasicTagDto;
use App\Dtos\Response\User\Partials\UserUsernameAndPicDto;

class ArticleDetailsDto
{

    public static function build($article)
    {
        $commentDtos = [];
        foreach ($article->comments as $comment)
            $commentDtos[] = CommentDetailsDto::build($comment);
        $categoryDtos = [];
        foreach ($article->categories as $category)
            $categoryDtos[] = BasicCategoryDto::build($category);

        $tagDtos = [];
        foreach ($article->tags as $tag)
            $tagDtos[] = BasicTagDto::build($tag);

        return [
            'id' => $article->id,
            'user' => UserUsernameAndPicDto::build($article->user),
            'title' => $article->title,
            'slug' => $article->slug,
            'description' => $article->description,
            'likes_count' => (int)$article->likes_count,
            'comments' => $commentDtos,
            'categories' => $categoryDtos,
            'tags' => $tagDtos,
            'views' => (int)$article->views,
        ];
    }


}
