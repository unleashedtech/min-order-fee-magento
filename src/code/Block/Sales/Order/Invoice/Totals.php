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

class UnleashedTech_MinOrderFee_Block_Sales_Order_Invoice_Totals
    extends Mage_Sales_Block_Order_Invoice_Totals
{
    protected function _initTotals()
    {
        parent::_initTotals();

        $amount = $this->getSource()->getMinOrderFeeAmount();
        $baseAmount = $this->getSource()->getBaseMinOrderFeeAmount();

        if ($amount > 0) {
            $this->addTotal(new Varien_Object(array(
                'code'      => 'min_order_fee',
                'value'     => $amount,
                'base_value'=> $baseAmount,
                'label'     => Mage::getStoreConfig('sales/minimum_order/small_order_fee_label', $this->getSource()->getStore())
            )), 'tax');
        }

        return $this;
    }
}
