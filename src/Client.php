<?php

namespace DiscordAPI;

use AuthManager\OAuthClientInterface;
use AuthManager\OAuthTokenInterface;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class Client implements OAuthClientInterface
{
    const baseUrl = 'https://discordapp.com/api/v6';
    const tokenUrl = 'https://discordapp.com/api/v6/oauth2/token';
    const authorizeUrl = 'https://discordapp.com/api/oauth2/authorize';

    private $id;
    private $secret;
    private $redirectUri;
    /** @var OAuthTokenInterface */
    private $token;
    private $scope;
    private $httpClient;

    public function __construct($id, $secret, $scope, $redirectUri)
    {
        $this->id = $id;
        $this->secret = $secret;
        $this->scope = $scope;
        $this->redirectUri = $redirectUri;
        $this->httpClient = new HttpClient();
    }

    public function setToken(OAuthTokenInterface $token)
    {
        $this->token = $token;
        return $this;
    }

    public function getClientID(): string
    {
        return $this->id;
    }

    public function getSecretKey(): string
    {
        return $this->secret;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getScope(): array
    {
        return $this->scope;
    }

    public function getAuthorizeURL(): string
    {
        return self::authorizeUrl;
    }

    public function getTokenUrl(): string
    {
        return self::tokenUrl;
    }

    public function getToken(): OAuthTokenInterface
    {
        return $this->token;
    }

    public function getAuthHeaders(): array
    {
        $a = $this->getToken()->getTokenType() . ' ' . $this->getToken()->getAccessToken();
        return [
            'Authorization' => $a,
            'Client-ID' => $this->getClientID(),
        ];
    }

    /**
     * @param $path
     * @param array $query
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function requestGet($path, $query = [], $headers = [])
    {
        $url = self::baseUrl . $path . (empty($query) ? '' : '?' . http_build_query($query));
        $response = $this->httpClient->request('GET', $url, [
            'headers' => array_merge($headers, $this->getAuthHeaders()),
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $path
     * @param $params
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function requestPost($path, $params, $headers = [])
    {
        $params = [
            'verify' => false,
            'headers' => $headers,
            'body' => http_build_query($params),
        ];
        $params['headers'] = array_merge($params['headers'], $this->getAuthHeaders());
        $response = $this->httpClient->request('POST', self::baseUrl . $path, $params);
        return json_decode($response->getBody()->getContents(), true);
    }

}