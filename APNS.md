Pushbox Apns
=======

Ready for push apple apns.

Installation
---

Update your `composer.json` file to include this package as a dependency
```json
"tinpont/pushbox": "dev-master",
"zendframework/zendservice-apple-apns": "1.*"
```


Usage
---

```php
$options = [
  'cert' => 'file_path_to_apns_cert.pem',
  'pass' => 'cert_passphrase'
];
$apns = new \Tinpont\Pushbox\Adapter\Apns($options);
// single device
$apns->to($deviceToken)->push('Hello world.');
// multi device
$apns->to([$deviceToken1, $deviceToken2])->push('Hello world.');

// success
$success = $apns->success();
// fails
$fails = $apns->fails();
```


Advance Usage
---

```php
$device = new \Tinpont\Pushbox\Device($deviceToken, [
  'badge' => 5,
  'custom' => ['one' => 'two']
]);

$message = new \Tinpont\Pushbox\Message('Hello world.', [
  'badge' => 1,
  'actionLocKey' => 'Action button title!',
  'locKey' => 'loc_key',
  'locArgs' => ['loc_arg1', 'loc_arg2', 'loc_arg3'],
  'launchImage' => 'image.jpg',
  'custom' => ['one' => 'three']
]);

$apns->to($device)->push($message);
```


Feedback
---
```php
$responses = $apns->feedback();
foreach ($responses as $token => $time) {
  // do something
}
```


Notice
---
1. *Device* option will merge to *Message* option.
2. *Device* badge will add to *Message* badge.

Options in above example will become:
```
$options = [
  'badge' => 6,
  'actionLocKey' => 'Action button title!',
  'locKey' => 'loc_key',
  'locArgs' => ['loc_arg1', 'loc_arg2', 'loc_arg3'],
  'launchImage' => 'image.jpg',
  'custom' => ['one' => 'two']
];
```


Thanks to
---
* [NotificationPusher](https://github.com/Ph3nol/NotificationPusher)
* [laravel-push-notification](https://github.com/davibennun/laravel-push-notification)