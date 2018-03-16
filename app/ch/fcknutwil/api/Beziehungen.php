<?php
namespace ch\fcknutwil\api;

use org\maesi\DB;
use org\maesi\ErrorResponseCreator;
use Slim\App;

class Beziehungen extends Base{

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group(self::getPath() . '/beziehungen', function () {
            $this->get('', function ($request, $response) {
                $res_mitglieder = DB::instance(DB::$TYP_MITGLIEDER_CRM)->fetchRowMany('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name, \'crm\' AS typ FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id');
                $res_donatoren = DB::instance(DB::$TYP_DONATOREN_CRM)->fetchRowMany('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name, \'donator\' AS typ FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id');
                return $response->withJson(array_merge($res_mitglieder, $res_donatoren));
            });
            $this->get('/crm', function ($request, $response) {
                $res_mitglieder = DB::instance(DB::$TYP_MITGLIEDER_CRM)->fetchRowMany('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name, \'crm\' AS typ FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id');
                return $response->withJson($res_mitglieder);
            });
            $this->get('/crm/{id}', function ($request, $response, $args) {
                $res = DB::instance(DB::$TYP_MITGLIEDER_CRM)->fetchRow('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id WHERE m.id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
            $this->get('/donator', function ($request, $response) {
                $res_donatoren = DB::instance(DB::$TYP_DONATOREN_CRM)->fetchRowMany('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name, \'donator\' AS typ FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id');
                return $response->withJson($res_donatoren);
            });
            $this->get('/donator/{id}', function ($request, $response, $args) {
                $res = DB::instance(DB::$TYP_DONATOREN_CRM)->fetchRow('SELECT m.id, CONCAT(m.vorname, " ", m.nachname, ", ", o.ort) AS name FROM mitglied AS m LEFT JOIN ort AS o ON m.fk_ort=o.id WHERE m.id=:id', $args);
                if($res) {
                    return $response->withJson($res);
                }
                return $response->withJson(ErrorResponseCreator::createNotFound(), 404);
            });
        });
    }
}
