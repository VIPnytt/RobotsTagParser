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
The library is available for install via Composer package. To install via Composer, please add the requirement to your `composer.json` file, like this:

```json
{
    "require": {
        "vipnytt/x-robots-tag-parser": "0.*"
    }
}
```

and then use composer to load the lib:

```php
<?php
require 'vendor/autoload.php';
$parser = new \vipnytt\XRobotsTagParser($url, $userAgent);
...
```

You can find out more about Composer here: https://getcomposer.org/


## Usage
Get rules for a specific UserAgent:
```php
$parser = new \vipnytt\XRobotsTagParser('http://example.com/', 'myUserAgent');
$array = $parser->getRules();
```

Use existing headers:
```php
$parser = new \vipnytt\XRobotsTagParser('http://example.com/', 'myUserAgent', ['headers' => $headers]);
$array = $parser->getRules();
```

Export rules for all UserAgents:
```php
$parser = new \vipnytt\XRobotsTagParser('http://example.com/', 'myUserAgent');
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
