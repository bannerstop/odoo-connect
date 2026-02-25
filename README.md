# odoo-connect

PHP client library for interacting with Odoo ERP via the odoo-relay API. Provides typed DTOs, fluent query builder, and domain-specific services.

## Installation

```bash
composer require bannerstop/odoo-connect
```

Requires PHP 8.1+.

## Setup

```php
use Bannerstop\OdooConnect\Client\OdooConnection;
use Bannerstop\OdooConnect\Client\OdooClient;
use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Config\Config;

// Connection
$connection = new OdooConnection(
    baseUrl: 'https://your-odoo-relay.com',
    apiKey: 'your-api-key',
    db: 'your-database'
);

// Client with default settings
$client = new OdooClient($connection);

// Client with custom settings
$client = new OdooClient(
    connection: $connection,
    config: new Config(returnDataTimezone: 'Europe/Berlin', odooTimezone: 'UTC'),
    requestsPerSecond: 5,  // Rate limiting (default: 3, 0 = disabled)
    maxRetries: 3,         // Retry on failure with exponential backoff (default: 3)
    timeout: 15.0,         // HTTP timeout in seconds (default: 10.0)
);

// RequestBuilder + Services
$requestBuilder = new RequestBuilder($client);

$orderService = new OrderService($requestBuilder);
$customerService = new CustomerService($requestBuilder);
$invoiceService = new InvoiceService($requestBuilder);
$purchaseOrderService = new PurchaseOrderService($requestBuilder);
$trackingCodeService = new TrackingCodeService($requestBuilder);
```

## Configuration

### Timezone

Odoo stores timestamps in UTC. The `Config` class converts them automatically in DTOs:

```php
use Bannerstop\OdooConnect\Config\Config;

$config = new Config(
    returnDataTimezone: 'Europe/Berlin',  // Timezone for returned data (default: PHP default)
    odooTimezone: 'UTC'                   // Timezone Odoo uses (default: UTC)
);

$client = new OdooClient($connection, config: $config);
```

## Fetching Data

### With DTOs (typed objects)

When you call service methods without specifying fields, you get typed DTOs:

```php
$order = $orderService->getOrderByOrderId('S3136366');

echo $order->orderId;        // "S3136366"
echo $order->state->value;   // "sale"
echo $order->amountTotal;    // 129.99
echo $order->spark;          // "some-value" or null
```

### With raw arrays (specific fields)

Pass a fields array to get raw data with only the requested fields:

```php
use Bannerstop\OdooConnect\Enum\Field\OrderField;

$order = $orderService->getOrderByOrderId('S3136366', [
    OrderField::ID,
    OrderField::ORDER_ID,
    OrderField::STATE,
    OrderField::AMOUNT_TOTAL,
]);

echo $order['id'];           // 12345
echo $order['name'];         // "S3136366"
echo $order['state'];        // "sale"
echo $order['amount_total']; // 129.99
```

## Filtering

### Using where()

Build filters using the fluent `where()` method on the RequestBuilder:

```php
use Bannerstop\OdooConnect\Enum\Model;

$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->where('state', '=', 'sale')
    ->where('date_order', '>=', '2025-01-01')
    ->where('date_order', '<=', '2025-12-31')
    ->get();
```

Supported operators: `=`, `!=`, `<`, `>`, `<=`, `>=`, `like`

### Filtering for NULL values

Pass `false` as the value to match records where a field is NULL (unset in Odoo):

```php
// Orders without spark
$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->where('spark', '=', false)
    ->get();

// Orders that have a spark
$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->where('spark', '!=', false)
    ->get();
```

### Using state() shortcut

```php
use Bannerstop\OdooConnect\Enum\State;

$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->state(State::SALES_ORDER)
    ->get();
```

## Pagination & Ordering

All search requests support `limit`, `offset`, and `order` parameters, matching Odoo's query format.

### Via RequestBuilder

```php
// First 10 orders sorted by id descending
$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->where('state', '=', 'sale')
    ->limit(10)
    ->offset(0)
    ->order('id DESC')
    ->getRaw();

// Page 2
$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->where('state', '=', 'sale')
    ->limit(10)
    ->offset(10)
    ->order('id DESC')
    ->getRaw();

// Multi-field sorting
$orders = $requestBuilder
    ->model(Model::SALE_ORDER)
    ->order('date_order DESC, name ASC')
    ->getRaw();
```

### Via Services

All service methods returning multiple records accept optional `limit`, `offset`, and `order`:

```php
$orders = $orderService->getOrdersByDate('2025-01-01', '2025-12-31', limit: 20, order: 'date_order DESC');
$items = $orderService->getOrderItemsByOrderId('S12345', limit: 5, offset: 10);
$invoices = $invoiceService->getInvoicesByShopOrderId('SHOP-123', limit: 10);
$codes = $trackingCodeService->searchTrackingCodes(orderId: 'S12345', limit: 10, order: 'id DESC');
```

### Defaults

| Parameter | Default | Description |
|-----------|---------|-------------|
| `limit` | `80` | Max records returned (Odoo default) |
| `offset` | `0` | Records to skip |
| `order` | *none* | Sort string, e.g. `"name ASC, id DESC"` |

## Services

### OrderService

```php
// Fetch single order
$order = $orderService->getOrderByOrderId('S3136366');
$order = $orderService->getOrderByShopOrderId('SHOP-123');
$order = $orderService->getOrderByShopOrderId('SHOP-123', type: State::SALES_ORDER);

// Fetch multiple orders
$orders = $orderService->getOrdersByDate('2025-01-01', '2025-03-01');
$orders = $orderService->getOrdersByDate('2025-01-01', '2025-03-01', type: State::QUOTE);

// Fetch order line items
$items = $orderService->getOrderItemsByOrderId('S3136366');

// Update fields
$orderService->updateOrderFields($odooId, [OrderField::ORIGIN->value => 'web']);
$orderService->updateOrderLastJiraSync($odooId);
$orderService->updateOrderDateProofAcceptance($odooId);
$orderService->updateOrderDateProofAcceptance($odooId, new DateTime('2025-06-15'));

// Spark
$orderService->updateOrderSpark($odooId, 'spark-value');
$orderService->updateOrderSpark($odooId, false); // clear
$orderService->updateOrderItemSpark($lineId, 'spark-value');

// Add tracking code
$trackingId = $orderService->addTrackingCode(
    orderId: 'S3136366',
    carrier: 'DHL',
    trackingCode: 'ABC123456',
);
// Or with known internal ID (skips lookup)
$trackingId = $orderService->addTrackingCode('S3136366', 'DHL', 'ABC123456', id: 12345);
```

### CustomerService

```php
$customer = $customerService->getCustomerById('42');

echo $customer->name;
echo $customer->email;
echo $customer->city;
echo $customer->countryName;

$customerService->updateSpark($odooId, 'spark-value');
$customerService->updateSpark($odooId, false); // clear
```

### InvoiceService

```php
// Fetch invoices by shop order reference
$invoices = $invoiceService->getInvoicesByShopOrderId('SHOP-123');

// Fetch single invoice
$invoice = $invoiceService->getInvoiceByInvoiceId('INV/2025/0001');

echo $invoice->amountTotal;
echo $invoice->amountResidual;

$invoiceService->updateSpark($odooId, 'spark-value');
```

### PurchaseOrderService

```php
use Bannerstop\OdooConnect\Enum\PurchaseState;

$pos = $purchaseOrderService->getPurchaseOrdersByName('PO-123');
$pos = $purchaseOrderService->getPurchaseOrdersByDate(
    '2025-01-01', '2025-06-01',
    state: PurchaseState::PURCHASE,
    limit: 50,
);
```

### TrackingCodeService

```php
// Search by order ID, code, or both
$codes = $trackingCodeService->searchTrackingCodes(orderId: 'S3136366');
$codes = $trackingCodeService->searchTrackingCodes(code: 'ABC123456');
$codes = $trackingCodeService->searchTrackingCodes(orderId: 'S3136366', code: 'ABC123456');

$trackingCodeService->updateSpark($odooId, 'spark-value');
```

## Creating Records

### Via service methods

```php
$trackingId = $orderService->addTrackingCode('S3136366', 'DHL', 'ABC123456');
```

### Via RequestBuilder (any model)

```php
$newId = $requestBuilder
    ->model(Model::BS_TRACKING_CODE)
    ->create([
        'name' => 'TC-001',
        'carrier' => 'UPS',
        'code' => 'UPS123456',
        'sale_order_id' => 12345,
    ]);
```

## Updating Records

### Via service methods

```php
$orderService->updateOrderFields($odooId, [
    OrderField::ORIGIN->value => 'website',
]);

$orderService->updateOrderSpark($odooId, 'new-spark');
$customerService->updateSpark($odooId, 'new-spark');
$invoiceService->updateSpark($odooId, 'new-spark');
$trackingCodeService->updateSpark($odooId, 'new-spark');

// Pass false to clear spark
$orderService->updateOrderSpark($odooId, false);
```

### Via RequestBuilder (any model)

```php
$requestBuilder
    ->model(Model::SALE_ORDER)
    ->recordId($odooId)
    ->updateFields([
        'origin' => 'website',
        'spark' => 'new-value',
    ])
    ->update();
```

## Error Handling

The library throws three exception types:

```php
use Bannerstop\OdooConnect\Exception\OdooClientException;
use Bannerstop\OdooConnect\Exception\OdooApiException;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;

try {
    $order = $orderService->getOrderByOrderId('S9999999');
} catch (OdooRecordNotFoundException $e) {
    // Record not found in Odoo
    echo $e->getMessage();
} catch (OdooApiException $e) {
    // Odoo API returned an error
    echo $e->getMessage();
    echo $e->getOdooMessage(); // Original Odoo error message
} catch (OdooClientException $e) {
    // HTTP/network error (timeout, connection refused, max retries exceeded)
    echo $e->getMessage();
}
```

| Exception | When |
|-----------|------|
| `OdooRecordNotFoundException` | No record found for the query |
| `OdooApiException` | Odoo API returned a non-success response |
| `OdooClientException` | Network error, timeout, or max retries exceeded |

`OdooRecordNotFoundException` extends `OdooApiException`, so catching `OdooApiException` catches both.

## Available Models

| Enum | Odoo Model | Service |
|------|-----------|---------|
| `Model::SALE_ORDER` | `sale.order` | OrderService |
| `Model::SALE_ORDER_LINE` | `sale.order.line` | OrderService |
| `Model::ACCOUNT_MOVE` | `account.move` | InvoiceService |
| `Model::RES_PARTNER` | `res.partner` | CustomerService |
| `Model::PURCHASE_ORDER` | `purchase.order` | PurchaseOrderService |
| `Model::BS_TRACKING_CODE` | `bs_tracking_code` | TrackingCodeService |
