[![Build Status](https://travis-ci.org/VIPnytt/X-Robots-Tag-parser.svg?branch=master)](https://travis-ci.org/VIPnytt/X-Robots-Tag-parser)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VIPnytt/X-Robots-Tag-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VIPnytt/X-Robots-Tag-parser/?branch=master)
[![Code Climate](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/badges/gpa.svg)](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser)
[![Test Coverage](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/badges/coverage.svg)](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/coverage)
[![License](https://poser.pugx.org/VIPnytt/X-Robots-Tag-parser/license)](https://github.com/VIPnytt/X-Robots-Tag-parser/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/vipnytt/x-robots-tag-parser.svg)](https://packagist.org/packages/vipnytt/x-robots-tag-parser)
[![Join the chat at https://gitter.im/VIPnytt/X-Robots-Tag-parser](https://badges.gitter.im/VIPnytt/X-Robots-Tag-parser.svg)](https://gitter.im/VIPnytt/X-Robots-Tag-parser)

# X-Robots-Tag HTTP header parser class
PHP class to parse X-Robots-Tag HTTP headers according to [Google X-Robots-Tag HTTP header specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b89a070f-07d3-490a-841a-0ae995574158/big.png)](https://insight.sensiolabs.com/projects/b89a070f-07d3-490a-841a-0ae995574158)

## Installation
The library is available via [Composer](https://getcomposer.org). Add this to your `composer.json` file:

```json
{
    "require": {
        "vipnytt/x-robots-tag-parser": "0.2.*"
    }
}
```
Then run `composer update`.

## Getting Started

### Basic example
Get all rules affecting _you_, this includes the following:
- All general rules
- Rules specific to _your_ User-Agent (if any)
```php
use vipnytt\XRobotsTagParser;

$headers = get_headers('http://example.com/'); // <-- for example only, returns an array

$parser = new XRobotsTagParser('myUserAgent', $headers);
$rules = $parser->getRules(); // <-- returns an array of rules
```

### Different approaches:

#### Get the HTTP headers by requesting an URL
```php
use vipnytt\XRobotsTagParser;

$parser = new XRobotsTagParser\Adapters\Url('http://example.com/', 'myUserAgent');
$rules = $parser->getRules();
```

#### Use your existing GuzzleHttp request
```php
use vipnytt\XRobotsTagParser;
use GuzzleHttp\Client;

$client = new GuzzleHttp\Client();
$response = $client->request('GET', 'http://example.com/');

$parser = new XRobotsTagParser\Adapters\GuzzleHttp($response, 'myUserAgent');
$array = $parser->getRules();
```

#### Provide HTTP headers as an string
```php
use vipnytt\XRobotsTagParser;

$string = <<<STRING
HTTP/1.1 200 OK
Date: Tue, 25 May 2010 21:42:43 GMT
X-Robots-Tag: noindex
X-Robots-Tag: nofollow
STRING;

$parser = new XRobotsTagParser\Adapters\TextString($string, 'myUserAgent');
$array = $parser->getRules();
```

### Export all rules
Returns an array containing all rules for _any_ User-Agent.
```php
use \vipnytt\XRobotsTagParser;

$parser = new XRobotsTagParser('myUserAgent', $headers);
$array = $parser->export();
```

## Supported directives
- [x] ````all```` - There are no restrictions for indexing or serving.
- [x] ````none```` - Equivalent to ````noindex````, ````nofollow````
- [x] ````noindex```` - Do not show this page in search results and do not show a "Cached" link in search results.
- [x] ````nofollow```` - Do not follow the links on this page
- [x] ````noarchive```` - Do not show a "Cached" link in search results.
- [x] ````nosnippet```` - Do not show a snippet in the search results for this page
- [x] ````noodp```` - Do not use metadata from the Open Directory project for titles or snippets shown for this page.
- [x] ````notranslate```` - Do not offer translation of this page in search results.
- [x] ````noimageindex```` - Do not index images on this page.
- [x] ````unavailable_after```` - Do not show this page in search results after the specified date/time.

Contributing is surely allowed! :-)
