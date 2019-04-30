<?php

namespace Whchi\LaravelApplicationInsights;

use ApplicationInsights\Channel\Telemetry_Channel;
use ApplicationInsights\Telemetry_Client;
use ApplicationInsights\Telemetry_Context;
use GuzzleHttp\Client;
use Whchi\LaravelApplicationInsights\Exceptions\InstrumentationKeyException;

class TelemetryClientWrapper
{
    /**
     * Microsoft azure application insight identify key
     *
     * @var string
     */
    protected $instrumentationKey;

    /**
     * @var Telemetry_Client
     */
    protected $client;

    /**
     * @var Telemetry_Context
     */
    private $context;

    /**
     * azure application insight rest api end point
     *
     * @var string
     */
    private $endpointUri = 'https://dc.services.visualstudio.com/v2/track';

    public function __construct($instrumentationKey, $initWithGuzzleHttpClient)
    {
        $this->setKey($instrumentationKey);
        $this->init($initWithGuzzleHttpClient);
    }

    public function enableHttpClient()
    {
        if (!$this->client) {
            throw new ContextDataFormatException('Telemetry_client not init');
        }

        $this->client->getChannel()->SetClient(new Client);
    }

    /**
     * set opertator
     *
     * @param  string $userId
     * @return void
     */
    public function setUserId(string $userId): void
    {
        $this->context->getUserContext()->setId($userId);
    }

    /**
     * For request context
     *
     * @param string $id
     * @param string $name
     * @return void
     */
    public function setOperationCtx(string $id, string $name)
    {
        $this->context->getOperationContext()->setId($id);
        $this->context->getOperationContext()->setName($name);
    }
    /**
     * set device locale
     *
     * @param  string $locale RFC5646 format: {locale}-{region}
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $pattern = '/^((?:(en-GB-oed|i-ami|i-bnn|i-default|i-enochian|i-hak|i-klingon|i-lux|i-mingo|i-navajo|i-pwn|i-tao|i-tay|i-tsu|sgn-BE-FR|sgn-BE-NL|sgn-CH-DE)|(art-lojban|cel-gaulish|no-bok|no-nyn|zh-guoyu|zh-hakka|zh-min|zh-min-nan|zh-xiang))|((?:([A-Za-z]{2,3}(-(?:[A-Za-z]{3}(-[A-Za-z]{3}){0,2}))?)|[A-Za-z]{4}|[A-Za-z]{5,8})(-(?:[A-Za-z]{4}))?(-(?:[A-Za-z]{2}|[0-9]{3}))?(-(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*(-(?:[0-9A-WY-Za-wy-z](-[A-Za-z0-9]{2,8})+))*(-(?:x(-[A-Za-z0-9]{1,8})+))?)|(?:x(-[A-Za-z0-9]{1,8})+))$/';
        if (!preg_match($pattern, $locale)) {
            throw new ContextDataFormatException('locale is not a valid RFC5646 string');
        }
        $this->context->getDeviceContext()->setLocale($locale);
    }

    /**
     * set request ip
     *
     * @param  string $ip
     * @return void
     */
    public function setIp(string $ip): void
    {
        $this->context->getLocationContext()->setIp($ip);
    }

    public function setUserAgent(string $userAgent)
    {
        $this->context->getDeviceContext()->setModel($userAgent);

    }
    protected function init($initWithGuzzleHttpClient)
    {
        if (isset($this->instrumentationKey)) {
            $initWithGuzzleHttpClient = ($initWithGuzzleHttpClient === true) ? null : false;
            $this->client = new Telemetry_Client(
                new Telemetry_Context,
                new Telemetry_Channel($this->endpointUri, $initWithGuzzleHttpClient)
            );
            $this->client->getContext()->setInstrumentationKey($this->instrumentationKey);
            $this->context = $this->client->getContext();
            $this->context->getSessionContext()->setId(session()->getId());

        } else {
            throw new InstrumentationKeyException('no instrumentationKey found');
        }
    }

    private function setKey($instrumentationKey)
    {
        $this->instrumentationKey = null;

        if ($this->checkKeyValidity($instrumentationKey)) {
            $this->instrumentationKey = $instrumentationKey;
        }

        return $this->instrumentationKey;
    }

    private function checkKeyValidity($instrumentationKey)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $instrumentationKey) === 1) {
            return true;
        }

        throw new InstrumentationKeyException($instrumentationKey . 'is not a valid M$ Application Insights instrumentation key.');
    }

}
