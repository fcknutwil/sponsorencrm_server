<?php

namespace ch\fcknutwil\api;

use ch\fcknutwil\DashboardBuilder;
use Slim\App;

class Dashboard extends Base
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute()
    {
        $this->app->group(self::getPath() . '/dashboard', function () {
            $this->get('', function ($request, $response) {
                return $response->withJson(DashboardBuilder::build());
            });
        });
    }

}
