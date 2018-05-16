<?php

namespace StudioKaa\Amoclient;
use Lcobucci\JWT\Parser;

class AmoAPI
{

	private $client;

	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client;
	}

	public function get($endpoint)
	{
		return $this->call($endpoint, 'GET');
	}

	private function call($endpoint = 'user', $method = 'GET')
	{
		$access_token = session('access_token');
		$endpoint = str_start($endpoint, '/');

		if($access_token->isExpired())
		{
			$access_token = $this->refresh(session('refresh_token'));
		}

	    $response = $this->client->request($method, 'https://login.amo.rocks/api' . $endpoint, [
		    'headers' => [
		        'Accept' => 'application/json',
		        'Authorization' => 'Bearer '. $access_token
		    ],
		]);

		return collect( json_decode( (string)$response->getBody(), true ) );
	}

	private function refresh($refresh_token)
	{
		try
		{
			$response = $this->client->post('https://login.amo.rocks/oauth/token', [
			    'form_params' => [
			        'grant_type' => 'refresh_token',
			        'refresh_token' => $refresh_token,
			        'client_id' => config('amoclient.client_id'),
			        'client_secret' => config('amoclient.client_secret')
			    ],
			]);

			$tokens = json_decode( (string) $response->getBody() );
			$access_token = (new Parser())->parse((string) $tokens->access_token);
			session('access_token', $access_token);
			return $access_token;
		}
		catch(\GuzzleHttp\Exception\ClientException $e)
		{
			$url = app('StudioKaa\Amoclient\AmoclientController')->redirect()->getTargetUrl();
			abort(302, '', ["Location" => $url]);
		}
	}
}
