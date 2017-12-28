<?php
namespace ch\fcknutwil;

use org\maesi\DB;
use org\maesi\JWT;
use Slim\App;

class Login {

    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->initRoute();
    }

    private function initRoute() {
        $this->app->group('/login', function () {
            $this->put('', function ($request, $response) {
                $body = $request->getParsedBody();
                $res = DB::instance()->fetchRow('SELECT id AS sub FROM users WHERE name=:name AND password=SHA2(:password, 512)', $body);
                if($res) {
                    $token = JWT::create($res);
                    return $response->withJson(['token' => $token, 'expire' => JWT::getClaim($token, 'exp')]);
                } else {
                    return $response->withJson(['message' => 'Login nicht erfolgreich'], 401);
                }
            });
        });
    }
}
