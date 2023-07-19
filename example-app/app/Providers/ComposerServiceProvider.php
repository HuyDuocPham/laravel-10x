<?php

namespace App\Providers;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // View::composer('*', function ($view) {
        //     $view->with('nguyenvanb', 'Nguyen Van C');
        // });
        $arrayViewProductCategory = [
            'client.pages.home',
            'client.pages.product_detail'
        ];

        View::composer($arrayViewProductCategory, function ($view) {
            $productCategories = ProductCategory::latest()->get()->filter(function($productCategory){
                return $productCategory->products->count() > 0;
            })->take(10);

            $view->with('productCategories',  $productCategories);
        });
    }
}
