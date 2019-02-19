# Introduction
This is an incomplete Laravel Api Blog application

# Features implemented
- Seeding all models with faker
- Many models already written: Article, Comment, Like, UserSubscription(following/follower paradigm), Tag, Category, User and Role.
- Many associations are implemented.
- Controllers have already some code, they have to be heavily refactored though.

# Useful commands

`composer require fzaninotto/faker`
`composer install`
`php artisan make:migration create_articles_table --create=articles`
`php artisan migrate:rollback`
`php artisan migrate`
`php artisan make:migration SiteSubscription --migrate`
`php artisan make:policy ArticlePolicy --model=Article`
`php artisan make:policy CommentPolicy --model=Comment`
`composer dump-auto`
`php artisan db:seed`
# Play with the models
`php artisan tinker`
```php
use App\Models\Article;
Article::count()
$article = Article::inRandomOrder()->first();
$article->comments();
$article->likes();
$article->user
$article->user->articles->count()
$article->user->comments->count()
$article->user->likes->count()
$article->user->likes->first
$article->user->likes->first()->likeable_type
$article->user->likes->first()->likeable_id
use App\Models\User;
$user = User::first();
$user->followers->count()
$user->following->count()
$user->following->first()
$user->following->first()->id
$user->roles
$user->isAdmin()
$user->isAuthor()
use App\Models\Like;
// Find likes assigned to articles
Like::where('likeable_type', 'like', '%article%')->count()
// Comments related to articles
Comment::where('commentable_type', 'like', '%article%')->count()
// Comments related to comments
Comment::where('commentable_type', 'like', '%comment%')->count()
// Get a comment related to article, assert the type of comentable is Article
is_a(Comment::where('commentable_type', 'like', '%article%')->first()->commentable, Article::class)
=> true
// Get a comment related to article, assert the type of comentable is Comment
is_a(Comment::where('commentable_type', 'like', '%comment%')->first()->commentable, Comment::class)
=> true
use App\Models\Tag;
$tag = Tag::first();
$tag->articles->count();
$tag->articles->first()->tags->contains($tag)
$tag->name;
// How many articles are not associated with $tag
Article::whereHas('tags', function($query)use($tag){return $query->where('tags.name', '!=', $tag->name);})->count()
// TODO: Below is not working fine
Article::whereHas('tags', function($query)use($tag){return $query->where('tags.name', '!=', $tag->name);})->first()->tags->contains($tag)
```

# Todo
- Handle my own error tymon messages
- Improve response on Auth::login()
- Improve ArticleController:store, I am saving the article then updating it with tags and categories, do everything in one shot
- Fix taggables and categorizables tables, the created_at and updated_at
are not set.
- File upload
- Using Request OOP
- Implement the getRandomNotFollowedBy etc helper methods in userRelation Model
- Password confirmation
- Refactor user relations to sitesubcription and userSubscription
- Better exception handling, Custom 404
- getByAuthor

# Resources
todo ...
