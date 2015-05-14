<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;
use App\User;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('welcome');
	}

	public function login(){

		$slack_url = Request::input('slack_url');
		$phid = Request::input('phid');

		$user = User::where('phid',$phid)->first();

		$user->slack_url = $slack_url;

		$user->save();

		return redirect('/')->with('success', "You're all signed up. The Slack Hunts bot will now post when you upvote a product.");


	}

	public function callback(){

		$code = Request::input('code');
		$tokenURL = 'https://api.producthunt.com/v1/oauth/token';
 
		$ch = curl_init($tokenURL);
		$jsonData = array(
    		'client_id' => 'fb42db6b01792f3e48c39ca9c658db4f42d7cbbc72afc296cf60d5076ac0b8de',
    		'client_secret' => '8d8af802b3d498ffef2780aa2f2d10d2b2582fb333f675788717c739fb613bf2',
    		"redirect_uri" => "http://localhost:8000/callback",
    		"code" => $code,
    		"grant_type" => "authorization_code"

		);
 
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Host: api.producthunt.com','Origin: ','Accept: application/json'));


		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result, true);
		$access_token = $json["access_token"];

		$authHeader = "Authorization: Bearer ".$access_token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.producthunt.com/v1/me");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, 'Content-Type: application/json','Host: api.producthunt.com','Origin: ','Accept: application/json'));
		$output = curl_exec($ch);

		$output = json_decode($output, true);

 		$userID = $output['user']['id'];
 		$name = $output['user']['name'];

 		if (User::where('phid', $userID)->exists()){


 		}else{

 			$user = new User();
 			$user->phid = $userID;
 			$user->name = $name;
 			$user->slack_url = "";
 			$user->save();
 		}

		curl_close($ch);

		return redirect('/')->with('userID', $userID);



	}

	
	public function getAccessToken($code){



//The JSON data.
		$jsonData = array(
    		'client_id' => 'fb42db6b01792f3e48c39ca9c658db4f42d7cbbc72afc296cf60d5076ac0b8de',
    		'client_secret' => '8d8af802b3d498ffef2780aa2f2d10d2b2582fb333f675788717c739fb613bf2',
    		"redirect_uri" => "http://localhost:8000/callback",
    		"code" => $code,
    		"grant_type" => "authorization_code"

		);
 
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization' => 'Content-Type: application/json','Host: api.producthunt.com','Origin: ','Accept: application/json')); 
		$result = curl_exec($ch);

		var_dump(json_decode($result, true));
		var_dump($code);
		//return redirect('/');
		
	}

	public function getUserID(){
 
 		$ch = curl_init();
		$headers = array('HTTP_ACCEPT: Something', 'HTTP_ACCEPT_LANGUAGE: fr, en, da, nl', 'HTTP_CONNECTION: Something');

		curl_setopt($ch, CURLOPT_URL, "http://localhost"); # URL to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); # return into a variable
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header ); # custom headers, see above
		$result = curl_exec( $ch ); # run!

		curl_close($ch);

	}

}
