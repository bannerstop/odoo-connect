<?php

namespace Bannerstop\OdooConnect\Service;

use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Enum\Field\TrackingCodeField;
use Bannerstop\OdooConnect\Enum\Model;
use Bannerstop\OdooConnect\DTO\TrackingCodeDTO;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use InvalidArgumentException;

class TrackingCodeService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Search tracking codes by Order ID and/or code
     *
     * @param string|null $orderId Order ID to search for
     * @param string|null $code Tracking code to search for
     * @param TrackingCodeField[]|null $fields Fields to retrieve
     * @return TrackingCodeDTO[]|array Array of TrackingCodeDTO objects or an array with specified fields
     * @throws InvalidArgumentException When both orderId and code are null or when mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function searchTrackingCodes(?string $orderId = null, ?string $code = null, ?array $fields = null): array
    {
        if ($orderId === null && $code === null) {
            throw new InvalidArgumentException('At least one search parameter (orderId or code) must be provided');
        }

        $request = $this->requestBuilder->model(Model::BS_TRACKING_CODE);

        if ($orderId !== null) {
            $request->where('sale_order_id', '=', $orderId);
        }

        if ($code !== null) {
            $request->where('code', '=', $code);
        }

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw();
        }

        return $request->get();
    }

    /**
     * Update tracking code's spark field
     *
     * @param int $id Odoo internal ID
     * @param string|false $spark Spark value to set, or false to clear
     * @return bool True if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateSpark(int $id, string|false $spark): bool
    {
        return $this->requestBuilder
            ->model(Model::BS_TRACKING_CODE)
            ->recordId($id)
            ->updateFields([TrackingCodeField::SPARK->value => $spark])
            ->update();
    }
}
