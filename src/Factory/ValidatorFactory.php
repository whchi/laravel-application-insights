<?php

namespace Whchi\LaravelApplicationInsights\Factory;

use Whchi\LaravelApplicationInsights\Exceptions\ContextDataFormatException;
use Whchi\LaravelApplicationInsights\Validators\DependencyData;
use Whchi\LaravelApplicationInsights\Validators\EventData;
use Whchi\LaravelApplicationInsights\Validators\ExceptionData;
use Whchi\LaravelApplicationInsights\Validators\MessageData;
use Whchi\LaravelApplicationInsights\Validators\MetricData;
use Whchi\LaravelApplicationInsights\Validators\PageViewData;
use Whchi\LaravelApplicationInsights\Validators\RequestData;

class ValidatorFactory
{
    public function messageType(string $messageType)
    {
        switch ($messageType) {
            case 'exception':
                return new ExceptionData;
            case 'message':
                return new MessageData;
            case 'dependency':
                return new DependencyData;
            case 'pageView':
                return new PageViewData;
            case 'event':
                return new EventData;
            case 'request':
                return new RequestData;
            case 'metric':
                return new MetricData;
            default:
                throw new ContextDataFormatException('Invalid message type');
        }
    }
}
