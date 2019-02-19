<?php


namespace App\Http\Controllers;


use App\Dtos\Response\Article\ArticleListDto;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use ArticleDetailsDto;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class ArticleController extends BaseController
{
    public function __construct()
    {
        // authenticate except for read based actions: index and show
        $this->middleware('jwt.verify')->only(['store', 'update', 'destroy']);

    }

    public function index(Request $request)
    {
        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);
        DB::getQueryLog();
        //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        // $articles = Article::skip(($page - 1) * $page_size, $page_size)->take($page_size)->get()->toArray();

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
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->first();
        if (is_null($article))
            return $this->sendError('Article was not found');

        $article->comments_count = $article->comments()->count();
        // return $this->sendSuccessResponse($article->toArray(), 'Article read succesfully');

        return $this->sendSuccessResponse(ArticleDetailsDto::build($article));
    }

    // TODO: Not working
    public function getById($id)
    {
        $article = Article::find($id);
        if ($article != null)
            return $this->sendSuccessResponse(ArticleDetailsDto::build($article));
        else
            return $this->sendError('Article not found');
    }


    public function getByCategory(Request $request, $category_name)
    {
        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);
        DB::getQueryLog();
        //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $articles = Article
            ::whereHas('categories', function ($query) use ($category_name) {
                $query->where('slug', '=', $category_name);
            })
            ->orderBy('created_at', 'desc')
            ->with(array('user' => function ($query) {
                $query->select('id', 'name as username');
            }))
            ->withCount('comments')
            ->withCount('likes')
            ->paginate($page_size);
        return $this->sendSuccess(ArticleListDto::build($articles, $request->getRequestUri()));
    }

    public function getByTag(Request $request, $tag_name)
    {
        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);
        DB::getQueryLog();
        //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $articles = Article
            ::whereHas('tags', function ($query) use ($tag_name) {
                $query->where('slug', '=', $tag_name);
            })
            ->orderBy('created_at', 'desc')
            ->with(array('user' => function ($query) {
                $query->select('id', 'name as username');
            }))
            ->withCount('comments')
            ->withCount('likes')
            ->paginate($page_size);
        return $this->sendSuccess(ArticleListDto::build($articles, $request->getRequestUri()));
    }

    // TODO: Not working
    public function getByAuthor($username)
    {
        $articles = Article::with('user')->where('users.username', '=', $username);
        return Response::json(['articles' => $articles]);
        return $this->sendSuccessResponse(ArticleListDto::build($articles));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            // 'slug' => 'required|alpha_dash|min:5|max:255|unique:articles,slug',
            'body' => 'required',
            // 'category_id' => 'sometimes|integer',
            'description' => 'required'
        ], [
            'title' => 'You must provide a title',
            'description' => 'You must provide a content for your article'
        ]);
        if ($validator->fails())
            return $this->sendError('errors', $validator->errors());

        $user = JWTAuth::parseToken()->authenticate();
        // or, the below works because we stated in __construct() we should run the middleware jwt verify if we trigger this method
        $user2 = auth()->user();

        // $article = Article::create($request->all());
        // $article = $user->articles()->create(['title' => $request->input('article.title'), 'body' => $request->input('article.body')]);
        // $article = Article::create($request->only('title', 'description', 'body'));
        $article = new  Article($request->only('title', 'description', 'body'));
        $user = auth()->user();

        $article->user_id = $user->id;
        $article->save();
        // $article = new Article;
        // $article->title = $request->title;
        // $article->content = Purifier::clean($request->body);

        $inputTags = $request->input('tags');
        $inputCategories = $request->input('categories');

        if ($inputTags && !empty($inputTags)) {

            $tags = array_map(function ($tagJsonObj) {
                $description = null;
                if (in_array('description', $tagJsonObj))
                    $description = $tagJsonObj['description'];
                return Tag::firstOrCreate(['name' => $tagJsonObj['name']], ['description' => $description])->id;
            }, $inputTags);

            $article->tags()->sync($tags);
            // $article->tags()->attach($tags);
        }

        if ($inputCategories && !empty($inputCategories)) {

            $categories = array_map(function ($tagJsonObj) {
                $description = null;
                if (in_array('description', $tagJsonObj))
                    $description = $tagJsonObj['description'];
                return Category::firstOrCreate(['name' => $tagJsonObj['name']], ['description' => $description]);
            }, $inputCategories);

            $article->categories()->saveMany($categories);
            // $article->categories()->attach($categories);
        }

        // This is from another project, remove if gives errors
        // We already attached the tags
        // $article->tags()->sync($request->tags, false);


        $article->save();

        return $this->sendSuccessResponse(ArticleDetailsDto::build($article), 'Created successfully');
    }

    public function update(Request $request, Article $article)
    {
        /*
         * if ($request->has('article')) {
            $article->update($request->get('article'));
        }
         */
        $input = $request->all();
        $validator = Validator::make($input, ['title' => 'required',
            'body' => 'required',
            'description' => 'required']);

        /*
        $this->validate($request, array(
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'content' => 'required'
        ));
        */
        if ($validator->fails())
            return $this->sendError('error validation', $validator->errors());

        if ($request->has('article')) {
            $article->update($request->get('article'));
        }

        // $article->title = $request->get('title');
        $article->title = $request->input('title');
        $article->description = $input['description'];
        $article->slug = $request->input('slug');
        // $article->body = Purifier::clean($request->input('body'));
        $article->body = $input['body'];
        $article->save();

        $inputTags = $request->input('tags');
        $inputCategories = $request->input('categories');

        if ($inputTags && !empty($inputTags)) {

            $tags = array_map(function ($tagJsonObj) {
                return Tag::firstOrCreate(['name' => $tagJsonObj['name']], ['description' => $tagJsonObj['description']])->id;
            }, $inputTags);

            $article->tags()->sync($tags);
            // $article->tags()->attach($tags);
        }

        if ($inputCategories && !empty($inputCategories)) {

            $categories = array_map(function ($tagJsonObj) {
                return Category::firstOrCreate(['name' => $tagJsonObj['name']], ['description' => $tagJsonObj['description']]);
            }, $inputCategories);

            $article->categories()->saveMany($categories);
            // $article->categories()->attach($categories);
        }
        return $this->sendSuccessResponse($article->toArray(), 'Article updated');
    }

    public function destroy(Article $article)
    {

        // Gates are registered in AuthServiceProvider.php
        // Use Gate for authorization
        // if (!Gate::allows('articles.delete', $article)) // if not allowed
        if (Gate::denies('articles.delete', $article)) // if denied
            return $this->sendError('Access denied');

        // there is allows() instead of denies
        if (Gate::forUser(Auth::user())->denies('articles.delete', $article))
            return $this->sendError('Access denied'); // not allowed

        // Using Policies, user->cant() and can(), if the given
        // operation is not implemented, laravel will try to execute
        // the equivalente Gate
        if (Auth::user()->cant('delete', $article))
            return $this->sendError('Access denied');

        // Authorization through Controller Helps
        $this->authorize('delete', $article);

        $article->categories()->detach(); // Don't delete associated tags
        $article->tags()->detach(); // Don't delete associated tags
        $article->delete();

        return $this->sendSuccessResponse($article->toArray(), 'Article deleted');
    }

}