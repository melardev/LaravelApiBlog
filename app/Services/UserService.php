<?php

namespace App\Services;


use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function getAuthenticatedUser() {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['success' => false,
                                            'full_messages' => ['User not found',]], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['success' => false,
                                        'full_messages' => ['Expired', $e->getStatusCode(),]], 404);

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['success' => false,
                                        'full_messages' => ['Invalid', $e->getStatusCode(),]], 404);

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['success' => false,
                                        'full_messages' => ['Absent', $e->getStatusCode(),]], 404);
        }

        //return response()->json(compact('user'));
        return $user;
    }
}
