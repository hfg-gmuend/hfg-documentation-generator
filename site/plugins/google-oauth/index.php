<?php

require_once 'vendor/autoload.php';

Kirby::plugin("k3/google-oauth", [

    "routes" => [
        [
            "pattern" => "oauth/panel/login",
            "method" => "GET",
            "auth" => false,
            "action" => function () {

                loginUser($_COOKIE['googleIdToken']);

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

                setcookie('instance', kirby()->site()->url(), time() + (60*10), "/");
               
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

function loginUser($googleIdToken)
{
    deleteCookie("googleAccessToken");
    deleteCookie("googleIdToken");
    
    //Verify and decode id token
    $CLIENT_ID = "914414992322-db2h9cuc69dhmfor6vrk6hblnrakucnn.apps.googleusercontent.com";

    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($googleIdToken);
    if ($payload && $payload['hd'] == "hfg.design" && $payload['aud'] == $CLIENT_ID) {
        $userid = $payload['sub'];
        $email = $payload['email'];
        $verifiedEmail = $payload['email_verified'];
    } else {
        die("Invalid token");
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
    print_r($kirbyUser);
}

function deleteCookie($cookieName) {
    if (isset($_COOKIE[$cookieName])) {
        unset($_COOKIE[$cookieName]);
        setcookie($cookieName, '', time() - 3600, '/'); // empty value and old timestamp
    }
}
