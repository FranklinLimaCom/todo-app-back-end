<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Database\Models\Todo;

$app->get('/todos/{id}', function (Request $request, Response $response, $args) {
    
    $id = $args["id"];
    $todo = Todo::find($id);

    $response->getBody()->write(json_encode($todo));

    return $response;
});

$app->get('/todos', function (Request $request, Response $response, $args) {
    $done = isset($_GET["done"]) ? is_true($_GET["done"]):false; 
    
    $todos = $done ? Todo::findDone():Todo::findUndone();

    $response->getBody()->write(json_encode($todos));

    return $response;
});

$app->post('/todos', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();

    $data["done"] = isset($data["done"]) ? $data["done"] : false;
    
    $todo = Todo::create($data);

    $response->getBody()->write(json_encode($todo));

    return $response;
});


$app->put('/todos/{id}', function (Request $request, Response $response, $args) {
    $id = $args["id"];

    $data = $request->getParsedBody();

    $data["id"] = $id;

    $todo = Todo::update($data);

    $response->getBody()->write(json_encode($todo));

    return $response;
});

$app->delete('/todos/{id}', function (Request $request, Response $response, $args) {
    $id = $args["id"];

    Todo::delete($id);

    $response->getBody()->write(json_encode([ "success" => true ]));

    return $response;
});

?>