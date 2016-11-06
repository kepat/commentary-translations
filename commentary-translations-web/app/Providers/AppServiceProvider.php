<?php
namespace App\Providers;

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
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require app_path('Support/helpers.php');

        $this->app->bind('Knp\Snappy\Pdf', function () {
            return new \Knp\Snappy\Pdf('/usr/local/bin/wkhtmltopdf.sh');
//            return new \Knp\Snappy\Pdf('/usr/local/bin/wkhtmltopdf');
        });

        $this->app->bind('App\Contracts\Repositories\CustomerRepositoryInterface', 'App\Repositories\EloquentCustomerRepository');
        $this->app->bind('App\Contracts\Repositories\WorkScheduleRepositoryInterface', 'App\Repositories\EloquentWorkScheduleRepository');

        $this->app->bind('App\Contracts\Repositories\DeliveryRepositoryInterface', 'App\Repositories\EloquentDeliveryRepository');
        $this->app->bind('App\Contracts\Repositories\JobDetailRepositoryInterface', 'App\Repositories\EloquentJobDetailRepository');
        $this->app->bind('App\Contracts\Repositories\DriverRepositoryInterface', 'App\Repositories\EloquentDriverRepository');
        $this->app->bind('App\Contracts\Repositories\JobRepositoryInterface', 'App\Repositories\EloquentJobRepository');
        $this->app->bind('App\Contracts\Repositories\RouteRepositoryInterface', 'App\Repositories\EloquentRouteRepository');
        $this->app->bind('App\Contracts\Repositories\UserRepositoryInterface', 'App\Repositories\EloquentUserRepository');
        $this->app->bind('App\Contracts\Repositories\RoleRepositoryInterface', 'App\Repositories\EloquentRoleRepository');
        $this->app->bind('App\Contracts\Repositories\SessionRepositoryInterface', 'App\Repositories\EloquentSessionRepository');
        $this->app->bind('App\Contracts\Repositories\DisputeRepositoryInterface', 'App\Repositories\EloquentDisputeRepository');
        $this->app->bind('App\Contracts\Repositories\UOMRepositoryInterface', 'App\Repositories\EloquentUOMRepository');
        $this->app->bind('App\Contracts\Repositories\PriceRepositoryInterface', 'App\Repositories\EloquentPriceRepository');
        $this->app->bind('App\Contracts\Repositories\PostalRepositoryInterface', 'App\Repositories\EloquentPostalRepository');
        $this->app->bind('App\Contracts\Repositories\InvoiceRepositoryInterface', 'App\Repositories\EloquentInvoiceRepository');
        $this->app->bind('App\Contracts\Repositories\CommissionRepositoryInterface', 'App\Repositories\EloquentCommissionRepository');
        $this->app->bind('App\Contracts\Repositories\DistanceRepositoryInterface', 'App\Repositories\EloquentDistanceRepository');
        $this->app->bind('App\Contracts\Repositories\InvoiceLineRepositoryInterface', 'App\Repositories\EloquentInvoiceLineRepository');
        $this->app->bind('App\Contracts\Repositories\ReasonRepositoryInterface', 'App\Repositories\EloquentReasonRepository');
        $this->app->bind('App\Contracts\Repositories\ConfirmationRepositoryInterface', 'App\Repositories\EloquentConfirmationRepository');
        $this->app->bind('App\Contracts\Repositories\RecipientRepositoryInterface', 'App\Repositories\EloquentRecipientRepository');
    }
}
