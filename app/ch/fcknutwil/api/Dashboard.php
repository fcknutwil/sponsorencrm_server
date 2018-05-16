<?php

namespace ch\fcknutwil\api;

use ch\fcknutwil\DashboardBuilder;
use org\maesi\ErrorResponseCreator;
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
                try {
                    return $response->withJson(DashboardBuilder::builder()->build());
                } catch (\Exception $exception) {
                    return $response->withJson(ErrorResponseCreator::create($exception->getMessage()), 422);
                }
            });
            $this->get('/{year}', function ($request, $response, $param) {
                try {
                    return $response->withJson(DashboardBuilder::builder()->withType(DashboardBuilder::$DETAIL)->withYear($param['year'])->build());
                } catch (\Exception $exception) {
                    return $response->withJson(ErrorResponseCreator::create($exception->getMessage()), 422);
                }
            });
        });
    }

}
