<?php
namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Slim\App;

class Sponsor extends Base{

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group(self::getPath() . '/sponsor', function () {
            $this->get('', function ($request, $response) {
                $res = DB::instance()->fetchRowMany('SELECT s.*, CONCAT(o.plz, " ", o.ort) AS ortstring FROM sponsor AS s 
                  LEFT JOIN ort AS o ON s.fk_ort=o.id');
                return $response->withJson($res);
            });
            $this->get('/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow('SELECT s.*, CONCAT(o.plz, " ", o.ort) AS ortstring FROM sponsor AS s 
                  LEFT JOIN ort AS o ON s.fk_ort=o.id WHERE s.id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->put('/{id}', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $res = DB::instance()->update('sponsor', ["id" => $args["id"]],
                    [
                        "name" => $body['name'], "vorname" => $body['vorname'], "strasse" => $body['strasse'], "fk_ort" => $body['fk_ort'],
                        "telefon" => $body['telefon'], "email" => $body["email"], "homepage" => $body["homepage"], "notiz" => $body["notiz"],
                        "name_ansprechpartner" => $body['name_ansprechpartner'], "email_ansprechpartner" => $body['email_ansprechpartner'],
                        "telefon_ansprechpartner" => $body['telefon_ansprechpartner'], "typ" => $body["typ"]
                    ]
                    );
                $res = DB::instance()->fetchRow('SELECT s.*, CONCAT(o.plz, " ", o.ort) AS ortstring FROM sponsor AS s 
                  LEFT JOIN ort AS o ON s.fk_ort=o.id WHERE s.id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->delete('/{id}', function ($request, $response, $args) {
                DB::instance()->delete('sponsor_engagement', ["fk_sponsor" => $args["id"]]);
                DB::instance()->delete('sponsor', ["id" => $args["id"]]);
                return $response->withStatus(204);
            });
            $this->post('', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $id = DB::instance()->insert('sponsor',
                    [
                        "name" => $body['name'], "vorname" => $body['vorname'], "strasse" => $body['strasse'], "fk_ort" => $body['fk_ort'],
                        "telefon" => $body['telefon'], "email" => $body["email"], "homepage" => $body["homepage"], "notiz" => $body["notiz"],
                        "name_ansprechpartner" => $body['name_ansprechpartner'], "email_ansprechpartner" => $body['email_ansprechpartner'],
                        "telefon_ansprechpartner" => $body['telefon_ansprechpartner'], "typ" => $body["typ"]
                    ]
                    );
                $res = DB::instance()->fetchRow('SELECT s.*, CONCAT(o.plz, " ", o.ort) AS ortstring FROM sponsor AS s 
                  LEFT JOIN ort AS o ON s.fk_ort=o.id WHERE s.id=:id', ["id" => $id]);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
        });

        $this->app->group(self::getPath() . '/sponsor/{sponsorid}/engagement', function () {
            $this->get('', function ($request, $response, $args) {
                $res = DB::instance()->fetchRowMany('SELECT se.*, e.name FROM sponsor_engagement AS se
                      INNER JOIN engagement AS e ON se.fk_engagement=e.id
                      WHERE se.fk_sponsor=:id', ["id" => $args['sponsorid']]);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->get('/{id}', function ($request, $response, $args) {
                $res = DB::instance()->fetchRow('SELECT se.*, e.name FROM sponsor_engagement AS se
                      INNER JOIN engagement AS e ON se.fk_engagement=e.id
                      WHERE se.id=:id', ["id" => $args['id']]);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->put('/{id}', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                DB::instance()->update('sponsor_engagement', ["id" => $args["id"]],
                ['von' => substr($body['von'],0,10), 'bis' => substr($body['bis'],0,10), "fk_sponsor" => $body['fk_sponsor'], "fk_engagement" => $body['fk_engagement']]
                    );
                $res = DB::instance()->fetchRow('SELECT se.*, e.name FROM sponsor_engagement AS se
                      INNER JOIN engagement AS e ON se.fk_engagement=e.id
                      WHERE se.id=:id', ["id" => $args['id']]);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->delete('/{id}', function ($request, $response, $args) {
                DB::instance()->delete('sponsor_engagement', ["id" => $args["id"]]);
                return $response->withStatus(204);
            });
            $this->post('', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $id = DB::instance()->insert('sponsor_engagement',
                ['von' => substr($body['von'],0,10), 'bis' => substr($body['bis'],0,10), "fk_sponsor" => $body['fk_sponsor'], "fk_engagement" => $body['fk_engagement']]
                    );
                $res = DB::instance()->fetchRow('SELECT se.*, e.name FROM sponsor_engagement AS se
                      INNER JOIN engagement AS e ON se.fk_engagement=e.id
                      WHERE se.id=:id', ["id" => $id]);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
        });
    }
}
