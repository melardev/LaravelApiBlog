<?php

namespace App\Dtos\response\Tag\Partial;


class BasicTagDto
{

    public static function build($tag) {
        return [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
        ];
    }
}