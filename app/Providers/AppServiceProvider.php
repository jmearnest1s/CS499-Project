<?php

namespace App\Providers;

use App\General;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		\URL::forceScheme('https');
        Schema::defaultStringLength(191);
        $gnl = General::first();

        $latest = Post::orderBy('id', 'DESC')->take(20)->get();
        $p = Post::selectRaw('user_id,count(user_id) as cnt')->groupBy('user_id')->orderBy('cnt', 'DESC')->pluck('user_id');
       // $topAuthors = User::where('status',1)->inRandomOrder()->take(2)->get();
		$topAuthors = User::where('status',1)->where('vercode','' )->inRandomOrder()->take(2)->get();


        view()->share('gnl', $gnl);
        view()->share('p', $p);
        view()->share('latest', $latest);
        view()->share('topAuthors', $topAuthors);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
