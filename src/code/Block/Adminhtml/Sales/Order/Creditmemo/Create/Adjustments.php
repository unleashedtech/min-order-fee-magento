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

class UnleashedTech_MinOrderFee_Block_Adminhtml_Sales_Order_Creditmemo_Create_Adjustments
    extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Create_Adjustments
{
    /**
     * Initialize creditmemo adjustment totals
     *
     * @return $this
     */
    public function initTotals()
    {
        parent::initTotals();

        $parent = $this->getParentBlock();
        $parent->removeTotal('min_order_fee');

        return $this;
    }

    /**
     * Get credit memo min order fee amount
     *
     * @return float
     */
    public function getMinOrderFeeAmount()
    {
        $source = $this->getSource();
        $amount = $source->getBaseMinOrderFeeAmount();

        return Mage::app()->getStore()->roundPrice($amount) * 1;
    }

    /**
     * Get label for min order fee
     *
     * @return string
     */
    public function getMinOrderFeeLabel()
    {
        return $this->helper('minorderfee')->__(
            'Refund %s', Mage::getStoreConfig(
                'sales/minimum_order/small_order_fee_label',
                $this->getSource()->getStore()
            )
        );
    }
}
