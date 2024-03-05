<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Utils;

Class GuzzleRequest
{   
    
    protected $client;
    protected $params;
    private $contents;

    /**
     * Available parameters are.
     * ['headers', 'form_params', 'auth', 'json', 'query', 'multipart']
     * 
     */
    public function __construct($array = [])
    {
        
        $this->client   = new Client([
                            'verify' => false
                        ]);

        $this->params   = $array;
    }

    public function submit($method, $url = '')
    {
        try {

            $response = $this->client->$method($url, $this->params);

            $this->contents = json_decode((string)$response->getBody()->getContents(),true);
            return response($this->contents, $response->getStatusCode())->header('Content-Type', 'application/json');
        }

        catch (ClientException $e) {
            $response = $e->getResponse();

            if($response->getStatusCode() != 500)
                $this->contents = json_decode((string)$response->getBody()->getContents(),true);

            return response($response->getBody()->getContents(), $response->getStatusCode())->header('Content-Type', 'application/json');
        }
    }

    public function post($url)
    {
        return $this->submit('post', $url);
    }

    public function get($url)
    {
        return $this->submit('get', $url);
    }

    public function patch($url)
    {
        return $this->submit('patch', $url);
    }

    public function delete($url)
    {
        return $this->submit('delete', $url);
    }

    public function data()
    {
        return $this->contents;
    }


    public static function tryFopen($file_path)
    {
        return Utils::tryFopen($file_path, 'r');
    }
}
