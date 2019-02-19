<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\Resource;

class Article extends Resource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            // 'created_at' => $this->posted_at->toIso8601String(),
            'user_id' => $this->user_id,
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            // 'thumbnail_url' => $this->when($this->hasThumbnail(), url(optional($this->thumbnail)->getUrl())),
        ];
    }
}