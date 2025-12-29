<?php

namespace SpringImport\RestApiExcludeFields\Filter;

use Magento\Framework\Webapi\Rest\Request as RestRequest;

abstract class AbstractFilter
{
    /**
     * @var RestRequest
     */
    protected $_request;

    /**
     * Initialize dependencies
     *
     * @param RestRequest $request
     */
    public function __construct(RestRequest $request)
    {
        $this->_request = $request;
    }

    /**
     * Process filter from the request and apply over response to get the partial results
     *
     * @param array $response
     * @return array partial response array or empty array if invalid filter criteria is provided
     */
    abstract public function filter($response);
}
