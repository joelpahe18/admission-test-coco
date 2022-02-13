<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\PokemonFormRequest;

class PokemonController extends Controller
{
	/**
	 * Display a listing of the pokemon.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( PokemonFormRequest $request ) {
		$base_url = config("constants.BASE_URL");

		$data = $request->all();

		$limit = ( isset( $data['limit'] ) ) ? $data['limit'] : 100;

		// set URL and other appropriate options
		$options = array(
			CURLOPT_URL 			=> $base_url.'pokemon?limit='.$limit,
			CURLOPT_HEADER 			=> false,
			CURLOPT_CUSTOMREQUEST 	=> 'GET',
		);

		return $this->curl_structure($options);
	}

	/**
	 * Display a dataset of the pokemon.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Request $request ) {
		$response = array();
		
		$base_url = config("constants.BASE_URL");

		$data = $request->all();

		$endpoints = explode( ",", $data['endpoint'] );

		$parameters = isset($data['parameter']) && !empty($data['parameter']) ? explode( ",", $data['parameter'] ) : null;

		foreach( $endpoints as $key => $endpoint ) {
			// set URL and other appropriate options
			$options = array(
				CURLOPT_URL 			=> ($base_url.''.$endpoint).(isset($parameters) ? ('/'.$parameters[$key]) : '' ),
				CURLOPT_HEADER 			=> false,
				CURLOPT_CUSTOMREQUEST 	=> 'GET',
			);

			$response[$endpoint] = $this->curl_structure($options);
		}

		return $response;
	}
	
	public function send_data(Request $request) {
		$base_url = config("constants.BASE_URL_SEND");
		
		$data = $request->all();

		// set URL and other appropriate options
		$options = array(
			CURLOPT_URL 			=> $base_url,
			CURLOPT_HEADER 			=> false,
			CURLOPT_CUSTOMREQUEST 	=> 'POST',
			CURLOPT_POSTFIELDS 		=> $data
		);

		return $this->curl_structure($options);
	}

	function curl_structure($options) {
		$ch = curl_init();

		$opts = curl_setopt_array($ch, $options);
		
		if( $opts === false )
			echo "Opciones mal configuradas...";

		// grab URL and pass it to the browser
		$exec = curl_exec($ch);

		if( $exec === false ){
			echo response()->json([
				'success'   =>  false,
				'message'   =>  'Failed request.',
				'data'      =>  null
			], 500);
		}

		// close cURL resource, and free up system resources
		curl_close($ch);

		echo response()->json([
			'success'   =>  true,
			'message'   =>  'Successful request.',
			'data'      =>  $exec,
			'codigo'    =>  200
		], 200);
	}
}