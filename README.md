Minimum Order Fee
=================

This module allows store owners to charge customers a fee for small orders (below the minimum subtotal).

Installation
------------

This module can be installed via Composer or modman.  Make sure you clear the cache after installing.

### Installation using Composer

 * Ensure [Composer](http://getcomposer.org/download/) is installed
 * Ensure your `composer.json` file references the Firegento repository:

```
  "repositories": [
    {
      "type": "composer",
      "url": "http://packages.firegento.com"
    }
  ]
```

 * Add `unleashedtech/min-order-fee-magento` to your `composer.json` file:

```
{
    "require": {
        ...
        "unleashedtech/min-order-fee-magento": "*"
    }
}
```

 * Have Composer install the module: `./composer.phar update unleashedtech/min-order-fee-magento`
 * Commit changes to `composer.json` and `composer.lock`

### Installation using modman (modman clone)

 * Ensure [modman](https://github.com/colinmollenhour/modman) is installed
 * `modman clone https://github.com/unleashedtech/min-order-fee-magento.git`

Configuration & Usage
---------------------

This functionality only works when minimum order amounts are enabled.  To charge a fee for orders below that amount, you'll need to set two new settings in the Magento backend by going to *System -> Configuration -> Sales -> Minimum Order Amount*:

 * `Allow smaller orders, but charge a fee` - Set to `Yes` to enable this module's functionality
 * `Small order fee` - The fee to charge when an order is less than the minimum

Contributing
------------

Changes to the module are welcome.  Please be sure to run `vendor/bin/phing` before committing or pushing your changes.

Changelog
---------

* **v1.2.0** - Public open-source release
* **v1.1.2** - Corrected the exclusion logic to never charge fees for orders exceeding the minimum, regardless of exclusions
* **v1.1.1** - Option to skip minimum fee if all products are marked as excluded
* **v1.1.0** - Added ability to exclude certain products from the minimum order requirement
* **v1.0.2** - Fix incorrect template path
* **v1.0.1** - Fix incorrect layout file name
* **v1.0.0** - Initial release

Copyright & License
-------------------

Copyright (c) 2014 Unleashed Technologies LLC (http://www.unleashed-technologies.com)

Licensed under the [GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)

