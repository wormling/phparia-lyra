phparia-lyra - [phparia](http://phparia.org) extension for [Sangoma Lyra Answering Machine Detection for Asterisk.](http://www.sangoma.com/products/answering-machine-detection-for-asterisk-sangoma-lyra)
===

Features
---

* Receive Lyra CPD-Result events in phparia applications.

Available via Composer
---
Just add the package "wormling/phparia-lyra":

```yaml
    {
        "require": {
            "wormling/phparia-lyra": "dev-master"
        }
    }
```

Listening for AMD results
---
```php
    $phpariaLyra = new \phpariaLyra\Client\PhpariaLyra($phparia);
    
    $phpariaLyra->onCpdResult(function (CpdResult $cpdResult) {
        echo $cpdResult->getResult();
    });
```

Examples
---
(https://github.com/wormling/phparia/tree/master/src/wormling/phparia-lyra/Examples)

License
---
Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
