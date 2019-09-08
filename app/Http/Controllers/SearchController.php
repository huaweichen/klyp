<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function index()
    {
        $searchTerm = request()->validate([
            'searchTerm' => 'required'
        ]);

        $apiConfig = config('api');
        $url = sprintf('%s?apiKey=%s&t=%s', $apiConfig['baseUri'], $apiConfig['apiKey'], $searchTerm['searchTerm']);

        $client = new Client();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
        $contents = $response->getBody()->getContents();

        $responseArray = \GuzzleHttp\json_decode($contents, true);

        if (!array_key_exists('Title', $responseArray)) {
            return response($responseArray, Response::HTTP_OK);
        }

        return response([
            'title' => $responseArray['Title'],
            'year' => $responseArray['Year'],
            'runtime' => $responseArray['Runtime'],
        ]);
    }
}
