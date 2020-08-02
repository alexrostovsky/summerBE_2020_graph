<?php
// Test this using following command
// php -S localhost:8080 ./graphql.php &
// curl http://localhost:8080 -d '{"query": "query { echo(message: \"Hello World\") }" }'
// curl http://localhost:8080 -d '{"query": "mutation { sum(x: 2, y: 2) }" }'
require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use Slim\App;

//require_once __DIR__ . '/schema.php';

require __DIR__ . '/src/Dependencies/dependencies.php';

$app = new App($container);
$container = $app->getContainer();


//$app->add(new middlewares\CORS());

$app->get('/', function(Request $request, Response $response) {
    return $response->write(file_get_contents(__DIR__ . '/src/graphiql/index.html'));
});

$app->post('/graph', 'GraphQLController:process');
//$app->post('/rest', 'GraphQLController:process');


$app->group('/rest', function () {
    $this->group('/user', function() {
        $this->get('', 'UserController:getUserInfo');
        $this->get('/owned', 'UserController:getOwnedGames');
        $this->get('/wishlist', 'UserController:getWishlist');
    });
    $this->group('/game', function () {
        $this->get('', 'GameController:getGameCatalog');
        $this->get('/{game_id:[0-9]+}', 'GameController:getGame');
        $this->post('/{game_id:[0-9]+}', 'GameController:buyGame');
        $this->get('/sale', 'GameController:getOnSaleGames');
    });
});

$app->run();