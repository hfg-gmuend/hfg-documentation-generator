<?php

// tries to log in user at HfG mailserver to verify email and password. Returns true on successful login, otherwise false
function checkHfGMailbox($email, $password) {
    try {
        $server = "{mail.hfg-gmuend.de:993/service=imap/secure/ssl/novalidate-cert/readonly}INBOX";

        if(is_callable("imap_open")) $mbox = imap_open(imap_utf7_encode($server), imap_utf7_encode($email), imap_utf7_encode($password), OP_READONLY | OP_HALFOPEN);
        else throw new Exception("imap_open() is undefined. Is the IMAP extension activated properly?");

        // return true if login was successful
        if(isset($mbox)) {
            // close imap stream
            imap_close($mbox);

            return true;
        }
    } catch(Exception $e) {
        // silence errors and alerts
        imap_errors();
        imap_alerts();
    }

    return false;
}

Kirby::plugin("panel-extensions/customLogin", [
    "api" => [
        "routes" => [
            [
                "pattern" => "auth/customLogin",
                "method"  => "POST",
                "auth"    => false,
                "action"  => function () {
                    $auth = $this->kirby()->auth();

                    // csrf token check
                    if ($auth->type() === "session" && $auth->csrf() === false) {
                        throw new InvalidArgumentException("Invalid CSRF token");
                    }

                    $email    = $this->requestBody("email");
                    $long     = $this->requestBody("long");
                    $password = $this->requestBody("password");

                    // check user credentials with HfG mailserver workaround
                    if (checkHfGMailbox($email, $password)) {
                        // impersonate almighty kirby user to be able to create new user or change password
                        kirby()->impersonate("kirby");

                        // if user already exists update password to react to possible password changes of the HfG account
                        if (kirby()->user($email) !== null) {
                            kirby()->user($email)->changePassword($password);
                        } else {
                            // if user successfully logged in and doesn't already exist create new user
                            $username  = explode("@", $email)[0] ?: "";
                            $splitName = explode(".", $username);
                            $user = kirby()->users()->create([
                                "name"     => $username,
                                "email"    => $email,
                                "password" => $password,
                                "language" => "de",
                                "role"     => "editor",
                                "content"  => [
                                    "firstName" => ucFirst($splitName[0]) ?: "",
                                    "lastName"  => ucFirst($splitName[1]) ?: ""
                                ]
                            ]);
                        }
                    }

                    // log in user
                    $user = $this->kirby()->auth()->login($email, $password, $long);

                    return [
                        "code"   => 200,
                        "status" => "ok",
                        "user"   => $this->resolve($user)->view("auth")->toArray()
                    ];
                }
            ]
        ]
    ]
]);