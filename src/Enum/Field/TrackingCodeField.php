<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum TrackingCodeField: string
{
    case ID = 'id';
    case NAME = 'name';
    case CODE = 'code';
    case CARRIER = 'carrier';
    case CREATE_DATE = 'create_date';
    case WRITE_DATE = 'write_date';
    case SPARK = 'spark';
}
