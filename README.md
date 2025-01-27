## Example of usage

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bannerstop\OdooConnect\Client\OdooConnection;
use Bannerstop\OdooConnect\Client\OdooClient;
use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Service\OrderService;
use Bannerstop\OdooConnect\Service\CustomerService;
use Bannerstop\OdooConnect\Enum\Field\OrderField;
use Bannerstop\OdooConnect\Enum\Field\CustomerField;
use Bannerstop\OdooConnect\Enum\State;

$client = new OdooClient(
    new OdooConnection(
        baseUrl: 'api-url', 
        apiKey: 'api-key', 
        db: 'database-name'
    )
);

$requestBuilder = new RequestBuilder($client);
$orderService = new OrderService($requestBuilder);
$customerService = new CustomerService($requestBuilder);

// Fetch order by order ID with specific fields
$order = $orderService->getOrderByOrderId('3136366', [OrderField::ORDER_ID, OrderField::STATE]);

print_r($order);

// Fetch orders by date range and state
$orders = $orderService->getOrdersByDate('2025-01-18', '2025-01-19', State::QUOTE, [OrderField::ORDER_ID, OrderField::AMOUNT_TOTAL]);

print_r($orders);

// Example with DTOs
$orderDTO = $orderService->getOrderByOrderId('3136366');
$customerDTO = $customerService->getCustomerById($orderDTO->customerId);

print_r($orderDTO);
print_r($customerDTO);
```