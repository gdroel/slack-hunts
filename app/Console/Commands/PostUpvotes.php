<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\User;
use App\Time;

class PostUpvotes extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	
	protected $name = 'postupvotes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){

		// $tokenURL = 'https://api.producthunt.com/v1/oauth/token';
		// $ch = curl_init($tokenURL);
		// $jsonData = array(
  //   		'client_id' => 'fb42db6b01792f3e48c39ca9c658db4f42d7cbbc72afc296cf60d5076ac0b8de',
  //   		'client_secret' => '8d8af802b3d498ffef2780aa2f2d10d2b2582fb333f675788717c739fb613bf2',
  //   		"grant_type" => "client_credentials"

		// );
 
		// $jsonDataEncoded = json_encode($jsonData);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Host: api.producthunt.com','Origin: ','Accept: application/json'));
		// $result = curl_exec($ch);
		// curl_close($ch);
		// $json = json_decode($result, true);
		// //$access_token = $json["access_token"];
		// $this->info($result);

		$users = User::all();
		foreach ($users as $user){

			$id = $user->phid;
			$url = "https://api.producthunt.com/v1/users/".$id."/votes";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer 000d58bc10f579445899f1d41bf406f375ce58f34a14b842bba6a0f630a30d59', 'Content-Type: application/json','Host: api.producthunt.com','Origin: ','Accept: application/json'));
			$output = curl_exec($ch);
			$json = json_decode($output, true);
			curl_close($ch);

			for ($i=0; $i<10; $i++){
				$voteTime = strtotime($json["votes"][$i]["created_at"]);
				$productName = $json["votes"][$i]["post"]["name"];
				$productURL = $json["votes"][$i]["post"]["discussion_url"];
				$estimateTime = time() - $voteTime;

				if ($estimateTime < 600){

					$this->info("less than 10 minutes ago");
					$url = $user->slack_url;
					$ch = curl_init($url);
					$jsonData = array(
    					'text' => $user->name.' upvoted '.'<'.$productURL.'|'.$productName.'>'.' on Product Hunt ',
    					'icon_url' => 'http://codecondo.com/wp-content/uploads/2014/08/Product-Hunt-Logo.png',
    					'username' => 'slack-hunts'
					);
 
					$jsonDataEncoded = json_encode($jsonData);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
					$result = curl_exec($ch);
				}

				else{

					$this->info("more than 10 minutes ago");
					break;

				}

			}
		

		}
		
	}


}
