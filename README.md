# Exclude fields in Rest API
Adds parameter to exclude fields in requests. Similar as [include fields](http://devdocs.magento.com/guides/v2.1/howdoi/webapi/filter-response.html#using-with-searchcriteria) by Magento 2.

Enable module:
```
php -f bin/magento module:enable SpringImport_RestApiExcludeFields
```

Disable module:
```
php -f bin/magento module:disable SpringImport_RestApiExcludeFields
```

Update system:
```
php -f bin/magento setup:upgrade
```
