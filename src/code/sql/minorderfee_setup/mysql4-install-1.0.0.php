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

/** @var Mage_Eav_Model_Entity_Setup $this */
$this->startSetup();

$this->run("ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");
$this->run("ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");

$this->run("ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");
$this->run("ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");

$this->run("ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");
$this->run("ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");

$this->run("ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");
$this->run("ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_min_order_fee_amount` DECIMAL(12, 4) NOT NULL;");

$this->endSetup();