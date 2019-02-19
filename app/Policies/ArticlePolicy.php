<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\Models\User $user
     * @param  App\Models\Article $article
     * @return mixed
     */
    public function view(User $user, Article $article) {
        if ($user->isAdmin())
            return true;
        else if ($user->id === $article->user->id)
            true;
        else {
            return $article->publish_on < now() && $article->visibility !== 1;
        }
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user) {
        return $user !== null && $user->isAuthor();
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\Models\User $user
     * @param  App\Models\Article $article
     * @return mixed
     */
    public function update(User $user, Article $article) {
        return $user->isAdmin() || ($user->id === $article->user->id);
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\Models\User $user
     * @param  App\Models\Article $article
     * @return mixed
     */
    public function delete(User $user, Article $article) {
        return $user->isAdmin() || ($user->id === $article->user->id);
    }

    /**
     * Determine whether the user can restore the article.
     *
     * @param  \App\Models\User $user
     * @param  App\Models\Article $article
     * @return mixed
     */
    public function restore(User $user, Article $article) {
        //
    }

    /**
     * Determine whether the user can permanently delete the article.
     *
     * @param  \App\Models\User $user
     * @param  App\Models\Article $article
     * @return mixed
     */
    public function forceDelete(User $user, Article $article) {
        //
    }
}
