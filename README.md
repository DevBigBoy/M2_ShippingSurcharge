<div align="center">

# Market_ShippingSurcharge

</div>

<div align="center">

[![Packagist Version](https://img.shields.io/packagist/v/market/module-shipping-surcharge?logo=packagist&sort=semver&label=packagist&style=for-the-badge)](https://packagist.org/packages/market/module-shipping-surcharge)
[![Packagist Downloads](https://img.shields.io/packagist/dt/market/module-shipping-surcharge?logo=composer&style=for-the-badge)](https://packagist.org/packages/market/module-shipping-surcharge/stats)
![Supported Magento Versions](https://img.shields.io/badge/magento-%202.4-brightgreen.svg?logo=magento&longCache=true&style=for-the-badge)
![License](https://img.shields.io/badge/license-MIT-green?color=%23234&style=for-the-badge)

</div>

Magento 2 module that adds a per-product `shipping_surcharge` attribute and carries the surcharge amount through the full quote, order, invoice, and credit memo lifecycle. It calculates surcharges per item and quote, displays them on the product page, cart, and checkout summary, and persists them on order placement via an observer.

## Screenshots

### Admin - Product Edit

![Admin product edit showing the Shipping Surcharge field](images/admin-product.png)

The `Shipping Surcharge` price field appears in the **General** attribute group, scoped at the website level, alongside Price and Tax Class.

### Frontend - Product Detail Page

![Frontend product page showing the Additional Shipping Charge notice](images/catalog-product-view.png)

When a surcharge is set, an **Additional Shipping Charge** notice is rendered below the Add to Cart button.

## Overview

The `shipping_surcharge` attribute is a decimal price field scoped at the **website level**, meaning the surcharge can differ per website. The surcharge is multiplied by item quantity and accumulated across all cart items to produce a quote-level total.

The module also:

- Calculates surcharge per item (`SurchargeCalculator`) and exposes an interface for custom rate calculation (`RateCalculatorInterface`).
- Collects the surcharge as a native quote total (`Model\Quote\Address\Total\Surcharge`) so it appears in the totals block.
- Persists surcharge values on quote items and the order via the `QuoteSubmit` observer on `sales_model_service_quote_submit_success`.
- Displays surcharge on the **product detail page**, **cart item rows**, and **checkout order summary** (Knockout component).
- Creates a CMS static block (`surcharge_explanatory_note`) for editable explanatory text alongside the surcharge amount.

## Architecture

```text
Product attribute (shipping_surcharge)
    │
    ├── Model\Quote\Address\Total\Surcharge   ← quote total collector (collect/fetch)
    │       └── SurchargeCalculator           ← surcharge × qty per item
    │
    ├── Observer\QuoteSubmit                  ← persists totals on order placement
    │       └── SurchargeCalculator
    │
    └── Frontend
            ├── Block\Product\Catalog         ← product detail page
            ├── Block\Product\Cart\Item       ← cart item rows
            └── JS: view/summary/surcharge    ← checkout sidebar total
```

## Installation

### Manual

1. Copy the module to `app/code/Market/ShippingSurcharge`
2. Run the following commands:

```bash
bin/magento module:enable Market_ShippingSurcharge
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:flush
```

### Via Composer

```bash
composer require market/module-shipping-surcharge
bin/magento module:enable Market_ShippingSurcharge
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:flush
```

## Configuration

### Stores > Configuration > Market > Shipping Surcharge

| Field                     | Description                                      |
|---------------------------|--------------------------------------------------|
| Enable Shipping Surcharge | Enable or disable the surcharge feature globally |

The surcharge total sort order can be configured under:

**Stores > Configuration > Sales > Sales > Checkout Totals Sort Order > Shipping Surcharge** (default: `35`)

## Attribute Details

| Property                | Value                |
|-------------------------|----------------------|
| Attribute code          | `shipping_surcharge` |
| Type                    | `decimal`            |
| Input                   | `price`              |
| Scope                   | Website              |
| Group                   | General              |
| Required                | No                   |
| Used in product listing | Yes                  |
| Visible on frontend     | No                   |

## Surcharge Calculation

`SurchargeCalculator` reads `shipping_surcharge` from the product and multiplies it by the item quantity (`qty` or `qty_ordered`). The total across all items becomes the quote-level surcharge.

```text
item_surcharge = product.shipping_surcharge × item.qty
quote_surcharge = Σ item_surcharge
```

The `RateCalculatorInterface` is available for implementing custom surcharge adjustments to shipping rate results.

## Frontend Display

| Location            | Implementation                                                    |
|---------------------|-------------------------------------------------------------------|
| Product detail page | `Block\Product\Catalog` via `catalog_product_view.xml`            |
| Cart item rows      | `Block\Product\Cart\Item` via `checkout_cart_index.xml`           |
| Checkout summary    | Knockout component `surcharge.js` via `checkout_index_index.xml`  |

### Explanatory Note CMS Block

A CMS static block with identifier `surcharge_explanatory_note` is created automatically on `setup:upgrade`. Its default content is:

> An additional shipping charge is required due to the product's size or weight, or because it requires additional packaging.

You can edit this text under **Content > Blocks > Surcharge Note** without redeploying code.

## Database Changes

Adds `shipping_surcharge` columns to the following tables:

| Table                   | Columns                                                                                                            |
|-------------------------|--------------------------------------------------------------------------------------------------------------------|
| `quote`                 | `shipping_surcharge`                                                                                               |
| `quote_item`            | `shipping_surcharge`                                                                                               |
| `sales_order`           | `shipping_surcharge`, `base_shipping_surcharge`, `shipping_surcharge_refunded`, `base_shipping_surcharge_refunded` |
| `sales_order_item`      | `shipping_surcharge`, `base_shipping_surcharge`                                                                    |
| `sales_invoice`         | `shipping_surcharge`, `base_shipping_surcharge`                                                                    |
| `sales_invoice_item`    | `shipping_surcharge`, `base_shipping_surcharge`                                                                    |
| `sales_creditmemo`      | `shipping_surcharge`, `base_shipping_surcharge`                                                                    |
| `sales_creditmemo_item` | `shipping_surcharge`, `base_shipping_surcharge`                                                                    |

## Uninstalling

```bash
bin/magento module:uninstall Market_ShippingSurcharge
bin/magento setup:upgrade
bin/magento cache:flush
```

This will trigger the `revert()` method on the data patch, removing the `shipping_surcharge` product attribute. Database columns added via `db_schema.xml` will also be removed automatically.

## Dependencies

- `Magento_Catalog`
- `Magento_Sales`
- `Magento_Eav`
- `Magento_Cms`
- `Magento_Checkout`
