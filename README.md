pushbox
=======

Pushbox is a universal notification push container for apple apns, google gcm, sms etc.


Apns Adapter: https://github.com/tinpont/pushbox/blob/master/APNS.md


Installation
---

Update your `composer.json` file to include these packages as a dependency.
```json
"tinpont/pushbox": "dev-master"
```


Custom your *Adapter*
---
Create a php file at whatever you like, extends *Tinpont\Pushbox\Adapter*

```php
<?php

namespace John\Pushbox;


use Tinpont\Pushbox\Device;
use Tinpont\Pushbox\Options;
use Tinpont\Pushbox\Adapter;

class Sms extends Adapter {
}
```

Overwrite *push* and *isValidToken* method
```php
public function push($message) {
  $this->success = $this->fails = [];

  // You can do what you like here.
  $response = $this->sendSms('12306');

  if ($response['status']) {
    $this->success[] = $response;
  } else {
    $this->fails[] = $response;
  }

  return $this;
}

protected function isValidToken($token) {
  // $token maybe a cellphone number.
  return ctype_digit($token);
}
```
Then you can handle response by yourself.
```php
$success = $sms->success();
$fails = $sms->fails();
```
