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

class UnleashedTech_MinOrderFee_Model_Minorderfee
    extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_code = 'min_order_fee';

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);

        // Only apply to the shipping addresses
        if ($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_BILLING) {
            return $this;
        }

        if ($this->_feeApplies($address)) {
            $previousFeeAmount = $address->getMinOrderFeeAmount();
            $fee = Mage::getStoreConfig(
                'sales/minimum_order/small_order_fee',
                $address->getQuote()->getStore()
            );
            $balance = $fee - $previousFeeAmount;
            $address->setMinOrderFeeAmount($balance);
            $address->setBaseMinOrderFeeAmount($balance);

            $address->getQuote()->setMinOrderFeeAmount($balance);
            $address->getQuote()->setBaseMinOrderFeeAmount($balance);

            //$address->setGrandTotal($address->getGrandTotal() + $address->getMinOrderFeeAmount());
            //$address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseMinPurchaseAmount());

            $this->_setAmount($balance);
            $this->_setBaseAmount($balance);
        }

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if ($amount = $address->getMinOrderFeeAmount()) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::getStoreConfig('sales/minimum_order/small_order_fee_label', $address->getQuote()->getStore()),
                'value' => $amount
            ));
        }

        return $this;
    }

    private function _feeApplies(Mage_Sales_Model_Quote_Address $address)
    {
        $storeId = $address->getQuote()->getStoreId();
        if (!Mage::getStoreConfigFlag('sales/minimum_order/active', $storeId)) {
            // Fee doesn't apply when minimum order amount is disabled
            return false;
        }

        if (!Mage::getStoreConfigFlag('sales/minimum_order/allow_with_fee', $storeId)) {
            // Fee doesn't apply unless it's allowed
            return false;
        }

        $applicableItems = $this->_getApplicableItems($address);

        $minimumAmount = (float) Mage::getStoreConfig('sales/minimum_order/amount', $storeId);

        $configuredBehavior = Mage::getStoreConfig('sales/minimum_order/small_order_fee_exclusion_behavior', $storeId);
        if ($configuredBehavior == UnleashedTech_MinOrderFee_Model_Adminhtml_System_Config_Source_Behavior::NO_FEE_IF_ALL_EXCLUDED) {
            // No fee if entire order is over $xx, regardless of exclusions
            $subtotalLessDiscount = $address->getBaseSubtotal() - abs($address->getBaseDiscountAmount());
            if ($subtotalLessDiscount >= $minimumAmount) {
                return false;
            }

            // So the order is under the minimum. But if it only contains excluded items, we don't charge a fee
            if (count($applicableItems) == 0) {
                return false;
            }

            // At this point, we know the total is under the minimum and contains at least one non-excluded item, so charge the fee!
            return true;
        }

        $currentAmount = 0;
        foreach ($applicableItems as $item) {
            $currentAmount += ($item->getBaseRowTotal() - $item->getBaseDiscountAmount());
        }

        if ($currentAmount < $minimumAmount) {
            return true;
        }

        return false;
    }

    /**
     * Returns quote items which count towards the minimum order amount
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return Mage_Sales_Model_Quote_Item[]
     */
    private function _getApplicableItems(Mage_Sales_Model_Quote_Address $address)
    {
        $ret = array();

        $items = $address->getItemsCollection();
        if ($items->count() == 0) {
            $items = $address->getAllItems();
        }

        /** @var Mage_Sales_Model_Quote_Item $item */
        foreach ($items as $item) {
            $product = $item->getProduct();
            if (!$product->hasData('exclude_from_min_order')) {
                $product = Mage::getModel('catalog/product')->load($product->getId());
            }

            if (!$product->getData('exclude_from_min_order')) {
                $ret[] = $item;
            }
        }

        return $ret;
    }
}
