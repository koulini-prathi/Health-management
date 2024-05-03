<?php

namespace Drupal\program_details;

use GuzzleHttp\ClientInterface;

Class GetParticipantData {
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
    public function GetParticipantInfo($prgid = NULL) {
        $api_url = 'http://go4fun.com/part-data-rest/'.$prgid; // Replace with your API URL.

        $response = $this->httpClient->request('GET', $api_url, [
            'headers' => [
                'Content-type' => 'application/json',
            ],
            'timeout' => 5,
            'x' => FALSE,
            'Cache-Control' => 'no-cache',
        ]);
        $participant_infos = [];
        $part_data = [];
        $data = json_decode($response->getBody()->getContents());
        $participant_infos = json_decode($data);

        foreach($participant_infos as $participant_info => $info ) {
            $part_data[$info->id] = $info; 
        }
        return $part_data;
    }
}