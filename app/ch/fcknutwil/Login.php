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
            $this->put('', function ($request, $response, $args) {
                $body = $request->getParsedBody();
                $res = DB::instance()->fetchRow('SELECT id AS userid FROM users WHERE name=:name AND password=SHA2(:password, 512)', $body);
                $response = $response->withHeader('Content-Type', 'application/json');
                if($res) {
                    $token = JWT::create($res);
                    return $response->write(json_encode((object) ['token' => $token, 'expire' => JWT::getClaim($token, 'exp')], JSON_UNESCAPED_SLASHES));
                } else {
                    return $response->withStatus(401)->write(json_encode((object) ['message' => 'Login nicht erfolgreich'], JSON_UNESCAPED_SLASHES));
                }
            });
        });
    }
}
