<?php declare(strict_types=1);

require_once "../vendor/autoload.php";

use Bramus\Router\Router;
use Core\DTO\ErrorResponse;
use Core\Exceptions\CoreExceptions;
use Core\Exceptions\ParametersException;
use Core\Exceptions\RouterException;
use Core\Tools\Other;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$router = new Router();
$router->setNamespace("\Api\Controllers");

try {

    $router->get("/", function () {
        echo "API for ddSchedule";
    });

    $router->mount("/methods", function () use ($router) {

        $router->get("/", function () {
            throw new ParametersException("method not passed");
        });

        $router->mount("/groups", function () use ($router) {

            $router->get("/", function () {
                throw new ParametersException("function not passed");
            });

            $router->get("/getAll", "GroupController@getAll");

            $router->post("/add", "GroupController@add");

        });

        $router->mount("/schedule", function () use ($router) {

            $router->get("/", function () {
                throw new ParametersException("function not passed");
            });

            $router->get("/get", "ScheduleController@get");

            $router->post("/add", "ScheduleController@add");

            $router->post("/set", "ScheduleController@set");

        });

    });

    $router->set404(function() {
        throw new RouterException("current route not found for this request method");
    });

    $router->run();

} catch (CoreExceptions $coreExceptions) {

    (new ErrorResponse())->setCode($coreExceptions->getCode())->setErrorMessage($coreExceptions->getMessage())->send();

} catch (Throwable $exceptions) {

    Other::log(
        "Error: " . $exceptions->getMessage() .
        " on line: " . $exceptions->getLine() .
        " in: " . $exceptions->getFile(),
        "schedule"
    );
    (new ErrorResponse())->setErrorMessage("internal error, try later")->send();

}