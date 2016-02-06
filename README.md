[![Build Status](https://travis-ci.org/VIPnytt/X-Robots-Tag-parser.svg?branch=master)](https://travis-ci.org/VIPnytt/X-Robots-Tag-parser) [![Code Climate](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/badges/gpa.svg)](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser) [![Test Coverage](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/badges/coverage.svg)](https://codeclimate.com/github/VIPnytt/X-Robots-Tag-parser/coverage) [![License](https://poser.pugx.org/VIPnytt/X-Robots-Tag-parser/license)](https://packagist.org/packages/VIPnytt/X-Robots-Tag-parser) [![Join the chat at https://gitter.im/VIPnytt/X-Robots-Tag-parser](https://badges.gitter.im/VIPnytt/X-Robots-Tag-parser.svg)](https://gitter.im/VIPnytt/X-Robots-Tag-parser)

# X-Robots-Tag HTTP header parser class
PHP class to parse X-Robots-Tag HTTP headers according to [Google X-Robots-Tag HTTP header specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header).

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
*Coming soon...*

## Directives
### Supported:
- [x] ````all```` - There are no restrictions for indexing or serving.
- [x] ````none```` - Equivalent to ````noindex````, ````nofollow````
- [x] ````noindex```` - Do not show this page in search results and do not show a "Cached" link in search results.
- [x] ````nofollow```` - Do not follow the links on this page
- [x] ````noarchive```` - Do not show a "Cached" link in search results.
- [x] ````nosnippet```` - Do not show a snippet in the search results for this page
- [x] ````noodp```` - Do not use metadata from the Open Directory project for titles or snippets shown for this page.
- [x] ````notranslate```` - Do not offer translation of this page in search results.
- [x] ````noimageindex```` - Do not index images on this page.
### Unsupported (work in progress):
- [ ] ````unavailable_after: [RFC-850 date/time]```` -Do not show this page in search results after the specified date/time.