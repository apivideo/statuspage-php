[![badge](https://img.shields.io/twitter/follow/api_video?style=social)](https://twitter.com/intent/follow?screen_name=api_video)

[![badge](https://img.shields.io/github/stars/apivideo/statuspage-php?style=social)](https://github.com/apivideo/statuspage-php)

[![badge](https://img.shields.io/discourse/topics?server=https%3A%2F%2Fcommunity.api.video)](https://community.api.video)

![](https://github.com/apivideo/API_OAS_file/blob/master/apivideo_banner.png)

[api.video](https://api.video) is an API that encodes on the go to facilitate immediate playback, enhancing viewer streaming experiences across multiple devices and platforms. You can stream live or on-demand online videos within minutes.

# apivideo/statuspage-php

[StatusPage](https://statuspage.io) php client by [api.video](https://api.video).

## Install

This package is installable and auto-loadable via [Composer](https://getcomposer.org):

```shell
$ composer require apivideo/statuspage-php
```

### Quick start

Find your [StatusPage account page](https://manage.statuspage.io/pages/723skcwdvm7j/edit) to find your API key and other IDs.

Auto-completion of code is available for every object of the library to ease development. 

```php
<?php

use ApiVideo\StatusPage\Client;

$client = new Client('2c04e0b2-8c4a-b941-de65-012a61b7f6ea'); // User API key 

foreach ($client->components as $component) {
    echo $component->id.': '.$component->name."\n";
}
$client->components->setStatus('7mst16b00d59', 'partial_outage');

foreach ($client->metrics as $metric) {
    echo $metric->id.': '.$metric->name."\n";
}
$client->metrics->addPoint('gu1kkk8qe0dl', 12.5);
```

## Full API

If you have several pages in your StatusPage account, you need to select the page before operating on metrics or components.

It's better to set the default page ID as it avoids an API request to guess it. 


### Client instantiation

```php
<?php

use ApiVideo\StatusPage\Client;

$client = new Client(
    '2c04e0b2-8c4a-b941-de65-012a61b7f6ea', // User API key
    [
        'page-id' => 'zujkhu4kgivg',        // (optional) Default page ID
    ]
);

// You can also set the default page later.
$client->setDefaultPageId('zujkhu4kgivg');
```

### Components API

```php
<?php

use ApiVideo\StatusPage\Client;
use ApiVideo\StatusPage\Model\Component;

$client = new Client(/*..*/);

foreach ($client->components as $component) {
    // Available properties:
    echo $component->id;
    echo $component->name;
    echo $component->description;
    echo $component->created_at;
    echo $component->status;
    echo $component->updated_at;
    echo $component->group;
    echo $component->group_id;
    echo $component->automation_email;
    echo $component->only_show_if_degraded;
    echo $component->page_id;
    echo $component->position;
    echo $component->showcase;
}

$component = $client->components->create([
    'name'        => 'Component name',
    'description' => 'This is an example component',
]);
echo $component->id; // a5xc8i1a03ki
$component = $client->components->update('a5xc8i1a03ki', ['description' => 'Another description']);
$component = $client->components->setStatus('a5xc8i1a03ki', Component::STATUS_MAINTENANCE); // Avoid using magic strings
$client->components->delete('a5xc8i1a03ki');
```

### Metrics API

```php
<?php

use ApiVideo\StatusPage\Client;

$client = new Client(/*..*/);

foreach ($client->metrics as $metric) {
    echo $metric->id;
    echo $metric->name;
    echo $metric->created_at;
    echo $metric->updated_at;
    echo $metric->most_recent_data_at;
    echo $metric->backfilled;
    echo $metric->decimal_places;
    echo $metric->metrics_display_id;
    echo $metric->suffix;
    echo $metric->tooltip_description;
    echo $metric->y_axis_hidden;
    echo $metric->y_axis_max;
    echo $metric->y_axis_min;
}

$metric = $client->metrics->create([
    'name'        => 'Component name',
    'description' => 'This is an example component',
]);
echo $metric->id; // 9zwc0v70t29n
$metric = $client->metrics->update('9zwc0v70t29n', ['description' => 'Another description']);

// Metric points
$client->metrics->addPoint('9zwc0v70t29n', random_int(0, 100));
$metric = $client->metrics->get('9zwc0v70t29n');
echo $metric->name.' (last updated on '.date(DATE_ATOM, $metric->most_recent_data_at).')';
print_r($client->metrics->getPoints('9zwc0v70t29n'));

$client->metrics->delete('9zwc0v70t29n');
```
