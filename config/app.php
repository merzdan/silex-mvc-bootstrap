<?php


/**
 * Application and Providers Configuration
 *
 *
 */

use Silex\Application;
use MJanssen\Provider\RoutingServiceProvider;

$app->register(new Silex\Provider\HttpCacheServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Template Engine Definition
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.options' => array(
        'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.path' => array(PATH_VIEWS),
    'twig.options' => array('debug' => $app['debug'])
));

// Database Definition
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname'    => '',   // <<настройте БД
        'user'      => '',
        'password'  => '',
        'charset'   => 'utf8',
    ),
));


//Routes
$routes = array(
    'home' => array(
        //'name' =>  --> you can omit the route name if a key is set
        'pattern' => '/',
        'controller' => 'App\Controller\IndexController::indexAction',
        'method' => array('get', 'post', 'put', 'delete', 'options', 'head')
    ),
	'homeReview' => array(
        //'name' =>  --> you can omit the route name if a key is set
        'pattern' => '/reviewID{id}',
        'controller' => 'App\Controller\IndexController::ReviewAction',
        'method' => array('get', 'post', 'put', 'delete', 'options', 'head')
    ),
    'indexReview' => array(
        //'name' => --> you can omit the route name if a key is set
        'pattern' => '/addreview',
        'controller' => 'App\Controller\ReviewController::IndexAction',
        'method' => array('get', 'put', 'delete', 'options', 'head')
    ),
	 'addReview' => array(
        //'name' =>  --> you can omit the route name if a key is set
        'pattern' => '/addreview',
        'controller' => 'App\Controller\ReviewController::AddReviewAction',
        'method' => array('post')
    )
	
);

$routingServiceProvider = new RoutingServiceProvider();
$routingServiceProvider->addRoutes($app, $routes);

// Security Definition
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'index' => array(
            'pattern' => '^/$',
            'anonymous' => true
        ),
		'addreview' => array(
            'pattern' => '^/addreview$',
            'anonymous' => true
        ),
		'review' => array(
            'pattern' => '^/reviewID{id}$',
            'anonymous' => true
        ),
    ),
));

$app['security.authentication.success_handler.admin'] = $app->share(function() use ($app) {
    // Success Authentication
    return new App\Service\Authentication\CustomAuthenticationSuccessHandler($app['security.http_utils'], array(), $app);
});

// Log Definition
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => PATH_LOG . '/app.log',
    'monolog.name'  => 'app',
    'monolog.level' => 300 // = Logger::WARNING
));

// Errors Exception
$app->error(function(\Exception $e, $code) use($app) {

    $file = pathinfo($e->getFile());

    return $app->json([
        'success' => false,
        'message' => 'Error',
        'error' => $e->getMessage(),
        'serverror' => $code,
        'source' => $file['filename'],
        'line' => $e->getLine()
    ], $code);
});