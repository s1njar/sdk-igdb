IGDB PHP Api Wrapper
====================

## Introduction
This PHP extension works as a wrapper for interface queries at the IGDB API.
It contains a query builder where you can add search criteria.

You must have an IGDB account in order to use the API. You can register a new 
account at (https://api.igdb.com/).

## Installation

Run composer to require this package:
```bash
composer require jschubert/igdb:@dev
```
## Usage

**Note** the parameters (url, key, endpoint, fields) are required.

**Default Workflow:**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder($apiKey);

//Add the endpoint to be requested.
$searchBuilder = $searchBuilder->addEndpoint('games');

//Add the fields you want to return.
$searchBuilder = $searchBuilder->addFields(['id', 'name']);

//Add multiple filters to refine the search.
$searchBuilder = $searchBuilder->addFilter('rating', 'eq', '75');

//Add a limit.
$searchBuilder = $searchBuilder->addLimit('10');

//Add an offset.
$searchBuilder = $searchBuilder->addOffset('0');

//Add an order. (field:direction => popularity:desc)
$searchBuilder = $searchBuilder->addOrder('popularity:desc');

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
    ->searchById(1)
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

**Request by scroll.**

```php
//Create new SearchBuilder object.
$searchBuilder = new SearchBuilder($apiKey);

//Create search and add scroll parameter.
$response = $searchBuilder
    ->addEndpoint('games')
    ->addFields(['id'])
    ->addScroll()
    ->search();

//Get header url for next scroll page.
$nextPage = $response->getResponse()->getHeader('X-Next-Page');

//Clear old criteria parameter and search by scroll.
$response = $searchBuilder
    ->clear()
    ->searchByScroll($nextPage[0]);
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

- [Janis Schubert](https://twitter.com/Janis_Schubert)

## License

The MIT License (MIT).