<?php

namespace App\Dtos\Response\User\Partials;

class UserOnlyUsernameDto
{

    public static function build($user)
    {
        return [
            'username' => $user->username
        ];
    }
}

?>
