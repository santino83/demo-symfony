<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TravioService
{

    private string $travioId;
    private string $travioKey;

    private string $travioUrl = 'https://api.travio.it';

    private bool $authenticated = false;
    private string $travioToken;

    public function __construct(private readonly HttpClientInterface $client)
    {

    }

    public function authenticate()
    {
        $response = $this->client->request('POST', $this->travioUrl . '/auth', [
            'body' => [
                'id' => $this->travioId,
                'key' => $this->travioKey
            ]
        ]);

        if ($response->getStatusCode() !== 200) throw new \Exception("Cannot authenticate");

        $this->travioToken = $response->toArray()['token'];
        $this->authenticated = (bool)$this->travioToken;
    }

    /**
     * TODO: DEMO METHOD TO SEARCH HOTELS. REFACTORY
     *
     * @param string $from
     * @param string $to
     * @return ResponseInterface
     */
    public function searchHotels(string $from, string $to): ResponseInterface
    {
        $this->ensureAuthenticated();

        $request = json_encode([
            'type' => 'hotels',
            'from' => $from,
            'to' => $to,
            'geo' => [13],
            'occupancy' => [
                ['adults' => 2, 'children' => []]
            ]
        ]);

        $response = $this->client->request('POST', $this->travioUrl . '/booking/search', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->travioToken,
                'Content-Type' => 'application/json;charset=UTF-8'
            ],
            'body' => $request
        ]);

        return $response;
    }

    /**
     * @param string $travioId
     */
    public function setTravioId(?string $travioId): void
    {
        $this->travioId = $travioId;
    }

    /**
     * @param string $travioKey
     */
    public function setTravioKey(?string $travioKey): void
    {
        $this->travioKey = $travioKey;
    }

    /**
     * @param string $travioUrl
     */
    public function setTravioUrl(?string $travioUrl): void
    {
        $this->travioUrl = $travioUrl;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): ?bool
    {
        return $this->authenticated;
    }

    private function ensureAuthenticated(): void
    {
        if (!$this->isAuthenticated()) $this->authenticate();
    }

}