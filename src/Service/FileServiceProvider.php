<?php

namespace Fifthgate\Objectivity\Files\Service;

use Illuminate\Support\ServiceProvider;
use Fifthgate\Objectivity\Files\Service\Interfaces\FileServiceInterface;
use Fifthgate\Objectivity\Files\Service\FileService;
use Fifthgate\Objectivity\Files\Infrastructure\Repository\Interfaces\FileRepositoryInterface;
use Fifthgate\Objectivity\Files\Infrastructure\Repository\FileRepository;
use Fifthgate\Objectivity\Files\Infrastructure\Mapper\Interfaces\FileMapperInterface;
use Fifthgate\Objectivity\Files\Infrastructure\Mapper\FileMapper;

class FileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        $migrationDir = __DIR__.'/../../database/migrations';
        $this->loadMigrationsFrom($migrationDir);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FileServiceInterface::class,
            FileService::class
        );
        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class
        );
        $this->app->bind(
            FileMapperInterface::class,
            FileMapper::class
        );
    }
}
