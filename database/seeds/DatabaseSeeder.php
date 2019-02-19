<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function __construct() {
        $this->faker = Faker\Factory::create();
    }

    public function seed_admin_feature() {
        $role_admin = Role::firstOrCreate(
            ['name' => Role::ROLE_ADMIN],
            ['description' => 'For Admin Users']
        );
        // Users
        $melardev = \App\Models\User::firstOrCreate(
            ['email' => 'admin@laravel_api_blog.com'],
            [
                'username' => 'admin',
                'first_name' => 'adminFn',
                'last_name' => 'adminLn',
                'password' => bcrypt('password')
            ]
        );
        $melardev->roles()->sync($role_admin);
    }

    /**/
    public function seed_author_feature() {
        $role_author = Role::firstOrCreate(
            ['name' => Role::ROLE_AUTHOR],
            ['description' => 'For authors']
        );
        $authors_to_seed = 5;

        $authors_count = User::whereHas('roles', function ($query) use ($role_author, $authors_to_seed) {
            return $query->where('roles.name', Role::ROLE_AUTHOR);
        })->count();

        factory(\App\Models\User::class, ($authors_to_seed - $authors_count))
            ->create()
            ->each(function ($author) use ($role_author) {
                $author->roles()->sync($role_author);
            });
    }

    public function seed_users_feature() {
        // $role_authenticated = Role::firstOrCreate(['name' => Role::ROLE_USER]);
        // User::with('roles')->where('roles.name', Role::ROLE_USER);

        $role_authenticated_user = Role::firstOrCreate(['name' => Role::ROLE_USER],
                                                       ['description' => 'For Authenticated users']
        );
        $normal_users_to_seed = 43;

        $users_count = User::whereHas('roles', function ($query) {
            return $query->where('roles.name', Role::ROLE_USER);
        })->count();

        $normal_users_to_seed_still = $normal_users_to_seed - $users_count;

        echo "[+] Seeding ${normal_users_to_seed_still} users\n";

        factory(\App\Models\User::class, $normal_users_to_seed_still)
            ->create();
    }

    public function seed_categories() {
        //\App\Models\Category::firstOrCreate(['name' => 'C++', 'description' => 'c++ tutorials'])
        $categories_to_seed = 8;
        $categories_count = \App\Models\Category::count();
        $categories_to_seed_still = $categories_to_seed - $categories_count;
        echo "[+] Seeding categories ${categories_to_seed_still}\n";
        if ($categories_to_seed_still > 0)
            factory(Category::class, $categories_to_seed_still)->create();
    }

    public function seed_tags() {
        //\App\Models\Category::firstOrCreate(['name' => 'C++', 'description' => 'c++ tutorials'])

        $tags_to_seed = 8;
        $tags_count = Tag::count();
        $tags_to_seed_still = $tags_to_seed - $tags_count;
        echo "[+] Seeding tags ${tags_to_seed_still}\n";
        if ($tags_to_seed_still > 0)
            factory(Tag::class, $tags_to_seed_still)->create();
    }

    public function seed_likes() {
        $likes_to_seed = 49;
        $likes_count = Like::count();
        $likes_to_seed -= $likes_count;
        factory(Like::class, $likes_to_seed)
            ->create();

    }

    public function seed_relations() {
        $relation_to_seed = 50;
        $relations_count = DB::table('user_relations')->count();
        $relation_to_seed -= $relations_count;

        echo "Seeding ${relation_to_seed} relations\n";
        if ($relation_to_seed > 0) {
            for ($i = 0; $i < $relation_to_seed; $i++) {
                $follower = User::inRandomOrder()->first();
                $following = User::getRandomUserNotFollowedBy($follower);

                if ($following != null && !is_null($following->id))
                    $follower->follow(intval($following->id));
            }
        }
    }

    function seed_articles() {
        $articles_to_seed = 52;
        $articles_count = Article::count();
        $articles_to_seed -= $articles_count;
        echo "[+] Seeding Articles ${articles_to_seed}\n";

        if ($articles_to_seed > 0) {

            factory(Article::class, $articles_to_seed)
                ->create()
                ->each(function ($article) {
                    $tags = Tag::inRandomOrder()->get()->take(rand(0, Tag::count()));
                    $categories = Category::inRandomOrder()->get()->take(rand(0, Category::count() - 2));
                    // https://stackoverflow.com/questions/23896031/how-to-save-entries-in-many-to-many-polymorphic-relationship-in-laravel
                    $article->tags()->sync($tags);
                    $article->categories()->sync($categories);
                });
        }
    }

    public function seed_comments() {
        $comments_to_seed = 23;
        $comments_count = Comment::where('commentable_type', 'like', "%article%")->count();
        $comments_to_seed -= $comments_count;

        echo "[+] Seeding ${comments_to_seed} comments\n";
        if ($comments_to_seed > 0) {
            factory(Comment::class, $comments_to_seed)
                ->create()
                ->each(function ($comment) {
                    // https://laravel.com/docs/5.4/eloquent-relationships#inserting-and-updating-related-models
                    Article::inRandomOrder()->first()->comments()->save($comment);
                    // return factory(Article::class)->create()->id;

                });
        }

        $comment_replies_to_seed = 11;
        $recomment_replies_count = Comment::where('commentable_type', 'like', '%Comment%')->count();
        $comment_replies_to_seed -= $recomment_replies_count;

        echo "[+] Seeding ${comment_replies_to_seed} comment replies\n";
        if ($comment_replies_to_seed > 0)
            factory(Comment::class, $comment_replies_to_seed)
                ->create()
                ->each(function ($comment) {
                    $parent = Comment::where('commentable_type', 'not like', '%Comment%')->inRandomOrder()->first();
                    $parent->comments()->save($comment);
                });
    }

    public function run(): void {
        /*
                $this->call(ArticleSeeder::class);
                $this->call(UserSeeder::class);
        */
        $this->seed_admin_feature();
        $this->seed_author_feature();
        $this->seed_users_feature();

        $this->seed_categories();
        $this->seed_tags();
        $this->seed_articles();
        $this->seed_comments();
        $this->seed_likes();
        $this->seed_relations();
//                $user()->roles()->associate($role_author);

    }
}
