<?php
namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Simplon\Mysql\MysqlException;
use Slim\App;

class Typ extends Base{

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group(self::getPath() . '/typ', function () {
            $this->get('', function ($request, $response) {
                $res = DB::instance()->fetchRowMany('SELECT * FROM typ');
                return $response->withJson($res);
            });
            $this->get('/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow('SELECT * FROM typ WHERE id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->put('/{id}', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $name = trim($body['name']);
                if(strlen($name) == 0) {
                    return $response->withJson(ErrorResponseCreator::createRequiredIsMissing("name"), 422);
                }
                // TODO: Prüfen, dass readonly Felder nicht geändert werden können
                if(DB::instance()->fetchRow('SELECT * FROM typ WHERE id=:id', $args)) {
                    DB::instance()->update('typ', ['id' => $args['id']], ['name' => $name]);
                    $res = DB::instance()->fetchRow('SELECT * FROM typ WHERE id=:id', $args);
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->post('', function ($request, $response) {
                $body = $request->getParsedBody();
                $name = trim($body['name']);
                if(strlen($name) == 0) {
                    return $response->withJson(ErrorResponseCreator::createRequiredIsMissing("name"), 422);
                }
                try {
                    $id = DB::instance()->insert('typ', ['name' => $name]);
                    $res = DB::instance()->fetchRow('SELECT * FROM typ WHERE id=:id', ['id' => $id]);
                    return $response->withJson($res);
                } catch (MysqlException $exception) {
                    // TODO: Duplicate entry schöner prüfen
                    return $response->withJson(ErrorResponseCreator::createDuplicate('name'), 422);
                }
            });
        });
    }
}
