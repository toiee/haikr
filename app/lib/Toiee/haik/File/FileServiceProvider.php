<?php
namespace Toiee\haik\File;

use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->registerLocalStorage();
        $this->registerFileRepositoryInterface();
        $this->registerFileManager();
        $this->listenFileSave();
    }

    public function register()
    {
        $this->app['filr'] = $this->app->share(function(){
            return new FileManager(
                $this->app->make('FileRepositoryInterface')
            );
        });
    }

    public function registerLocalStorage()
    {
        $this->app->bind('LocalStorage', function()
        {
            return new LocalStorage;
        });
    }

    public function registerFileRepositoryInterface()
    {
        $this->app->bind('FileRepositoryInterface', function()
        {
            return new FileRepository;
        });
    }

    public function registerFileManager()
    {
        $this->app->singleton('FileManager', function($app)
        {
            return new FileManager(
                $app->make('FileRepositoryInterface')
            );
        });
    }

    public function registerTypeResolvers()
    {
        $this->app->singleton('MimeTypeResolver', function($app)
        {
            return new MimeTypeResolver;
        });
        $this->app->singleton('FileTypeResolver', function($app)
        {
            return new FileTypeResolver;
        });
    }

    public function listenFileSave()
    {
        \Event::listen('file.save', function($file)
        {
            $this->app->make('FileManager')->setLastSaved($file);
        });
    }
}