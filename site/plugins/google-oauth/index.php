<?php

session_start();

Kirby::plugin("k3/google-oauth", [

    "routes" => [
        [
            "pattern" => "oauth/panel/login",
            "method" => "GET",
            "auth" => false,
            "action" => function () {

                loginUser($_SESSION['googleAccountInfo']);

                return [
                    "code" => 200,
                    "status" => "ok",
                ];
            },
        ],
        [
            "pattern" => "oauth/panel/redirect",
            "method" => "GET",
            "auth" => false,
            "action" => function () {

                $_SESSION['instance'] = kirby()->site()->url();
               
                go('https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id='.kirby()->option('client_id').'&redirect_uri='.kirby()->option('redirect_uri'));
                
                return [
                    "code" => 200,
                    "status" => "ok",
                ];
            },
        ],
        [
            "pattern" => "oauth/url",
            "method" => "GET",
            "auth" => false,
            "action" => function () {
                return [
                    "redirect" => kirby()->site()->url()."/oauth/panel/redirect",
                ];
            },
        ],
    ],
]);

function loginUser($oauthUser)
{
    $vars = ['email', 'verifiedEmail', 'hd'];

    $oauthUserData = (array) $oauthUser;

    foreach ($vars as $var) {
        $$var = isset($oauthUserData[$var]) ? $oauthUserData[$var] : null;
    }

    if (!$email) {
        $this->error("E-mail address missing missing!");
    }

    if ($verifiedEmail === false) {
        $this->error("E-mail address not verified!");
    }

    if (!$kirbyUser = kirby()->user($email)) {
        kirby()->impersonate('kirby');
        $kirbyUser = kirby()->users()->create([
            'email' => $email,
            'role' => kirby()->option('google-oauth.defaultRole', 'editor'),
        ]);
    }

    $kirbyUser->loginPasswordless();
    go('panel');
}
