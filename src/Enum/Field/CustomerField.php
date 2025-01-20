<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum CustomerField: string
{
    case ID = 'id';
    case NAME = 'name';
    case COMPLETE_NAME = 'complete_name';
    case COMMERCIAL_COMPANY_NAME = 'commercial_company_name';
    case EMAIL = 'email_normalized';
    case STREET = 'street';
    case STREET2 = 'street2';
    case ZIP = 'zip';
    case CITY = 'city';
}
