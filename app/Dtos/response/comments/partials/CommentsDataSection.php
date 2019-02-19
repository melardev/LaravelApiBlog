<?php

use App\Dtos\Response\AbstractPagedDto;

class CommentsDataSection extends AbstractPagedDto
{

    public static function build($pageMeta, $comments)
    {

        $commentArrayList = [];
        foreach ($comments as $key => $comment) {
            $commentArrayList[] = CommentDetailsDto::build($comment);
        }

        return [
            'page_meta' => $pageMeta,
            'comments' => $commentArrayList
        ];
    }

}
