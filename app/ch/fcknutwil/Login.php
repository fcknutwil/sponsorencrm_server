<?php
namespace ch\fcknutwil;

use org\maesi\DB;
use Slim\App;

class Login {

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group('/login', function () {
            $this->get('', function ($request, $response, $args) {
                $res = DB::instance()->fetchRowMany('SELECT name, password FROM users');
                $response = $response->withHeader('Content-Type', 'application/json');
                return $response->write(json_encode($res, JSON_UNESCAPED_SLASHES));
            });
        });
    }
}
