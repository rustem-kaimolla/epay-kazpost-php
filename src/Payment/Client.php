<?php

namespace App\Payment;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use SimpleXMLElement;

class Client extends GuzzleClient
{
    protected string $baseUrl = '';

    /**
     * @param string|null $baseUrl
     */
    public function __construct(string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? 'https://epay.post.kz/test/api';

        parent::__construct([
            'base_uri' => $baseUrl,
            'timeout'  => 2.0,
        ]);
    }

    /**
     * Возвращает URL на оплату
     *
     * @param array $params
     *
     * @return string
     */
    public function getPaymentUrl(array $params = []): string
    {
        return $this->baseUrl . '?' . http_build_query($params);
    }

    /**
     * Проверить статус оплаты
     *
     * @param array $params
     *
     * @return false|SimpleXMLElement
     *
     * @throws GuzzleException
     */
    public function checkStatus(array $params)
    {
        $response = $this->post($this->baseUrl, [
            'query' => $params,
        ]);

        return simplexml_load_string($response->getBody()->getContents());
    }
}
