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
	public function index( PokemonFormRequest $request )
	{
		$data = $request->all();

		$limit = ( isset( $data['limit'] ) ) ? $data['limit'] : 100;

		$ch = curl_init();

		// set URL and other appropriate options
		$options = array(
			CURLOPT_URL 			=> 'https://pokeapi.co/api/v2/pokemon?limit='.$limit,
			CURLOPT_HEADER 			=> false,
			CURLOPT_CUSTOMREQUEST 	=> 'GET',
		);

		$opts = curl_setopt_array($ch, $options);
		
		if( $opts === false )
			echo "Opciones mal configuradas...";

		// grab URL and pass it to the browser
		$exec = curl_exec($ch);

		if( $exec === false ){
			return response()->json([
				'success'   =>  false,
				'message'   =>  'Failed request.',
				'data'      =>  null
			], 500);
		}

		// close cURL resource, and free up system resources
		curl_close($ch);

		return response()->json([
			'success'   =>  true,
			'message'   =>  'Successful request.',
			'data'      =>  $exec,
			'codigo'    =>  200
		], 200);
	}
}