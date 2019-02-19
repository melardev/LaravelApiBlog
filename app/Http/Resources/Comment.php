<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Comment extends Resource
{


    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // We need to create an App\Models\Comment because the registred policy in
        // AuthServiceProvider is App\Models\Comment and not App\Http\Resources\Comment.
        // I didn't find another way to make this cleaner.
        $comment = new \App\Models\Comment($this->getAttributes());
        $user = Auth::guard('api')->user();

        return [
            'id' => $this->id,
            'content' => $this->content,
            'user_id' => $this->user_id,
            'article_id' => $this->article_id,
            'can_delete' => $user ? $user->can('delete', $comment) : false
        ];
    }
}