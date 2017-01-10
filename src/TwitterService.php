<?php
/**
 * @file
 * Contains Drupal\swarad\TwitterService
 */

namespace Drupal\swarad;

class TwitterService {
  public function getData($username) {
    $api_key = urlencode('dNNfgUyheKwKr4ARa7oiopub0');
    $api_secret = urlencode('3OMPGqJnZ9xxvMz596p6FFwZ2HnN5yJbplnP3zhRKC1RyALhWv');
    $auth_url = 'https://api.twitter.com/oauth2/token';

    $data_username = $username;
    $data_count = 10;
    $data_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $api_credentials = base64_encode($api_key.':'.$api_secret);

    $auth_headers = 'Authorization: Basic '.$api_credentials."\r\n".
      'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'."\r\n";

    $auth_context = stream_context_create(
      array(
        'http' => array(
          'header' => $auth_headers,
          'method' => 'POST',
          'content'=> http_build_query(array('grant_type' => 'client_credentials', )),
        )
      )
    );

    $auth_response = json_decode(file_get_contents($auth_url, 0, $auth_context), true);
    $auth_token = $auth_response['access_token'];

    $data_context = stream_context_create( array( 'http' => array( 'header' => 'Authorization: Bearer '.$auth_token."\r\n", ) ) );

    $data = json_decode(file_get_contents($data_url.'?count='.$data_count.'&screen_name='.urlencode($data_username), 0, $data_context), true);

    return $data;
  }

  public function renderData($username) {
    $data = $this->getData($username);

    $tweets = [];
    foreach ($data as $value) {
      $tweets[] = $value['text'];
    }

    return '' . implode('', $tweets) . '';
  }	
}