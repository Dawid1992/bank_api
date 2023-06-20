<?php
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

    $routes = [
        '/' => 'src/controllers/main.php',
        '/api' => 'src/controllers/api.php',
        '/calculator' => 'src/controllers/calculator.php',
        '/convert' => 'src/controllers/convert.php',
        '/history' => 'src/controllers/history.php',
    ];

    if(array_key_exists($uri, $routes)) {
        include $routes[$uri];
    } else {
        http_response_code(404);
    }

?>
