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

$this->addAttribute(
    Mage_Catalog_Model_Product::ENTITY, 'exclude_from_min_order', array(
        'group'            => 'General',
        'type'             => 'int',
        'backend'          => '',
        'frontend'         => '',
        'label'            => 'Exclude from Min. Order Fee',
        'input'            => 'select',
        'class'            => '',
        'source'           => 'eav/entity_attribute_source_boolean',
        'global'           => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'          => true,
        'required'         => false,
        'user_defined'     => false,
        'default'          => '',
        'searchable'       => false,
        'filterable'       => true,
        'comparable'       => false,
        'visible_on_front' => true,
        'unique'           => false,
        'apply_to'         => 'simple,configurable,virtual'
    )
);

$this->endSetup();