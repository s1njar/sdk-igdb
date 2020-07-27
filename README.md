IGDB PHP Api Wrapper
====================

## Introduction
This PHP extension works as a wrapper for interface queries at the IGDB API.
It contains a query builder where you can add search criteria.

You must have an IGDB account in order to use the API. You can register a new 
account at (https://api.igdb.com/).

Project: (https://gitlab.com/s1njar/igdb/)

## Installation

Run composer to require this package:
```bash
composer require s1njar/igdb
```
## Usage

**Note** the parameters (url, apikey, endpoint) are required.

**Default Workflow:**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder($apiKey);

//Add the endpoint to be requested.
$searchBuilder = $searchBuilder->addEndpoint('games');

//Add the fields you want to return.
$searchBuilder = $searchBuilder->addFields(['id', 'name']);

//Add multiple filters to refine the search.
$searchBuilder = $searchBuilder->addFilter('rating', '=', '75');

//Add multiple filters to refine multi filter search.
$searchBuilder = $searchBuilder->addFilter('platforms', '=', (1,2,3));

//Add a limit.
$searchBuilder = $searchBuilder->addLimit('10');

//Add an offset.
$searchBuilder = $searchBuilder->addOffset('0');

//Add an order.
$searchBuilder = $searchBuilder->addOrder('popularity', 'desc');

//Trigger the search. It returns an Response object.
$searchBuilder = $searchBuilder->search();

//Decode the response from the server and return an array of objects.
$response = $searchBuilder->get();
```

**Request by id.**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder($apiKey);

//Add endpoint and search by id.
$response = $searchBuilder
    ->addEndpoint('games')
    ->searchById(1, ['name', 'id'])
    ->get();
```

**Request by search**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder($apiKey);

//Add endpoint, fields and search needle.
$response = $searchBuilder
    ->addEndpoint('games')
    ->addFields(['id'])
    ->addSearch('witcher')
    ->search()
    ->get();
```

## Format of response

The data is returned in Json format and converted to an array of PHP objects.

**Sample Response:**

```php
[
    0 => {
        'id' => '1',
        'name' => 'Witcher'
    },
    1 => {
        'id' => '2',
        'name' => 'Fallout'
    }
]
```

## Run Unit Test

Install phpunit in your environment and  run:

```bash
$ phpunit
```

## IGDB API

- [Get IGDB Account credentials](https://api.igdb.com/)

## Credits

- [s1njar](https://twitter.com/s1njar)

## License

The MIT License (MIT).
