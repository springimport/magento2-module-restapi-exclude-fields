<?php

namespace SpringImport\RestApiExcludeFields\Filter;

/**
 * Class to handle partial service response
 */
class ExcludeFieldsFilter extends AbstractFieldsFilter
{
    const FILTER_PARAMETER = 'excludeFields';

    /**
     * {@inheritdoc}
     */
    protected function recursiveArrayCompare(array $array1, array $array2)
    {
        // @codingStandardsIgnoreStart
        $arrayCompare = array_diff_ukey($array1, $array2, function ($key1, $key2) use ($array1, $array2) {
            if ($key1 == $key2 && !is_array($array2[$key2]))
                return 0;
            else if ($key1 > $key2)
                return 1;
            else
                return -1;
        });

        foreach ($arrayCompare as $key => &$value) {
            if (is_array($value) && array_key_exists($key, $array2) && is_array($array2[$key])) {
                $value = $this->applyFilter($value, $array2[$key]);
            }
        }
        // @codingStandardsIgnoreEnd
        return $arrayCompare;
    }
}
