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
     * Search tracking codes by name and/or code
     *
     * @param string|null $name Order name to search for
     * @param string|null $code Tracking code to search for
     * @param TrackingCodeField[]|null $fields Fields to retrieve
     * @return TrackingCodeDTO[]|array Array of TrackingCodeDTO objects or an array with specified fields
     * @throws InvalidArgumentException When both name and code are null or when mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function searchTrackingCodes(?string $name = null, ?string $code = null, ?array $fields = null): array
    {
        if ($name === null && $code === null) {
            throw new InvalidArgumentException('At least one search parameter (name or code) must be provided');
        }

        $request = $this->requestBuilder->model(Model::BS_TRACKING_CODE);

        if ($name !== null) {
            $request->where('name', '=', $name);
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
}
