<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/26/18
 * Time: 2:11 AM
 */

namespace Core\AuditTrail;

use Illuminate\Support\ServiceProvider;

class AuditTrailServiceProvider extends ServiceProvider
{

    public function boot(){
        $this->set_up_configs();
        $this->set_up_facades();
        $this->set_up_migrations();
    }

    protected function set_up_migrations(){
        $this->publishes([realpath(__DIR__ . '/migrations') => $this->app->databasePath().'/migrations']);
    }

    protected  function set_up_configs(){
        $this->publishes([
            __DIR__ . '/config/audit.php' => config_path('audit.php','config')
        ],'audit_config');
    }

    protected function set_up_facades(){
        \App::bind('audit', function (){
            return new \Core\AuditTrail\Audit();
        });
    }

    public function register(){

    }

    public function provides()
    {
        return [];
    }

}