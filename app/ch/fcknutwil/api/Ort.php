<?php
namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Simplon\Mysql\MysqlException;
use Slim\App;

class Ort extends Base{

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group(self::getPath() . '/ort', function () {
            $this->get('', function ($request, $response) {
                $res = DB::instance()->fetchRowMany('SELECT *, concat(plz, " ", ort) AS fullname FROM ort');
                return $response->withJson($res);
            });
        });
    }
}
