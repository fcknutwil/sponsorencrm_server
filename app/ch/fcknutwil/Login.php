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
                $res = DB::instance()->fetchRow('SELECT count(id) AS correct FROM users WHERE name=:name AND password=SHA2(:password, 512)', $body);
                $response = $response->withHeader('Content-Type', 'application/json');
                if($res['correct'] == 1) {
                    return $response->write(json_encode((object) ['key' => JWT::getPrivateKey()], JSON_UNESCAPED_SLASHES));
                } else {
                    return $response->withStatus(401)->write(json_encode((object) ['message' => 'Login nicht erfolgreich'], JSON_UNESCAPED_SLASHES));
                }
            });
        });
    }
}
