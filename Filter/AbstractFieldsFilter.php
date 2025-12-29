<?php

namespace SpringImport\RestApiExcludeFields\Filter;

use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Magento\Framework\Exception\LocalizedException;

abstract class AbstractFieldsFilter extends AbstractFilter
{
    /**
     * Initialize dependencies
     * @param RestRequest $request
     * @throws LocalizedException
     */
    public function __construct(RestRequest $request)
    {
        if (!defined('static::FILTER_PARAMETER')) {
            throw new LocalizedException(__('Constant FILTER_PARAMETER is not defined on subclass %1.', get_class($this)));
        }

        parent::__construct($request);
    }

    /**
     * @return mixed
     */
    public function getFilterParameter()
    {
        return static::FILTER_PARAMETER;
    }

    /**
     * Process filter from the request and apply over response to get the partial results
     *
     * @param array $response
     * @return array response
     */
    public function filter($response)
    {
        $filter = $this->_request->getParam(static::FILTER_PARAMETER);
        if (!is_string($filter)) {
            return $response;
        }
        $filterArray = $this->parse($filter);
        if ($filterArray === null) {
            return $response;
        }
        $partialResponse = $this->applyFilter($response, $filterArray);
        return $partialResponse;
    }

    /**
     * Parse filter string into associative array. Field names are returned as keys with values for scalar fields as 1.
     *
     * @param string $filterString
     * <pre>
     *  ex. customer[id,email],addresses[city,postcode,region[region_code,region]]
     * </pre>
     * @return array|null
     * <pre>
     *  ex.
     * array(
     *      'customer' =>
     *           array(
     *               'id' => 1,
     *               'email' => 1,
     *               ),
     *      'addresses' =>
     *           array(
     *               'city' => 1,
     *               'postcode' => 1,
     *                   'region' =>
     *                       array(
     *                           'region_code' => 1,
     *                           'region' => 1,
     *                         ),
     *               ),
     *      )
     * </pre>
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function parse($filterString)
    {
        $length = strlen($filterString);
        if ($length == 0 || preg_match('/[^\w\[\],]+/', $filterString)) {
            return null;
        }

        $start = null;
        $current = [];
        $stack = [];
        $parent = [];
        $currentElement = null;

        for ($position = 0; $position < $length; $position++) {
            //Extracting field when encountering field separators
            if (in_array($filterString[$position], ['[', ']', ','])) {
                if ($start !== null) {
                    $currentElement = substr($filterString, $start, $position - $start);
                    $current[$currentElement] = 1;
                }
                $start = null;
            }
            switch ($filterString[$position]) {
                case '[':
                    array_push($parent, $currentElement);
                    // push current field in stack and initialize current
                    array_push($stack, $current);
                    $current = [];
                    break;

                case ']':
                    //cache current
                    $temp = $current;
                    //Initialize with previous
                    $current = array_pop($stack);
                    //Add from cache
                    $current[array_pop($parent)] = $temp;
                    break;

                //Do nothing on comma. On the next iteration field will be extracted
                case ',':
                    break;

                default:
                    //Move position if no field separators found
                    if ($start === null) {
                        $start = $position;
                    }
            }
        }
        //Check for wrongly formatted filter
        if (!empty($stack)) {
            return null;
        }
        //Check if there's any field remaining that's not added to response
        if ($start !== null) {
            $currentElement = substr($filterString, $start, $position - $start);
            $current[$currentElement] = 1;
        }
        return $current;
    }

    /**
     * Apply filter array
     *
     * @param array $responseArray
     * @param array $filter
     * @return array
     */
    protected function applyFilter(array $responseArray, array $filter)
    {
        $arrayIntersect = null;
        //Check if its a sequential array. Presence of sequential arrays mean that the filed is a collection
        //and the filtering will be applied to all the collection items
        if (!(bool)count(array_filter(array_keys($responseArray), 'is_string'))) {
            foreach ($responseArray as $key => &$item) {
                $arrayIntersect[$key] = $this->recursiveArrayCompare($item, $filter);
            }
        } else {
            $arrayIntersect = $this->recursiveArrayCompare($responseArray, $filter);
        }
        return $arrayIntersect;
    }

    /**
     * Recursively compare response and filter arrays
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    abstract protected function recursiveArrayCompare(array $array1, array $array2);
}
