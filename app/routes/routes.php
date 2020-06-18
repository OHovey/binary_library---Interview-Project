<?php

$book_routes = [
    'get' => '/books',
    'lent' => '/books/lent'
]; 

$member_routes = [
    'get' => '(^/members)',
    'getOne' => '(^/members/(\d))',
    'currently_lending' => '(^/members/(\d/)/currently_lending)'
];

function route_matcher($uris)
{
    foreach($uris as $uri_key => $uri_value)
    {
        if (preg_match(
            $uri_value, 
            $_SERVER['REQUEST_URI']
        ))
        {
            return $uri_key;
        }
    }
}