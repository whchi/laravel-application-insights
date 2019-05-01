<?php

namespace Whchi\LaravelApplicationInsights;

use ApplicationInsights\Channel\Contracts\Message_Severity_Level;
use Log;
use Throwable;
use Whchi\LaravelApplicationInsights\Factory\ValidatorFactory;
use Whchi\LaravelApplicationInsights\TelemetryClientWrapper;

class AppInsights extends TelemetryClientWrapper
{
    private $async = false;
    private $dataValidator;
    /**
     * init with guzzle if set to null
     *
     * @param mixed $initWithGuzzle
     */
    public function __construct(string $instrumentationKey, $initWithGuzzleHttpClient)
    {
        parent::__construct($instrumentationKey, $initWithGuzzleHttpClient);
        $this->dataValidator = new ValidatorFactory;
    }
    /**
     * call native methods provided by microsoft/application-insights
     *
     * @see   https://github.com/Microsoft/ApplicationInsights-PHP
     * @param string $name      function name
     * @param mixed  $arguments parameters to passin
     */
    public function __call($name, $arguments)
    {
        if (isset($this->instrumentationKey, $this->client)) {
            return call_user_func_array([ & $this->client, $name], $arguments);
        }
    }
    /**
     * set async call or not
     *
     * @param  boolean $async
     * @return void
     */
    public function setAsync(bool $async): void
    {
        $this->async = $async;
    }

    /**
     * flush wrapper
     */
    public function send()
    {
        try {
            $options = [];
            if ($this->userAgent !== '') {
                $options = ['headers' => ['User-Agent' => $this->userAgent]];
            }
            $this->client->flush($options, $this->async);
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            throw $th;
        }
    }
    /**
     * trackException wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setException(array $data): void
    {
        $validator = $this->dataValidator->messageType('exception');
        $validator->validate($data, ['exception' => 'Throwable', 'created_at' => 'Carbon']);

        $ex = $data['exception'];
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;
        $measurments = $data['measurments'] ?? null;

        $this->client->trackException($ex, $properties, $measurments);
    }
    /**
     * trackPageView wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setPageView(array $data): void
    {
        $validator = $this->dataValidator->messageType('pageView');
        $validator->validate($data, ['name' => 'string', 'uri' => 'uri', 'created_at' => 'Carbon']);

        $name = $data['name'];
        $uri = $data['uri'];
        $duration = $data['duration'] ?? 0;
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;
        $measurments = $data['measurments'] ?? null;

        $this->client->trackPageView($name, $uri, $duration, $properties, $measurments);
    }
    /**
     * trackEvent wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setEvent(array $data): void
    {
        $validator = $this->dataValidator->messageType('event');
        $validator->validate($data, ['event' => 'string', 'created_at' => 'Carbon']);

        $event = $data['event'];
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;
        $measurments = $data['measurments'] ?? null;

        $this->client->trackEvent($event, $properties, $measurments);
    }
    /**
     * trackRequest wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setRequest(array $data): void
    {
        $validator = $this->dataValidator->messageType('request');
        $validator->validate($data, ['name' => 'string', 'uri' => 'uri', 'created_at' => 'Carbon']);

        $name = $data['name'];
        $uri = $data['uri'];
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;
        $measurments = $data['measurments'] ?? null;
        $durationInMilliseconds = $data['durationInMilliseconds'] ?? 0;
        $httpResponseCode = $data['httpResponseCode'] ?? 200;
        $isSuccessful = $data['isSuccessful'] ?? true;
        $startTime = time();

        $this->client->trackRequest(
            $name,
            $uri,
            $startTime,
            $durationInMilliseconds,
            $httpResponseCode,
            $isSuccessful,
            $properties,
            $measurments
        );
    }

    /**
     * trackDependency wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setDependency(array $data): void
    {
        $validator = $this->dataValidator->messageType('dependency');
        $validator->validate($data, ['name' => 'string', 'type' => 'string', 'commandName' => 'string', 'created_at' => 'Carbon']);

        $name = $data['name'];
        $type = $data['type'];
        $commandName = $data['commandName'];
        $startTime = time();
        $durationInMilliseconds = $data['durationInMilliseconds'] ?? 0;
        $isSuccessful = $data['isSuccessful'] ?? true;
        $resultCode = $data['resultCode'] ?? 200;
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;

        $this->client->trackDependency(
            $name,
            $type,
            $commandName,
            $startTime,
            $durationInMilliseconds,
            $isSuccessful,
            $resultCode,
            $properties
        );
    }

    /**
     * trackMetric wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setMetric(array $data): void
    {
        $validator = $this->dataValidator->messageType('metric');
        $validator->validate($data, ['name' => 'string', 'value' => 'double', 'created_at' => 'Carbon']);

        $name = $data['name'];
        $value = $data['value'];
        $type = $data['type'] ?? null;
        $count = $data['count'] ?? null;
        $min = $data['min'] ?? null;
        $max = $data['max'] ?? null;
        $stdDev = $data['stdDev'] ?? null;
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;

        $this->client->trackMetric(
            $name,
            $value,
            $type,
            $count,
            $min,
            $max,
            $stdDev,
            $properties
        );
    }
    /**
     * trackMessage wrapper
     *
     * @param  array $data
     * @return void
     */
    public function setMessage(array $data): void
    {
        $validator = $this->dataValidator->messageType('message');
        $validator->validate($data, ['message' => 'string', 'created_at' => 'Carbon']);

        $message = $data['message'];
        $data['properties']['created_at'] = $data['created_at']->toRfc3339String();
        $properties = $data['properties'] ?? null;
        $level = $data['level'] ?? Message_Severity_Level::VERBOSE;

        $this->client->trackMessage($message, $level, $properties);
    }
}
