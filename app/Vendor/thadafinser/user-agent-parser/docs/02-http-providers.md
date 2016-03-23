
# HTTP providers

A detailed description of all HTTP providers

- [General](#general)
- [Note about HTTP providers](#note-about-http-providers)
- [DeviceAtlasCom](#deviceatlascom)
- [NeutrinoApiCom](#neutrinoapicom)
- [UdgerCom](#udgercom)
- [UserAgentApiCom](#iseragentapicom)
- [UserAgentStringCom](#useragentstringcom)
- [WhatIsMyBrowserCom](#whatismybrowsercom)


## General

Regardless of which HTTP provider you use, you need always a valid client to connect.

This library uses the `GuzzleHttp` library.

```php
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

$handler = new CurlHandler();

$stack = HandlerStack::create($handler);

$client = new Client([
    'handler' => $stack,
    'timeout' => 3,

    'curl' => [
        // CURLOPT_SSL_VERIFYHOST => false,
        // CURLOPT_SSL_VERIFYPEER => false
    ]
]);
```

In all following examples, we assume that you defined the `$client` already and it's working.

## Note about HTTP providers

The client can throw additional `Exceptions` like timeout/no connection/... if something is not working well.

Also the API provider can have have issues.

Be aware that it can fail, when you use HTTP providers


## DeviceAtlasCom

Go to [https://deviceatlas.com/](https://deviceatlas.com/) and get a valid API key.

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\DeviceAtlasCom($client, 'YOUR_API_KEY');

$result = $provider->parse($userAgent, $headers);
```


## NeutrinoApiCom

Go to [https://www.neutrinoapi.com/](https://www.neutrinoapi.com/) and get a valid API key.

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\NeutrinoApiCom($client, 'YOUR_USER_ID', 'YOUR_API_KEY');

$result = $provider->parse($userAgent, $headers);
```


## UdgerCom

Go to [https://udger.com/](https://udger.com/) and get a valid API key.

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\UdgerCom($client, 'YOUR_API_KEY');

$result = $provider->parse($userAgent, $headers);
```



## UserAgentApiCom

Go to [https://useragentapi.com/](https://useragentapi.com/) and get a valid API key.

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\UserAgentApiCom($client, 'YOUR_API_KEY');

$result = $provider->parse($userAgent, $headers);
```


## UserAgentStringCom

Nothing to do, just use it 

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\UserAgentStringCom($client);

$result = $provider->parse($userAgent, $headers);
```


## WhatIsMyBrowserCom

Go to [https://www.whatismybrowser.com/](https://www.whatismybrowser.com/) and get a valid API key.

### Use it

```php
use UserAgentParser\Provider;

// @see $client definition in chapter "General"

$deviceAtlas = new Provider\Http\WhatIsMyBrowserCom($client, 'YOUR_API_KEY');

$result = $provider->parse($userAgent, $headers);
```

