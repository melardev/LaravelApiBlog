<?php


use App\Dtos\Response\Article\Partials\ArticleElementalDto;
use App\Dtos\Response\User\Partials\UserOnlyUsernameDto;
use App\Models\Article;
use App\Models\Comment;

class CommentDetailsDto
{

    public static function build(Comment $comment)
    {
        $data = [
            // SuccessResponse::build(),
            'id' => $comment->id,
            'user' => UserOnlyUsernameDto::build($comment->user),
            'content' => $comment->content,
            'created_at' => $comment->createdAt,
            'updated_at' => $comment->updatedAt
        ];
        if ($comment->commentable_id !== null) {
            $parts = preg_split('/\\\\/', $comment->commentable_type);
            $model = strtolower(end($parts));
            if ($comment->commentable_type == Article::class)
                $data['article'] = ArticleElementalDto::build($comment->commentable);
            else if ($comment->commentable_type === Comment::class)
                $data['comment'] = ['id' => $comment->commentable_id];
        }

        return $data;
    }

}

