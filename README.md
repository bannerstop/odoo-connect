## Example of usage

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Bannerstop\OdooConnect\Client\OdooConnection;
use Bannerstop\OdooConnect\Client\OdooClient;
use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Services\OrderService;


$client = new OdooClient(
    new OdooConnection(
        baseUrl: 'api-url', 
        apiKey: 'api-key', 
        db: 'database-name'
    )
);

$requestBuilder = new RequestBuilder($client);
$orderService = new OrderService($requestBuilder);

$order = $orderService->getOrderByOrderId('127/11/2024');

print_r($order);
```