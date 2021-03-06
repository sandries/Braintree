<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Log;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class TransactionStatusLog implements TransactionStatusLogInterface
{

    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     */
    public function __construct(BraintreeQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_AUTHORIZE,
            ApiConstants::PAYMENT_CODE_AUTHORIZE,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_REVERSAL,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_CAPTURE,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_REFUND,
            [ApiConstants::PAYMENT_CODE_REVERSAL, ApiConstants::PAYMENT_CODE_REFUND],
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $transactionCode
     * @param string|array $statusCode
     * @param string $expectedStatusReasonCode
     *
     * @return bool
     */
    private function hasTransactionLogStatus(OrderTransfer $orderTransfer, $transactionCode, $statusCode, $expectedStatusReasonCode)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $logEntity = $this
            ->queryContainer
            ->queryTransactionStatusLogBySalesOrderIdAndTransactionCodeLatestFirst(
                $idSalesOrder,
                $transactionCode
            )
            ->filterByTransactionStatus($statusCode)
            ->findOne();
        if (!$logEntity) {
            return false;
        }

        return $logEntity->getIsSuccess() === (bool)$expectedStatusReasonCode;
    }

}
