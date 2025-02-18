<?php
namespace MaximeEtundi\OpenWireComment;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class OpenWireCommentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Charger les migrations du package
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Charger les vues du package
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'openwirecomment');

        // Publication des vues (pour personnalisation)
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/openwirecomment'),
        ], 'views');

        // Enregistrement du composant Livewire
        if (class_exists(Livewire::class)) {
            Livewire::component('open-wire-comment', Http\Livewire\OpenWireComment::class);
        }
    }

    public function register()
    {
        //
    }
}
