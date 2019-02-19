<?php

namespace App\Dtos\Response;


use App\Dtos\Response\Shared\PageMeta;
use App\Dtos\Response\Shared\SuccessResponse;
use CommentsDataSection;


class CommentListDto
{

    public static function build($comments, $base_path = '/articles')
    {
        $commentsDataSection = CommentsDataSection::build(PageMeta::build($comments, $base_path), $comments->items());
        return array_merge(SuccessResponse::build(), [
            'data' => $commentsDataSection
        ]);
    }

}
