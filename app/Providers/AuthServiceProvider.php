<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Policies\ArticlePolicy;
use App\Policies\CommentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Article::class => ArticlePolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        // Register Policies
        $this->registerPolicies();

        Gate::define('articles.create', 'App\Policies\ArticlePolicy@create');
        Gate::define('articles.delete', function ($user, $article) {
            return $user->id == $article->user->id;
        });
        // resource gate, same as // comments.view -> view() comments.create -> create() comments.update -> update() comments.delete -> delete()
        Gate::resource('comments', 'App\Policies\CommentsPolicy');
    }
}
