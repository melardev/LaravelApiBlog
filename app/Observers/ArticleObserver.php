<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{

    // php artisan make:observer ArticleObserver --model=Models/Article
    /**
     * Handle the article "created" event.
     *
     * @param  \App\Models\Article $article
     * @return void
     */
    public function created(Article $article) {
        //
    }

    /**
     * Handle the article "updated" event.
     *
     * @param  \App\Article $article
     * @return void
     */
    public function updated(Article $article) {
        //
    }

    public function creating(Article $article) {
        if ($article->slug == null)
            $article->slug = str_slug($article->title);
        if ($article->publish_on == null)
            $article->publish_on = now();
    }

    public function updating(Article $article) {
        if ($article->slug == null)
            $article->slug = str_slug($article->title);
    }

    /**
     * Handle the article "deleted" event.
     *
     * @param  \App\Article $article
     * @return void
     */
    public function deleted(Article $article) {
        //
    }

    /**
     * Handle the article "restored" event.
     *
     * @param  \App\Article $article
     * @return void
     */
    public function restored(Article $article) {
        //
    }

    /**
     * Handle the article "force deleted" event.
     *
     * @param  \App\Article $article
     * @return void
     */
    public function forceDeleted(Article $article) {
        //
    }
}
