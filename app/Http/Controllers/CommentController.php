<?php

namespace App\Http\Controllers;

use App\Dtos\Response\CommentListDto;
use App\Models\Article;
use App\Models\Comment;
use CommentDetailsDto;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


class CommentController extends BaseController
{

    public function __construct()
    {
        $this->middleware('jwt.verify', ['only' => 'index, show']);
    }

    public function index(Request $request, $article_slug)
    {
        $page = (int)$request->get('page', 1);
        $page_size = (int)$request->get('page_size', 10);
        //Article::resolveConnection()->getPaginator()->setCurrentPage($page - 1);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        //Comment::ArticleId($article_id);
        $article = Article::where('slug', $article_slug)->first();
        $comments = Comment::where('commentable_id', $article->id)->paginate($page_size);
        // $comments = $article->comments()->get();
        return $this->sendSuccess(CommentListDto::build($comments, '/comments'));

        //return view('comments.index', ['comments' => Comment::where('article_id', $article_id)]);
    }

    public function store(// CreateCommentRequest $request,
        Request $request, Article $article)
    {
        // validation
        $this->validate($request, array(
            'content' => 'required|min:2|max:500'
        ));


        /**
         * Authorization without required Models
         */
        // If fails will throuw Illuminate\Auth\Access\AuthorizationException 403
        $this->authorize('create', Comment::class);


        // Approach 1, not working as expected for polymorphic relations
        /**
         * $comment = Auth::user()->comments()->create([
         * 'commentable_type' => Article::class,
         * 'commentable_id' => $article->id,
         * 'content' => $request->input('content')
         * ]);
         */


        // Approach 2
        // The same, but using article model
        /*
                $comment = $article->comments()->create([
                    'content' => $request->input('content'),
                    'user_id' => auth()->id(),
                ]);
            */

        // Approach 3
        $comment = new Comment;
        $comment->content = $request->content;
        // $comment->user_id = auth()->id();
        // or
        $comment->user()->associate(auth()->user());
        $comment->commentable()->associate($article);
        $comment->save();

        // Session::flash('success', 'Comment added succesfully');
        // redirect()->route('posts.show', [$article->id]);
        return $this->sendSuccessResponse(CommentDetailsDto::build($comment), 'Comment created successfully');
    }

    public function edit(Comment $comment)
    {
        return view('comments.edit')->withComment($comment);
    }

    public function update(// UpdateCommentRequest $request,
        Request $request, Comment $comment)
    {
        $this->validate($request, array(
            'content' => 'required|min:2|max:500'
        ));

        //$comment = Comment::find($id);
        $comment->content = $request->content;
        $comment->save();

        Session::flash('success', 'Comment updated succesfully');
        return redirect()->route('articles.show', $comment->article->id);
    }

    public function delete(Comment $comment)
    {
        return view('comments.delete')->withComment($comment);
    }

    public function destroy(Article $article, Comment $comment)
    {

        // Use Gate
        /*  if (!Gate::allows('comments.delete', $comment))
              return redirect()->route();

          // there is allows() instead of denies
          if (Gate::forUser(Auth::user())->denies('comments.delete', $comment))
              return redirect()->route(); // not allowed

  */
        // Using Policies, user->cant() and can(), if the given
        // operation is not implemented, laravel will try to execute
        // the equivalente Gate
        if (Auth::user()->cant('delete', $comment))
            return $this->sendError('Access denied');

        // Authorization through Controller Helpers
        $this->authorize('delete', $comment);

        $comment->delete();

        // Session::flash('success', 'Deleted Comment');
        // return redirect()->route('posts.show', $post_id);

        return $this->sendSuccessResponse('Article deleted');
    }


    public function show(Article $article)
    {

    }
    /*
        public function update(UpdateArticleRequest $request, Article $article) {
            if ($request->has('article')) {
                $article->update($request->get('article'));
            }
        }

        public function destroy(DeleteCommentRequest $request, Article $article, Comment $comment) {
            $comment->delete();
            return $this->respondSuccess();
        }*/
}


