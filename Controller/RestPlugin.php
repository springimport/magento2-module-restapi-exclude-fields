<?php

namespace SpringImport\RestApiExcludeFields\Controller;

use SpringImport\RestApiExcludeFields\Filter\ExcludeFieldsFilter;

class RestPlugin
{
    /**
     * Filter classes list
     *
     * @param \SpringImport\RestApiFilters\Controller\Rest $subject
     * @param mixed $result
     * @return array
     */
    public function afterGetFilters(\SpringImport\RestApiFilters\Controller\Rest $subject, $result)
    {
        $result[] = ExcludeFieldsFilter::class;
        return $result;
    }
}
