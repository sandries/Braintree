<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Braintree\BraintreeConfig;

abstract class AbstractPaymentMethod
{

    const BRAINTREE_DATE_FORMAT = 'Y-m-d';

    /**
     * @var \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(BraintreeConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    abstract public function getMethodType();

    /**
     * @param int $grandTotal
     * @param string $currency
     * @param string $idOrder
     *
     * @return array
     */
    protected function getBaseTransactionRequest($grandTotal, $currency, $idOrder)
    {
        return [
            ApiConstants::TRANSACTION_MODE => $this->getConfig()->getEnvironment(),
            ApiConstants::PRESENTATION_AMOUNT => $this->getCurrencyManager()->convertCentToDecimal($grandTotal),
            ApiConstants::PRESENTATION_USAGE => $idOrder,
            ApiConstants::PRESENTATION_CURRENCY => $currency,
            ApiConstants::IDENTIFICATION_TRANSACTIONID => $idOrder,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $paymentCode
     * @param string $uniqueId
     *
     * @return array
     */
    protected function getBaseTransactionRequestForPayment(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $paymentCode,
        $uniqueId
    ) {
        $requestData = $this->getBaseTransactionRequest(
            $orderTransfer->getTotals()->getGrandTotal(),
            $paymentEntity->getCurrencyIso3Code(),
            $orderTransfer->getIdSalesOrder()
        );

        $this->addRequestData(
            $requestData,
            [
                ApiConstants::PAYMENT_CODE => $paymentCode,
                ApiConstants::IDENTIFICATION_REFERENCEID => $uniqueId,
            ]
        );

        return $requestData;
    }

    /**
     * @param array $requestData
     * @param array $additionalData
     *
     * @return void
     */
    protected function addRequestData(&$requestData, $additionalData)
    {
        foreach ($additionalData as $fieldName => $value) {
            $requestData[$fieldName] = $value;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function formatAddress($addressTransfer)
    {
        return trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     *
     * @todo: use currency/money bundle #989
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
