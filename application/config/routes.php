<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'performer/login';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'performer/login';
/********** Rest API Routes  *************/
$route['auth/login']['post']='webservices/auth/login';
$route['auth/logout']['post']='webservices/auth/logout';
$route['performers']['get']='webservices/performers';
$route['performers/songslist']['get'] ='webservices/songs/performerSongsList';

$route['get_tiny_url/(:any)']['get'] ='webservices/performers/check_shorten_url/$1';

$route['performers/favoritesongslist']['get']='webservices/songs/performerFavoriteSongsList';

$route['performers/detail/(:any)']['get']    = 'webservices/performers/detail/$1';
$route['performers/create']['post']   	   = 'webservices/performers/create';
$route['performers/update/(:num)']['put']    = 'webservices/performers/update/$1';
$route['performers/delete/(:num)']['delete'] = 'webservices/performers/delete/$1';

$route['create-request-song']['post']   	   = 'webservices/request/createRequest';
$route['create-request-song']['post']   	   = 'webservices/request/getAllCurrentShowRequests';

/********** Finish *************/


/********** Web Application Routes *************/

$route['login'] = "performer/login";
$route['forgotpassword'] = "performer/home/forgot_password_page";
$route['home.html'] = "performer/home";
$route['home/(:any)']="performer/home/request_songs/$1";
$route['requestsongs']="performer/home/songs_list";
$route['tiny_url.html']="performer/home/check_tiny_url";
$route['requestdetails']="performer/home/request_details";
$route['paymentsuccess']="performer/home/payment_success_page";
$route['createprofile']="performer/home/create_profile";
$route['myshow']="performer/home/performer_shows";
$route['showdetails']="performer/home/performer_show_detail";

$route['mysonglist']="performer/home/mySongList";

$route['songList']="performer/home/AllSongList";

/********** Finish *************/



$route['user'] = '/user';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
