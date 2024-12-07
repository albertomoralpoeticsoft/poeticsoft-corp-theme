<?php

// https://blog.randes.me/curl-requests-as-mobile-clients-android-phone-tablet-ipad-ios/

function blocktheme_curl_getpage( WP_REST_Request $req ) {
  
  $res = new WP_REST_Response();
  
  try {

    $ch = curl_init();

    // set URL and other appropriate options
    $ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
    // $ua = 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 15_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1';
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
    curl_setopt($ch, CURLOPT_URL, "https://wp.poeticsoft.xyz");    
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $out = curl_exec($ch);

    curl_close($ch);

    $file = __DIR__ . '/data.html';

    $iswritable = is_writable($file);

    $fp = file_put_contents(
      __DIR__ . '/data.html',
      $out
    );

    $res->set_data($fp);

  } catch (Exception $e) {

    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;  
}


add_action(
  'rest_api_init',
  function () {

    register_rest_route(
      'blocktheme/curl',
      'getpage',
      [
        'methods'  => 'GET',
        'callback' => 'blocktheme_curl_getpage',
        'permission_callback' => '__return_true'
      ]
    );
  }
);