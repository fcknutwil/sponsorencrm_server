<?php
namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Slim\App;

class Engagement extends Base{

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group(self::getPath() . '/engagement', function () {
            $this->get('', function ($request, $response) {
                $res = DB::instance()->fetchRowMany('SELECT * FROM engagement');
                return $response->withJson($res);
            });
            $this->get('/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow('SELECT * FROM engagement WHERE id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
        });
    }
}
