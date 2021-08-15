<?php

namespace App\Providers;

// use App\Container\SettingLaunch;
use App\Models\ResultTest;
use App\Repositories\QuestionCopy\QuestionCopyRepository;
use App\Repositories\Quiz\IQuizRepositoryInterface;
use App\Repositories\Quiz\QuizRepository;
use App\Repositories\QuizCopy\IQuizCopyRepositoryInterface;
use App\Repositories\QuizCopy\QuizCopyRepository;
use App\Repositories\ResultDetail\IResultDetailRepositoryInterface;
use App\Repositories\ResultDetail\ResultDetailRepository;
use App\Repositories\ResultTest\IResultTestRepositoryInterface;
use App\Repositories\ResultTest\ResultTestRepository;
use App\Repositories\Room\IRoomRepositoryInterface;
use App\Repositories\Room\RoomRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // $this->app->bind(SettingLaunch::class, function ($app){
        //     return new SettingLaunch("longle");
        // });

        $this->app->singleton(
            IRoomRepositoryInterface::class,
            RoomRepository::class
        );
        $this->app->singleton(
            IResultTestRepositoryInterface::class,
            ResultTestRepository::class
        );
        $this->app->singleton(
            IResultDetailRepositoryInterface::class,
            ResultDetailRepository::class
        );
        $this->app->singleton(
            IQuizRepositoryInterface::class,
            QuizRepository::class
        );
        $this->app->singleton(
            IQuizCopyRepositoryInterface::class,
            QuizCopyRepository::class
        );
        $this->app->bind("resultDetailRepo", function () {
            $question_copy_repo = new QuestionCopyRepository();
            return new ResultDetailRepository($question_copy_repo);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
