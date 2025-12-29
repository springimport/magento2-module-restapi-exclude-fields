# REST API Exclude Fields for Magento 2

Adds blacklist filtering to Magento REST API via `excludeFields` parameter. Magento has native whitelist filtering (`?fields=`), this module adds blacklist filtering (`?excludeFields=`) to exclude specified fields from responses.

## Version Compatibility

| Magento Version | PHP Version | Status |
|-----------------|-------------|--------|
| **2.3.x - 2.4.x** | **7.2 - 8.3** | **✅ Compatible** |
| 2.2.x | 7.0 - 7.2 | ⚠️ Legacy (use v1.x) |

## Installation

```bash
composer require springimport/magento2-module-restapi-exclude-fields:^2.0
php bin/magento module:enable SpringImport_RestApiExcludeFields
php bin/magento setup:upgrade
```

## Usage

```
GET /rest/V1/orders/1?excludeFields=items[product_option,extension_attributes]
```
