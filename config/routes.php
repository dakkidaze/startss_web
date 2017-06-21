<?php

use Slim\App;
use Slim\Container;
use App\Controllers;
use App\Middleware\Auth;
use App\Middleware\Guest;
use App\Middleware\Admin;
use App\Middleware\Api;
use App\Middleware\Mu;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

/***
 * The slim documents: http://www.slimframework.com/docs/objects/router.html
 */

// config
$debug = false;
if (defined("DEBUG")) {
    $debug = true;
}
/***
 * $configuration = [
 * 'settings' => [
 * 'displayErrorDetails' => $debug,
 * ]
 * ];
 * $c = new Container($configuration);
 ***/

// Make a Slim App
// $app = new App($c);
$app = new App([
    'settings' => [
        'debug' => $debug,
        'whoops.editor' => 'sublime'
    ]
]);
$app->add(new WhoopsMiddleware);


// Home
$app->get('/', 'App\Controllers\HomeController:index');
$app->get('/s', 'App\Controllers\HomeController:baidu');
$app->get('/code', 'App\Controllers\HomeController:code');
$app->get('/tos', 'App\Controllers\HomeController:tos');

// User Center
$app->group('/user', function () {
    $this->get('', 'App\Controllers\UserController:index');
    $this->get('/', 'App\Controllers\UserController:index');
    $this->post('/checkin', 'App\Controllers\UserController:doCheckin');
    $this->post('/draw', 'App\Controllers\UserController:doDraw');
    $this->get('/node', 'App\Controllers\UserController:node');
    $this->get('/node/{id}', 'App\Controllers\UserController:nodeInfo');
    $this->get('/profile', 'App\Controllers\UserController:profile');
    $this->get('/invite', 'App\Controllers\UserController:invite');
    $this->post('/invite', 'App\Controllers\UserController:doInvite');
    $this->get('/edit', 'App\Controllers\UserController:edit');
    $this->post('/password', 'App\Controllers\UserController:updatePassword');
    $this->post('/sspwd', 'App\Controllers\UserController:updateSsPwd');
	$this->post('/buy/buypackage', 'App\Controllers\UserController:updateBuypackage');
	$this->get('/addcredit', 'App\Controllers\UserController:addCredit');
	$this->get('/resetflow', 'App\Controllers\UserController:resetFlow');
	$this->post('/resetflow/{code}', 'App\Controllers\UserController:doResetFlow');
    $this->post('/method', 'App\Controllers\UserController:updateMethod');
    $this->get('/sys', 'App\Controllers\UserController:sys');
    $this->get('/trafficlog', 'App\Controllers\UserController:trafficLog');
	$this->get('/buytraffic', 'App\Controllers\UserController:buyTraffic');
	$this->get('/buy/{id}', 'App\Controllers\UserController:buyTraffic');
    $this->get('/kill', 'App\Controllers\UserController:kill');
    $this->post('/kill', 'App\Controllers\UserController:handleKill');
    $this->get('/logout', 'App\Controllers\UserController:logout');
})->add(new Auth());

// Auth
$app->group('/auth', function () {
    $this->get('/login', 'App\Controllers\AuthController:login');
    $this->post('/login', 'App\Controllers\AuthController:loginHandle');
	$this->get('/login/{message}', 'App\Controllers\AuthController:loginMsg');
    $this->get('/register', 'App\Controllers\AuthController:register');
    $this->post('/register', 'App\Controllers\AuthController:registerHandle');
    $this->get('/logout', 'App\Controllers\AuthController:logout');
})->add(new Guest());

// Password
$app->group('/password', function () {
    $this->get('/reset', 'App\Controllers\PasswordController:reset');
    $this->post('/reset', 'App\Controllers\PasswordController:handleReset');
    $this->get('/token/{token}', 'App\Controllers\PasswordController:token');
    $this->post('/token/{token}', 'App\Controllers\PasswordController:handleToken');
	$this->get('/active/{id}/{token}', 'App\Controllers\PasswordController:active');
})->add(new Guest());

// Admin
$app->group('/admin', function () {
    $this->get('', 'App\Controllers\AdminController:index');
    $this->get('/', 'App\Controllers\AdminController:index');
	$this->get('/trafficlog', 'App\Controllers\AdminController:trafficLog');
	
    // Node Mange
    $this->get('/node', 'App\Controllers\Admin\NodeController:index');
    $this->get('/node/create', 'App\Controllers\Admin\NodeController:create');
    $this->post('/node', 'App\Controllers\Admin\NodeController:add');
    $this->get('/node/{id}/edit', 'App\Controllers\Admin\NodeController:edit');
    $this->put('/node/{id}', 'App\Controllers\Admin\NodeController:update');
    $this->delete('/node/{id}', 'App\Controllers\Admin\NodeController:delete');
    $this->get('/node/{id}/delete', 'App\Controllers\Admin\NodeController:deleteGet');
    
    //Document
    $this->get('/doc', 'App\Controllers\Admin\DocController:index');
    $this->get('/doc/create', 'App\Controllers\Admin\DocController:create');
    $this->post('/doc', 'App\Controllers\Admin\DocController:add');
    $this->get('/doc/{id}/delete', 'App\Controllers\Admin\DocController:deleteGet');
    
    // User Mange
    $this->get('/user', 'App\Controllers\Admin\UserController:index');
	$this->get('/user/tools/{action}', 'App\Controllers\Admin\UserController:tools');
	$this->get('/user/search/{kw}', 'App\Controllers\Admin\UserController:search');
	$this->get('/user/searchRatio/{kw}', 'App\Controllers\Admin\UserController:searchRatio');
    $this->get('/user/{id}/edit', 'App\Controllers\Admin\UserController:edit');
    $this->put('/user/{id}', 'App\Controllers\Admin\UserController:update');
    $this->delete('/user/{id}', 'App\Controllers\Admin\UserController:delete');
    $this->get('/user/{id}/delete', 'App\Controllers\Admin\UserController:deleteGet');
	
	
    $this->post('/sendwarn', 'App\Controllers\AdminController:sendWarn');
//     $this->get('/sendwarn', 'App\Controllers\AdminController:sendWarn');
    

    $this->get('/profile', 'App\Controllers\AdminController:profile');
    $this->get('/invite', 'App\Controllers\AdminController:invite');
    $this->post('/invite', 'App\Controllers\AdminController:addInvite');
	$this->get('/couponcode', 'App\Controllers\AdminController:couponcode');
	$this->post('/couponcode', 'App\Controllers\AdminController:addCouponCode');
    $this->get('/sys', 'App\Controllers\AdminController:sys');
    $this->get('/logout', 'App\Controllers\AdminController:logout');
})->add(new Admin());

// API
$app->group('/api', function () {
    $this->get('/token/{token}', 'App\Controllers\ApiController:token');
    $this->post('/token', 'App\Controllers\ApiController:newToken');
    $this->get('/node', 'App\Controllers\ApiController:node')->add(new Api());
	$this->get('/nodedraft', 'App\Controllers\ApiController:nodeDraft');
    $this->get('/user/{id}', 'App\Controllers\ApiController:userInfo')->add(new Api());
	$this->post('/payment/{invoice}', 'App\Controllers\PaymentController:payCallback');
});

// mu
$app->group('/mu', function () {
    $this->get('/users', 'App\Controllers\Mu\UserController:index');
    $this->post('/users/{id}/traffic', 'App\Controllers\Mu\UserController:addTraffic');
})->add(new Mu());

// res
$app->group('/res', function () {
    $this->get('/captcha/{id}', 'App\Controllers\ResController:captcha');
});

// Run Slim Routes for App
$app->run();
