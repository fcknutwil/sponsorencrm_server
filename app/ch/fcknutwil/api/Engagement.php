<?php

namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Slim\App;

class Engagement extends Base
{

    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute()
    {
        $this->app->group(self::getPath() . '/engagement', function () {
            $this->get('', function ($request, $response) {
                $res = DB::instance()->fetchRowMany('SELECT * FROM engagement');
                return $response->withJson($res);
            });
            $this->get('/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow('SELECT * FROM engagement WHERE id=:id', $args);
                if ($res) {
                    $res['types'] = DB::instance()->fetchColumnMany('SELECT fk_typ FROM engagement_typ WHERE fk_engagement=:id', ['id' => $res['id']]);
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->put('/{id}', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                DB::instance()->delete('engagement_typ', [fk_engagement=> $args['id']]);
                if(is_array($body['types'])) {
                    foreach($body['types'] as $type) {
                        DB::instance()->insert('engagement_typ', [fk_engagement=> $args['id'], fk_typ => $type]);
                    }
                }
                DB::instance()->update('engagement', ['id' => $args['id']], ['name' => $body['name'], 'betrag' => $body['betrag'], 'zahlung' => $body['zahlung']]);


                $res = DB::instance()->fetchRow('SELECT * FROM engagement WHERE id=:id', $args);
                return $response->withJson($res);
            });
            $this->post('', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $id = DB::instance()->insert('engagement', ['name' => $body['name'], 'betrag' => $body['betrag'], 'zahlung' => $body['zahlung']]);
                if(is_array($body['types'])) {
                    foreach($body['types'] as $type) {
                        DB::instance()->insert('engagement_typ', [fk_engagement=> $id, fk_typ => $type]);
                    }
                }
                $res = DB::instance()->fetchRow('SELECT * FROM engagement WHERE id=:id', ['id' => $id]);
                return $response->withJson($res);
            });
            $this->delete('/{id}', function ($request, $response, $args) {
                DB::instance()->delete('engagement_typ', ['fk_engagement' => $args['id']]);
                DB::instance()->delete('engagement', ['id' => $args['id']]);
                return $response->withStatus(204);
            });
        });
    }
}
