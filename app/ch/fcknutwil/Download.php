<?php
namespace ch\fcknutwil;

use org\maesi\DB;
use Slim\App;

class Download {

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group('/download', function () {
            $this->get('/dokument/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow(
                    'SELECT d.name, c.mimetype, c.content, c.size FROM dokument as d INNER JOIN content AS c ON d.fk_content = c.id WHERE d.id=:id',
                    ['id' => $args['id']]
                );
                if(!$res) {
                    return $response->withStatus(404);
                }
                $response = $response
                    ->withHeader('Content-type', $res['mimetype'])
                    ->withHeader('Content-Disposition', "attachment; filename=" . $res['name'])
                    ->withHeader('Content-Length', $res['size']);
                $body = $response->getBody();
                $body->write(base64_decode($res['content']));
                return $response;
            });
            $this->get('/logo/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow(
                    'SELECT l.name, c.mimetype, c.content, c.size FROM logo as l INNER JOIN content AS c ON l.fk_content = c.id WHERE l.id=:id',
                    ['id' => $args['id']]
                );
                if(!$res) {
                    return $response->withStatus(404);
                }
                $response = $response
                    ->withHeader('Content-type', $res['mimetype'])
                    ->withHeader('Content-Disposition', "attachment; filename=" . $res['name'])
                    ->withHeader('Content-Length', $res['size']);
                $body = $response->getBody();
                $body->write(base64_decode($res['content']));
                return $response;
            });
        });
    }
}
