# Community Store API Client

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This project contains a library that makes it easy to use the [Community Store API](https://github.com/concretecms-community-store/community_store_api).

You can use it in concrete5 v8 and in ConcreteCMS v9+ projects, as well as in custom (non concrete) projects.


## Installation

### With [Composer](https://getcomposer.org)

Simply add the `concretecms-community-store/community_store_api_client` dependency:

```sh
composer require concretecms-community-store/community_store_api_client
```

### Without [Composer](https://getcomposer.org)

Download this repository somewhere and include the `register.php` file.

For example, if your local copy of this project is available at `/path/to/community_store_api_client`, add this line in your PHP files:

```php
require_once '/path/to/community_store_api_client/register.php';
```


## Usage

### Creating the Client instance

You need the base URL of your website, as well as the client ID and client secret.

```php
$baseURL = 'http://www.yoursite.com';
$clientID = 'your-client-id';
$clientSecret = 'your-client-secret';
```

You also need to tell the client the scopes you need:

```php
use CommunityStore\APIClient\Scope;

$scopes = [
    Scope::CONFIG_READ,
    Scope::PRODUCTS_READ,
    Scope::PRODUCTS_WRITE,
    Scope::ORDERS_READ,
    Scope::ORDERS_WRITE,
];
```

#### In a concrete5/ConcreteCMS environment

```php
use CommunityStore\APIClient\Client;

$app = app();

$client = new Client(
    $baseURL
    $clientID,
    $clientSecret,
    $scopes,
    $app->make('http/client'),
    $app->make('cache/expensive')
);
```

#### Not in a concrete5/ConcreteCMS environment

In this case, you need the [Guzzle HTTP Client library](https://docs.guzzlephp.org):

```php
use CommunityStore\APIClient\Client;
use GuzzleHttp\Client as GuzzleClient;

$client = new Client(
    $baseURL
    $clientID,
    $clientSecret,
    $scopes,
    new GuzzleClient()
);
```

### Retrieving the Community Store configuration

```php
$configuration = $client->getConfiguration();

echo 'Currency code: ', $configuration->currency->code, "\n";
echo 'Currency symbol: ', $configuration->currency->symbol, "\n";
echo 'Currency decimal digits: ', $configuration->currency->decimalDigits, "\n";
```

Sample output:

```
Currency code: EUR
Currency symbol: €
Currency decimal digits: 2
```

### Retrieving the defined fulfilment satuses

```php
$statuses = $client->getFulfilmentStatuses();

echo 'Number of statuses: ', count($statuses), "\n";
echo 'ID of first status: ', $statuses[0]->id, "\n";
echo 'Handle of first status: ', $statuses[0]->handle, "\n";
echo 'Name of first status: ', $statuses[0]->name, "\n";
```

Sample output:

```
Number of statuses: 6
ID of first status: 1
Handle of first status: incomplete
Name of first status: Awaiting Processing
```

### Retrieving an order given its ID

```php
foreach ([123456, 11] as $id) {
    $order = $client->getOrder($id);
    if ($order === null) {
        echo "Order with ID {$id} could not be found\n\n";
        continue;
    }
    echo 'Order ID: ', $order->id, "\n";
    echo 'Date placed: ', $order->datePlaced->format('c'), "\n";
    echo 'Total: ', $order->total, "\n";
    echo 'Payment method: ', $order->paymentMethodName, "\n";
    echo 'Payment date: ', $order->paymentDate === null ? 'n/a' : $order->paymentDate->format('c'), "\n";
    echo 'Payment reference: ', $order->paymentReference, "\n";
    echo 'Shipping method: ', $order->shippingMethodName, "\n";
    echo 'Fulfilment status name: ', $order->fulfilment->statusName, "\n";
    echo 'Fulfilment status handle: ', $order->fulfilment->statusHandle, "\n";
    echo 'Tracking ID: ', $order->fulfilment->trackingID, "\n";
    echo 'Tracking code: ', $order->fulfilment->trackingCode, "\n";
    echo 'Tracking url: ', $order->fulfilment->trackingURL, "\n";
    echo 'Language: ', $order->locale, "\n";
    echo 'Customer email: ', $order->customer->email, "\n";
    echo 'Customer user name: ', $order->customer->username, "\n";
    echo 'Billing first name: ', $order->customer->billing->firstName, "\n";
    echo 'Billing last name: ', $order->customer->billing->lastName, "\n";
    echo 'Billing company: ', $order->customer->billing->company, "\n";
    echo 'Billing address line 1: ', $order->customer->billing->address->address1, "\n";
    echo 'Billing city: ', $order->customer->billing->address->city, "\n";
    echo 'Billing state/province: ', $order->customer->billing->address->stateProvince, "\n";
    echo 'Billing country code: ', $order->customer->billing->address->country, "\n";
    echo 'Billing postal code: ', $order->customer->billing->address->postalCode, "\n";
    echo 'Billing phone: ', $order->customer->billing->phone, "\n";
    echo 'Billing VAT: ', $order->customer->billing->vatNumber, "\n";
    echo 'Shipping first name: ', $order->customer->shipping->firstName, "\n";
    echo 'Shipping last name: ', $order->customer->shipping->lastName, "\n";
    echo 'Shipping company: ', $order->customer->shipping->company, "\n";
    echo 'Shipping address line 1: ', $order->customer->shipping->address->address1, "\n";
    echo 'Shipping city: ', $order->customer->shipping->address->city, "\n";
    echo 'Shipping state/province: ', $order->customer->shipping->address->stateProvince, "\n";
    echo 'Shipping country code: ', $order->customer->shipping->address->country, "\n";
    echo 'Shipping postal code: ', $order->customer->shipping->address->postalCode, "\n";
    echo 'Number of custom attributes: ', count($order->attributes), "\n";
    echo 'Refunded: ', $order->refunded === null ? 'no' : ('on ' . $order->refunded->date->format('c') . " ({$order->refunded->reason})"), "\n";
    echo 'Number of items: ', count($order->items), "\n";
    echo 'First item - ID: ', $order->items[0]->id, "\n";
    echo 'First item - name: ', $order->items[0]->name, "\n";
    echo 'First item - SKU: ', $order->items[0]->sku, "\n";
    echo 'First item - quantity: ', $order->items[0]->quantity, "\n";
    echo 'First item - price: ', $order->items[0]->price, "\n";
    echo 'First item - number of options: ', count($order->items[0]->options), "\n";
    echo 'First item - number of digital uploads: ', count($order->items[0]->uploads), "\n";
    echo "\n";
}
```

Sample output:

```
Order with ID 123456 could not be found

Order ID: 11
Date placed: 2024-01-23T16:34:42+01:00
Total: 123
Payment method: Invoice
Payment date: 2024-01-23T16:37:16+01:00
Payment reference: 
Shipping method: 
Fulfilment status name: Awaiting Processing
Fulfilment status handle: incomplete
Tracking ID: 
Tracking code: 
Tracking url: 
Language: en_US
Customer email: john@doe.com
Customer user name: john
Billing first name: John
Billing last name: Doe
Billing company: JoeCo
Billing address line 1: 20, Ocrean Street
Billing city: New York
Billing state/province: NY
Billing country code: US
Billing postal code: 10001
Billing phone: +1234567
Billing VAT: US12345678
Shipping first name: Jane
Shipping last name: Doe
Shipping company: JaneDo
Shipping address line 1: 30, Lake Street
Shipping city: San Francisco
Shipping state/province: CA
Shipping country code: US
Shipping postal code: 94016
Number of custom attributes: 2
Refunded: no
Number of items: 1
First item - ID: 321
First item - name: Stormtrooper armor
First item - SKU: SW-ST-A
First item - quantity: 1
First item - price: 123
First item - number of options: 0
First item - number of digital uploads: 0
```

### Listing orders

Look for orders placed in the last 7 days, which are updaid and are awaiting processing:

```php
use CommunityStore\APIClient\Entity\FulfilmentStatus;
use CommunityStore\APIClient\Query;
use CommunityStore\APIClient\Query\Orders\PaymentStatus;

$query = new Query\Orders();
$query->fromDate = new \DateTimeImmutable('-7 days');
$query->paymentStatus = PaymentStatus::INCOMPLETE;
$query->status = FulfilmentStatus::AWAITING_PROCESSING;

$orders = $client->getOrders($query, $pagination);
/** @var \CommunityStore\APIClient\Entity\Pagination $pagination */
while (true) {
    echo 'Found ', count($orders), ' orders in page ', $pagination->currentPage, "\n";
    $orders = $client->getNextOrders($pagination, $newPagination);
    if ($orders === []) {
        break;
    }
    $pagination = $newPagination;
}
```

Sample output:

```
Found 20 orders in page 1
Found 5 orders in page 2
```

### Updating an order

Update the order with ID 5:

```php
use CommunityStore\APIClient\Entity\FulfilmentStatus;
use CommunityStore\APIClient\Query\OrderPatch;

$patch = new OrderPatch(5);
$patch->fulfilment->trackingID = 'TRK-ID-001';
$patch->fulfilment->trackingCode = 'TRK-CODE-001';
$patch->fulfilment->trackingURL = 'https://www.carrier.com/?foo=bar';
$patch->fulfilment->status = FulfilmentStatus::SHIPPED;

$order = $client->updateOrder($patch);

echo "Order with ID {$order->id} has been updated.\n";
echo "Its status is now {$order->fulfilment->statusName}.\n";
```

Sample output:

```
Order with ID 5 has been updated.
Its status is now Shipped.
```

### Product related operations

Still to be implemented.

