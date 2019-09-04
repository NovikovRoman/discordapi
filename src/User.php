<?php

namespace DiscordAPI;

class User
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function me()
    {
        return $this->client->requestGet('/users/@me');
    }
}