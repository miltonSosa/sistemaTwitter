<?php
if(function_exists('curl_init')){
    include('./TwitterAPIExchange.php');

    $settings = array(
        'oauth_access_token' => "",
        'oauth_access_token_secret' => "",
        'consumer_key' => "",
        'consumer_secret' => ""
    );

    //Como lo que quiero es obtener datos, el método que voy a usar es GET
    $requestMethod = 'GET';

    //Instancio la librería pasandole al constructor los datos de acceso
    $twitter = new TwitterAPIExchange($settings);

    //hashtag
    $search = 'hashtagABuscar';
    $hashtag = urlencode($search);
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    $getfield = '?q='.$hashtag;
    $tweets = json_decode($twitter->setGetfield($getfield)
               ->buildOauth($url, $requestMethod)
               ->performRequest());

    $datos = array();

    foreach ($tweets->statuses as $dato) {
        $hora = new DateTime($dato->created_at);
        $datos[$dato->id]=array(
            'id' => $dato->id,
            'hora' =>$hora->format('H:i'),
            'text' => $dato->text,
            'user' => array(
                'name' => $dato->user->name,
                'name_user'=>$dato->user->screen_name,
                'descripcion' => $dato->user->description,
                'img' => $dato->user->profile_image_url_https,
            ),
            'cant_rtw' => $dato->retweet_count,
            'cant_fav' => $dato->favorite_count,
        );
    }
    echo json_encode(array_reverse($datos));
}else{
    echo 'La librería curl no se encuentra instalada.';
    die;
}
