<?php

namespace DiscordAPI;

use GuzzleHttp\Exception\GuzzleException;

class User
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function me()
    {
        return $this->client->requestGet('/users/@me');
    }
}