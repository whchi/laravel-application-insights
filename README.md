A wrapper of [ApplicationInsights-PHP](https://github.com/Microsoft/ApplicationInsights-PHP)

Reference: [ms-application-insights-laravel](https://github.com/Marchie/ms-application-insights-laravel)

# Installation
1. require package
```bash
composer require whchi/application-insights
```
2. publish vendor
```bash
php artisan vendor:publish --provider="Whchi\LaravelApplicationInsights\AppInsightsServiceProvider"
```
3. setup config `config/appinsights.php`
```php
return [
    'instrumentationKey' => 'find it on Microsoft Azure portal (https://portal.azure.com)'
    'initWithGuzzleHttpClient' => true, // 如果要用 event queue 則設定為 false
];

```
4. start use
* with facade
```php
'aliases' => [
   ...
   'AppInsights' => Whchi\LaravelApplicationInsights\Facades\AppInsights::class,
   ...
 ];
```
* with event queue

add event listener
```php
// in EventServiceProvider
 protected $listen = [
      'App\Events\AppInsightsLogEvent' => [
          'App\Listeners\AppInsightsLogEventListener',
      ]
  ];
```
trigger event queue
```php
$appInsightsObj = \App::make('AppInsights');
$appInsightsObj->setException(['exception' => new \Exception('exception'), 'created_at' => \Carbon\Carbon::now()]);
event(new \App\Events\AppInsightsLogEvent($appInsightsObj));
```

# Usage

### use native methods
see: [ApplicationInsights-PHP](https://github.com/Microsoft/ApplicationInsights-PHP)
### use wrap method
### 設定 operation context (MUST if use setRequest)
```php
AppInsights::setOperationCtx('operation id', 'operation name');
```
#### 設定裝置資訊 (SHOULD)
```php
AppInsights::setDeviceInfo(string $deviceType = '裝置類型(mobile/pc...)', string $osVersion = 'default: user-agent')
```
#### 設定ip (SHOULD)
```php
AppInsights::setIp('127.0.0.1');
```
#### 設定locale (SHOULD)
比照 [RFC5646](https://tools.ietf.org/html/rfc5646)
```php
AppInsights::setLocale('zh-TW');
```
#### 設定 userId (SHOULD)
```php
AppInsights::setUserId('testuser@example.com');
```
### 發送log

#### exception
```php
$required = ['exception' => new \Exception('exception'), 'created_at' => \Carbon\Carbon::now()];
$optional = ['properties' => ['hello' => 'world'], 'measurments' => ['duration' => 123.12]];
AppInsights::setException($required + $optional);
AppInsights::send();
```
#### dependency
這個是屬於比較彈性的部分，可自定義類別提供資料，參考[微軟官方文件說明](https://docs.microsoft.com/en-us/azure/azure-monitor/app/asp-net-dependencies)
```php
$required = ['name' => 'an identifier', 'type' => 'SQL', 'commandName' => 'SELECT * FROM TABLE', 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'durationInMilliseconds' => 1000,
  'isSuccessful' => true,
  'resultCode' => 200,
  'properties' => ['hello' => 'world'],
];
AppInsights::setDependency($required + $optional);
AppInsights::send();
```
#### page view
```php
$required = ['name' => 'an identifier', 'uri' => 'https://example.com/', 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'duration' => 1000,
  'properties' => ['hello' => 'world'],
  'measurments' => ['duration' => 123.12]
];
AppInsights::setPageView($required + $optional);
AppInsights::send();
```
#### event
```php
$required = ['event' => 'an identifier', 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'properties' => ['hello' => 'world'],
  'measurments' => ['duration' => 123.12]
];
AppInsights::setEvent($required + $optional);
AppInsights::send();
```
#### request
```php
$required = ['name' => 'an identifier', 'uri' => 'https://example.com/', 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'durationInMilliseconds' => 1000,
  'isSuccessful' => true,
  'httpResponseCode' => 200,
  'properties' => ['hello' => 'world'],
  'measurments' => ['duration' => 123.12]
];
AppInsights::setRequest($required + $optional);
AppInsights::send();
```
#### metric
自訂義的衡量標準，比如說在程式執行速度或是經過的class數量等，`stdDev`為標準差

| type | 描述        |
| :--- | :---------- |
| 0    | Measurement |
| 1    | Aggregation |
```php
$required = ['name' => 'an identifier', 'value' => 42.1, 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'type' => 0,
  'count' => 124,
  'min' => 10.3,
  'max' => 99.2,
  'stdDev' => 1233.1,
  'properties' => ['hello' => 'world'],
];
AppInsights::setMetric($required + $optional);
AppInsights::send();
```
#### message
| level | 描述        |
| :---- | :---------- |
| 0     | verbose     |
| 1     | information |
| 2     | warning     |
| 3     | error       |
| 4     | critical    |
```php
$required = ['message' => 'message to send', 'created_at' => \Carbon\Carbon::now()];
$optional = [
  'level' => 0,
  'properties' => ['hello' => 'world'],
];
AppInsights::setMessage($required + $optional);
AppInsights::send();
```
