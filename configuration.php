<?php

// DISABLE CACHING ON THE ENTITY IN PREPROCESS HOOKS
$variables['#cache']['max-age'] = 0;

// SET CACHE CONTEXTS
    $variables['#cache'] = [
      'contexts' => [
        'cookies:CookieWithPriority',
        'cookies:CookieWithoutPriority',
      ]
    ];
    
