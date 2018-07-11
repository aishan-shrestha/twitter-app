<?php

// require_once 'vendor/autoload.php';
require_once('vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

// require_once('twitteroauth/autoload.php');
// use Abraham\TwitterOAuth\TwitterOAuth;

session_start();

if( isset($_GET["pid"]) ) {
    $_SESSION["pic_id"] = $_GET["pid"];
}

switch( $_SESSION["pic_id"] ) {
    case 1:
        $PicLocation = 'cover_pics/cover1.jpg';
        break;
    case 2:
        $PicLocation = 'cover_pics/cover2.png';
        break;
    case 3:
        $PicLocation = 'cover_pics/cover3.jpg';
        break;
    case 4:
        $PicLocation = 'cover_pics/cover4.jpg';
        break;
    case 5:
        $PicLocation = 'cover_pics/cover5.jpg';
        break;
    case 6:
        $PicLocation = 'cover_pics/cover6.jpg';
        break;
    case 7:
        $PicLocation = 'cover_pics/cover7.jpg';
        break;
    case 8:
        $PicLocation = 'cover_pics/cover8.jpg';
        break;
    case 9:
        $PicLocation = 'cover_pics/cover9.jpg';
        break;
    case 10:
        $PicLocation = 'cover_pics/cover10.jpg';
        break;
    case 11:
        $PicLocation = 'cover_pics/cover11.jpg';
        break;
    case 12:
        $PicLocation = 'cover_pics/cover14.jpg';
        break;
    case 13:
        $PicLocation = 'cover_pics/cover13.png';
        break;
     case 14:
        $PicLocation = 'cover_pics/cover14.png';
        break;

    default:
       // header('Location: ' . $homeurl);
        break;
}


$config = require_once 'config.php';

// get and filter oauth verifier
$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');

// check tokens
if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}

// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
$connection->setTimeouts(10, 15);

// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);

// connect with user token
$twitter = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $token['oauth_token'],
    $token['oauth_token_secret']
);

$user = $twitter->get('account/verify_credentials');


// if something's wrong, go and log in again
if(isset($user->error)) {
    header('Location: ' . $config['url_login']);
}




/*
 * Update cover
*/
$media = $twitter->upload('media/upload', ['media' => $PicLocation]);
echo $media->image->image_type.'<br/>';
echo "w: ".$media->image->w.'<br/>';
echo "h: ". $media->image->h.'<br/>';
echo "size: ". $media->size.'<br/>';
$parameters = [
    "media_id"=> $media->media_id_string,
    "media_id_string"=> $media->media_id_string,
    "size"=> $media->size,
    "expires_after_secs"=> 864000,
    "image"=> [
        "image_type"=> $media->image->image_type,
        "w"=> 1500,
        "h"=> 500,
    ]
];
$result = $twitter->post("account/update_profile_banner", $parameters );
print_r($result);

/*
 * Update profile
*/
/*$media = $twitter->upload('media/upload', ['media' => $PicLocation]);
echo $media->image->image_type.'<br/>';
echo "w: ".$media->image->w.'<br/>';
echo "h: ". $media->image->h.'<br/>';
echo "size: ". $media->size.'<br/>';
$parameters = [
    "media_id"=> $media->media_id_string,
    "media_id_string"=> $media->media_id_string,
    "size"=> $media->size,
    "expires_after_secs"=> 86400,
    "image"=> [
        // "image_type"=> "image/jpg",
        "image_type"=> $media->image->image_type,
        "w"=> 400,
        "h"=> 400,
    ]
];
$result = $twitter->post("account/update_profile_image", $parameters );
print_r($result);*/


/*
 * Post a picture on the twet
*/
// $media = $twitter->upload('media/upload', ['media' => 'cover_pics/cover6.jpg']);

/*$media = $twitter->upload('media/upload', ['media' =>  $PicLocation]);
echo $media->image->image_type.'<br/>';
echo "w: ".$media->image->w.'<br/>';
echo "h: ". $media->image->h.'<br/>';
echo "size: ". $media->size.'<br/>';
$parameters = [
    // 'status' => 'TESTing Tweets #testiii',
    // 'media_ids' => implode(',', [$media->media_id_string]),

    "media_ids"=> $media->media_id_string,
    "media_id_string"=> $media->media_id_string,
    "size"=> $media->size,
    "expires_after_secs"=> 86400,
    "image"=> [
        "image_type"=> $media->image->image_type,
        "w"=> 440,
        "h"=> 220,
    ]
];

$result = $twitter->post('statuses/update', $parameters);

print_r($result);*/

