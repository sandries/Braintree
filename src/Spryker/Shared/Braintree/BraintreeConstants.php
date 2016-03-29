<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Braintree;

interface BraintreeConstants
{

    const BRAINTREE = 'braintree';
    const TRANSACTION_GATEWAY_URL = 'BRAINTREE_TRANSACTION_GATEWAY_URL';
    const CALCULATION_GATEWAY_URL = 'BRAINTREE_CALCULATION_GATEWAY_URL';

    const MERCHANT_ID = 'BRAINTREE_MERCHANT_ID';
    const PUBLIC_KEY = 'BRAINTREE_PUBLIC_KEY';
    const PRIVATE_KEY = 'BRAINTREE_PRIVATE_KEY';

    const ENVIRONMENT = 'BRAINTREE_ENVIRONMENT';

    const ACCOUNT_ID = 'BRAINTREE_ACCOUNT_ID';
    const ACCOUNT_UNIQUE_IDENTIFIER = 'BRAINTREE_ACCOUNT_UNIQUE_IDENTIFIER';

    const TRANSACTION_MODE = 'BRAINTREE_TRANSACTION_MODE';
    const CALCULATION_MODE = 'BRAINTREE_CALCULATION_MODE';

    CONST METHOD_PAY_PAL = 'BRAINTREE_METHOD_PAY_PAL';

    const MIN_ORDER_GRAND_TOTAL_INVOICE = 'BRAINTREE_MIN_ORDER_GRAND_TOTAL_INVOICE';
    const MAX_ORDER_GRAND_TOTAL_INVOICE = 'BRAINTREE_MAX_ORDER_GRAND_TOTAL_INVOICE';

    const EMAIL_TEMPLATE_NAME = 'BRAINTREE_EMAIL_TEMPLATE_NAME';
    const EMAIL_FROM_NAME = 'BRAINTREE_EMAIL_FROM_NAME';
    const EMAIL_FROM_ADDRESS = 'BRAINTREE_EMAIL_FROM_ADDRESS';
    const EMAIL_SUBJECT = 'EMAIL_SUBJECT';

}
