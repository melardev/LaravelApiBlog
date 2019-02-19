<?php


namespace App\Http\Controllers;


use App\Dtos\Response\Article\ArticleListDto;
use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class LikeController extends BaseController
{

    public function __construct()
    {
        // authenticate except for read based actions: index and show
        $this->middleware('jwt.verify');
    }

    public function index(Request $request)
    {
        $id = auth()->id();
        $articleIds = Like::where([
            'user_id' => $id,
            'likeable_type' => Article::class
        ])->pluck('likeable_id');

        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $articles = Article
            ::whereIn('id', $articleIds)
            ->orderBy('created_at', 'desc')
            ->with(array('user' => function ($query) {
                $query->select('id', 'name as username');
            }))
            ->withCount('comments')
            ->withCount('likes')
            ->paginate($page_size);
        return $this->sendSuccess(ArticleListDto::build($articles, $request->getRequestUri()));
    }

    public function like(Article $article)
    {
        $user = auth()->user();

        foreach ($user->likes as $like) {
            if ($like->likeable_id == $article->id)
                return $this->sendError('You already liked this article', null, 400);
        }
        $like = new  Like(['user_id' => $user->id,
            'likeable_id' => $article->id,
            'likeable_type' => Article::class]);
        $like->save();

        return $this->sendSuccessResponse(null, 'Liked successfully');
    }

    public function dislike(Article $article)
    {
        $user = auth()->user();

        $like = Like::where(['user_id' => $user->id, 'likeable_id' => $article->id])->first();
        if ($like == null)
            return $this->sendError('You are not liking this article, fail');
        if ($like->delete())
            return $this->sendSuccessResponse(null, 'Like removed successfully');
        else
            return $this->sendError('Something went wrong');
    }
}