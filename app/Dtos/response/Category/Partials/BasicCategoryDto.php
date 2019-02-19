<?php


namespace App\Dtos\response\Category\Partials;


class BasicCategoryDto
{

    public static function build($category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];
    }
}