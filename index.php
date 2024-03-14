<?php

require 'vendor/autoload.php'; // Include Composer's autoloader

use GuzzleHttp\Client;

// OpenWeather API key
$apiKey = getenv('OPENWEATHER_API_KEY');

// Get weather data for Dhaka from OpenWeather API
function getWeatherData() {
    global $apiKey;
    
    $client = new Client();
    $response = $client->get('https://api.openweathermap.org/data/2.5/weather', [
        'query' => [
            'q' => 'Dhaka',
            'appid' => $apiKey,
            'units' => 'metric' // Get temperature in Celsius
        ]
    ]);
    
    return json_decode($response->getBody(), true);
}

// Get the current datetime
$datetime = date('Y-m-d H:i:s');

// Define the base path for the API
define('BASE_PATH', '/api');

// Define the routes
$routes = [
    // Route to get basic information and weather data
    '/hello' => function () use ($datetime) {
        return [
            'hostname' => gethostname(),
            'datetime' => $datetime,
            'version' => '1.0',
            'weather' => getWeatherData()
        ];
    }
];

// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];

// Remove the base path from the request URI
$path = str_replace(BASE_PATH, '', $requestUri);

// Trim any trailing slashes
$path = rtrim($path, '/');

// Check if the requested route exists
if (isset($routes[$path])) {
    // Call the handler function for the route and return the response
    $response = $routes[$path]();
    echo json_encode($response);
} else {
    // Return a 404 Not Found error if the route doesn't exist
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
?>
