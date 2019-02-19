<?php

namespace App\Dtos\response\subscriptions;


use App\Dtos\response\users\partials\UserListDataSection;
use App\Dtos\SuccessResponse;
use PageMeta;

class UserSubscriptionsList
{
    public static function build($relations, $base_path = '/articles', $render_following = true) {

        $users = [];
        foreach ($relations as $relation) {
            if ($render_following)
                $users[] = $relation->following;
            else
                $users[] = $relation->follower;
        }

        $userListDataSection = UserListDataSection::build(PageMeta::build($relations, $base_path), $users);
        return array_merge(SuccessResponse::build(), [
            'data' => $userListDataSection
        ]);
    }
}