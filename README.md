# Market_ShippingSurcharge

Magento 2 module that adds a per-product `shipping_surcharge` attribute, allowing store administrators to define an additional shipping charge on top of the standard carrier rate for individual products.

## Overview

The `shipping_surcharge` attribute is a decimal price field scoped at the **website level**, meaning the surcharge can differ per website. It is intended to be read by shipping logic (e.g. a plugin or observer on the quote/order) to apply the extra cost at checkout.

## Installation

```bash
bin/magento module:enable Market_ShippingSurcharge
bin/magento setup:upgrade
bin/magento cache:flush
```

## Attribute Details

| Property | Value |
|---|---|
| Attribute code | `shipping_surcharge` |
| Type | `decimal` |
| Input | `price` |
| Scope | Website |
| Group | General |
| Required | No |
| Used in product listing | Yes |
| Visible on frontend | No |

## Reverting

The data patch implements `PatchRevertableInterface`. To remove the attribute:

```bash
bin/magento setup:db-declaration:generate-patch Market_ShippingSurcharge revert
```

Or programmatically via `bin/magento setup:rollback`.

## Dependencies

- `Magento_Catalog`
- `Magento_Sales`
- `Magento_Eav`
