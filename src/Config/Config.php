<?php

namespace Bannerstop\OdooConnect\Config;

use DateTimeZone;
use Exception;
use InvalidArgumentException;

final class Config
{
    private DateTimeZone $returnDataTimezone;
    private DateTimeZone $odooTimezone;

    public function __construct(
        ?string $returnDataTimezone = null,
        string $odooTimezone = 'UTC'
    ) {
        $this->setReturnDataTimezone($returnDataTimezone ?? date_default_timezone_get());
        $this->setOdooTimezone($odooTimezone);
    }

    public function setReturnDataTimezone(string $timezone): void
    {
        try {
            $this->returnDataTimezone = new DateTimeZone($timezone);
        } catch (Exception) {
            throw new InvalidArgumentException("Invalid return data timezone: $timezone");
        }
    }

    public function setOdooTimezone(string $timezone): void
    {
        try {
            $this->odooTimezone = new DateTimeZone($timezone);
        } catch (Exception) {
            throw new InvalidArgumentException("Invalid Odoo timezone: $timezone");
        }
    }

    public function getReturnDataTimezone(): DateTimeZone
    {
        return $this->returnDataTimezone;
    }

    public function getOdooTimezone(): DateTimeZone
    {
        return $this->odooTimezone;
    }
}
