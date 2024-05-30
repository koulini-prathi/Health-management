<?php

namespace Drupal\api_retrieves;

use GuzzleHttp\ClientInterface;

class GetProgramSchedule {
    /**
     * The HTTP client to fetch the feed data with.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    public function __construct(ClientInterface $http_client) {
        $this->httpClient = $http_client;
    }
 
    /**
     * Make an API request.
     *
     * @return string
     *   The API response.
     */
    public function GetProgramSchedule($prgid) {
        $api_url = 'http://admincontrolhub.com/prgsch-rest'.$prgid.'?_format=json'; // Replace with your API URL.

        // You can include credentials in the request headers or query parameters.
        $response = $this->httpClient->request('GET', $api_url, [
            'headers' => [
                'Content-type' => 'application/json',
            ],
            'timeout' => 5,
            'x' => FALSE,
            'Cache-Control' => 'no-cache',
        ]);
        $prg_ids = [];
        $data = json_decode($response->getBody()->getContents());
        return $data;
    }
}
