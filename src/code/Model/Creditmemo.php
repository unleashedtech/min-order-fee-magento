<?php
/**
 * Minimum Order Fee extension
 *
 * NOTICE OF LICENSE
 *
 * This extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this file.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category    UnleashedTech_MinOrderFee
 * @package     UnleashedTech_MinOrderFee
 * @copyright   Copyright (c) 2014 Unleashed Technologies, LLC. (http://www.unleashed-technologies.com)
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */

class UnleashedTech_MinOrderFee_Model_Creditmemo
    extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        $amountPreviouslyRefunded = 0;
        $baseAmountPreviouslyRefunded = 0;

        /** @var Mage_Sales_Model_Order_Creditmemo $previousCreditmemo */
        foreach ($order->getCreditmemosCollection() as $previousCreditmemo) {
            if ($previousCreditmemo->getMinOrderFeeAmount()
                && $previousCreditmemo->getState() !== Mage_Sales_Model_Order_Creditmemo::STATE_CANCELED
            ) {
                $amountPreviouslyRefunded += $previousCreditmemo->getMinOrderFeeAmount();
                $baseAmountPreviouslyRefunded += $previousCreditmemo->getBaseMinOrderFeeAmount();
            }
        }

        // Default to the order amount
        $minOrderFee = $order->getMinOrderFeeAmount();
        $baseMinOrderFee = $order->getBaseMinOrderFeeAmount();

        $allowedAmount = $minOrderFee - $amountPreviouslyRefunded;
        $baseAllowedAmount = $baseMinOrderFee - $baseAmountPreviouslyRefunded;

        /**
         * Check if fee amount was specified (from invoice or another source).
         * Using has magic method to allow setting 0 as fee amount.
         */
        if ($creditmemo->hasBaseMinOrderFeeAmount()) {
            $baseMinOrderFeeAmount = Mage::app()->getStore()->roundPrice(
                $creditmemo->getBaseMinOrderFeeAmount()
            );
            /*
             * Rounded allowed shipping refund amount is the highest acceptable shipping refund amount.
             * Shipping refund amount shouldn't cause errors, if it doesn't exceed that limit.
             * Note: ($x < $y + 0.0001) means ($x <= $y) for floats
             */
            if ($baseMinOrderFeeAmount < Mage::app()->getStore()->roundPrice($baseAllowedAmount) + 0.0001) {
                /*
                 * Shipping refund amount should be equated to allowed refund amount,
                 * if it exceeds that limit.
                 * Note: ($x > $y - 0.0001) means ($x >= $y) for floats
                 */
                if ($baseMinOrderFeeAmount > $baseAllowedAmount - 0.0001) {
                    $minOrderFee = $allowedAmount;
                    $baseMinOrderFee = $baseAllowedAmount;
                } else {
                    if ($baseMinOrderFee != 0) {
                        $minOrderFee = $minOrderFee * $baseMinOrderFeeAmount / $baseMinOrderFee;
                    }
                    $minOrderFee     = Mage::app()->getStore()->roundPrice($minOrderFee);
                    $baseMinOrderFee = $baseMinOrderFeeAmount;
                }
            } else {
                $baseAllowedAmount = $order->getBaseCurrency()->format($baseAllowedAmount, null, false);
                Mage::throwException(
                    Mage::helper('minorderfee')->__(
                        'Maximum fee allowed to refund is: %s',
                        $baseAllowedAmount
                    )
                );
            }
        } else {
            $minOrderFee = $allowedAmount;
            $baseMinOrderFee = $baseAllowedAmount;
        }

        $creditmemo->setMinOrderFeeAmount($minOrderFee);
        $creditmemo->setBaseMinOrderFeeAmount($baseMinOrderFee);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $minOrderFee);
        $creditmemo->setBaseGrandTotal(
            $creditmemo->getBaseGrandTotal() + $baseMinOrderFee
        );

        return $this;
    }
}
