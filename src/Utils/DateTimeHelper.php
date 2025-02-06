<?php
namespace Bannerstop\OdooConnect\Utils;

use Bannerstop\OdooConnect\Exception\OdooApiException;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

class DateTimeHelper
{
    private const TIMEZONE = 'Europe/Berlin';
    private const HOUR_OFFSET = '+1 hour';

    public static function createFromString(?string $dateString): ?DateTimeImmutable
    {
        if (!$dateString) {
            return null;
        }

        try {
            $date = new DateTimeImmutable($dateString, new DateTimeZone(self::TIMEZONE));
            return $date->modify(self::HOUR_OFFSET);
        } catch (Exception) {
            throw new OdooApiException(
                message: sprintf('Invalid date format received from Odoo API: %s', $dateString)
            );
        }
    }
}
