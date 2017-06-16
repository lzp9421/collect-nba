<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
//        if (!isset($_SERVER['PHP_AUTH_USER'])) {
//            header("WWW-Authenticate: Basic realm=\"My Private Area\"");
//            header("HTTP/1.0 401 Unauthorized");
//            print "You need valid credentials to get access!\n";
//            exit;
//        } else {
//            if ($_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'admin') {
//                header("WWW-Authenticate: Basic realm=\"My Private Area\"");
//                header("HTTP/1.0 401 Unauthorized");
//                print "You need valid credentials to get access!\n";
//                exit;
//            }
//        }
    }
}
