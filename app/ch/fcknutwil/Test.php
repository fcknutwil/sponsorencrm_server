<?php
namespace ch\fcknutwil;

use Slim\App;

class Test {

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group('/test', function () {
            $this->get('', function ($request, $response, $args) {
                return $response->write(json_encode((object) ['message' => 'Hello World'], JSON_UNESCAPED_SLASHES));
            });
        });
    }
}
