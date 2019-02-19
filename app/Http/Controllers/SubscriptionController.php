<?php

namespace App\Http\Controllers;

use App\Dtos\response\subscriptions\UserSubscriptionsList;
use App\Dtos\response\users\UserListDto;
use App\Models\User;
use App\Models\UserRelation;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends BaseController
{

    public function __construct() {
        $this->middleware('jwt.verify')->except('subscribeToWebsite');
    }

    public function subscribeToUser(Request $request) {
        $current_user = auth()->user();
        if ($current_user === null)
            return $this->sendError('You should be logged in to follow an author');

        $following = User::find($request->user_id);
        if ($following === null || !$following->isAuthor())
            return $this->sendError('You can not follow this user');

        $relation = [
            'following_id' => $following->id,
            'follower_id' => $current_user->id
        ];

        if (UserRelation::where($relation)->count() > 0)
            return $this->sendError('You are already subscribed to this author');

        $current_user->follow($following);
        // $ur = UserRelation::create($relation);


        return $this->sendSuccessResponse("Subscribed successfully to user {$following->id}");
    }

    public function unsubscribeFromUser(Request $request) {
        $current_user = auth()->user();
        $user_id = $request->user_id;
        $following = User::find($user_id);
        $relation = [
            'following_id' => $following->id,
            'follower_id' => $current_user->id
        ];
        $ur = UserRelation::where($relation)->first();
        if ($ur == null)
            return $this->sendError('You are not subscribed, fail');


        // if ($ur->delete()) // TODO: for some readon it returns true meaning success, but the row is not deleted
        if ($current_user->unFollow($following) == 1)
            return $this->sendSuccessResponse('Unsubscribed successfully');
        else
            return $this->sendError('Something went wrong');
    }

    public function subscribeToWebsite(Request $request) {
        $user = auth()->user();
        if ($user !== null) {
            $email = $user->email;
            $user_id = $user->id;
        } else {
            $user_id = null;
            $email = $request->get('email');
            if ($email === null)
                return $this->sendError('You must provide an email');

        }
        SiteSubscriptions::create([
                                      'user_id' => $user_id,
                                      'email' => $email]);
    }

    public function followers(Request $request) {

        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);

        return $this->sendSuccessResponse(Auth::user()->followers);
        /*
                //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
                Paginator::currentPageResolver(function () use ($page) {
                    return $page;
                });

                 $articles = Article::skip(($page - 1) * $page_size, $page_size)->take($page_size)->get()->toArray();

                // $perPage = null, $columns = ['*'], $pageName = 'page', $page = null
                // $articles = Article::paginate($page_size, ['*'], 'page', 5);
        // By reading the source code I see the page is set to $page from input request, page_size is set to paginate(page_size)
                // that means we do not have to setCurrentPageResolver because it works if we sent ?page= in the url
                $articles = Article::orderBy('created_at', 'desc')
                                   ->with(array('user' => function ($query) {
                                       $query->select('id', 'name as username');
                                   }))
                                   ->withCount('comments')
                                   ->withCount('likes')
                                   ->paginate($page_size);
                // return $this->sendResponse($articles->toArray(), 'Articles read succesfully');
                return $this->sendSuccess(ArticleListDto::build($articles, '/articles'));
                // return response()->json(ArticleListDto::build($articles, '/articles'), 200);
                //return $this->sendResponse($articles->toArray(), 'Books read succesfully');
        */
    }

    public function following(Request $request) {
        //return $this->sendSuccessResponse(auth()->user()->following);

        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);
        $user = auth()->user();
        //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        // $articles = Article::skip(($page - 1) * $page_size, $page_size)->take($page_size)->get()->toArray();

        // $perPage = null, $columns = ['*'], $pageName = 'page', $page = null
        // $articles = Article::paginate($page_size, ['*'], 'page', 5);
// By reading the source code I see the page is set to $page from input request, page_size is set to paginate(page_size)
        // that means we do not have to setCurrentPageResolver because it works if we sent ?page= in the url
        $following_relations = UserRelation::orderBy('created_at', 'desc')
                                           ->where('follower_id', '=', $user->id)
                                           ->paginate($page_size);

        return $this->sendSuccess(UserSubscriptionsList::build($following_relations, '/articles', true));
    }
}