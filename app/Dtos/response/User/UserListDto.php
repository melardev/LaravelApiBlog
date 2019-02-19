<?php


namespace App\Dtos\Response\User;


use App\Dtos\Response\Users\partials\UserListDataSection;
use App\Dtos\SuccessResponse;
use App\Dtos\PageMeta;

class UserListDto
{
    public static function build($users, $base_path = '/articles') {
        $userListDataSection = UserListDataSection::build(PageMeta::build($users, $base_path), $users->items());
        return array_merge(SuccessResponse::build(), [
            'data' => $userListDataSection
        ]);
    }
}