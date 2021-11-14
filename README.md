# optinsoft\QRShow

## Description

QRShow displays QR codes for the data that present in your redis cache.

## License

This software is distributed under BSD 2-Clause License.

## Documentation

### Requirements

PHP 7.2+ with `mbstring` extension.

### Installation

**requires [redis](https://redis.io/), [composer](https://getcomposer.org)**

### For [Apache](https://httpd.apache.org/)

- Copy QRShow to your server, for example to /var/www/qrshow/

```
/var/www/qrshow/
├── conf/
│   └── config.php
├── pub/
│   └── index.php
├── src/
│   ├── components/
│   │   └── qrview.php
│   └── pages/
│       ├── qrdata.php
│       └── qrimage.php
├── composer.json
└── README.md
```

- Go to /var/www/qrshow/ and update packages with composer

```bash
cd /var/www/qrshow/
composer update
```

- In your public html folder create symlink to /var/www/qrshow/pub

```bash
ln -s /var/www/qrshow/pub /var/www/html/qrshow
```

### For CentOS users

If SELinux prevents using network services from Apache and QRShow unable connect to redis, do this:

```bash
setsebool -P httpd_can_network_connect on
```

### Configuring

QRShow configuration file lcated at /conf/config.php

####  `QRShow` constants
name | description
---- | -----------
`QR_AUTO_REFRESH` | Auto-refresh (reload) QR code interval, seconds
`QR_TITLE` | `QR Show` title
`QR_API_KEY` | If it is not empty then putting data to the cache will require provid valid signature.
`QR_TOKEN` | If it is not empty then putting data to the cache will require provide valid token.
`QR_REDIS_HOST` | Redis host.
`QR_REDIS_PORT` | Redis port.
`QR_REDIS_PREFIX` | Keys in redis will be combination of the `QR_REDIS_PREFIX` and id, provided by user.

## Usage

### Display QR code for the data in redis cache with id = 100

In your browser go to link:
```
http://localhost/qrshow/?id=100
```

### Put data to the cache:
```bash
curl -d "id=100&data=test&ttl=30&t=8f83ffeab1a30e2171520589a1d6a01f" -X POST http://localhost/qrshow/
```

#### Parameters

name | description
---- | -----------
`id`|Data identifier
`data`|Data content
`ttl`|Time-to-live, seconds
`s`|Signature, base64-encoded HMAC SHA-512 hash of `id` + `data` + `ttl` + `QR_API_KEY`
`t`|Token