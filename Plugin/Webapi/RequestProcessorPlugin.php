<?php

namespace SpringImport\RestApiExcludeFields\Plugin\Webapi;

use Magento\Framework\Webapi\Rest\Response as RestResponse;
use SpringImport\RestApiExcludeFields\Filter\ExcludeFieldsFilter;

/**
 * Plugin to apply excludeFields filtering to REST API responses
 * Compatible with Magento 2.3.x and 2.4.x
 */
class RequestProcessorPlugin
{
    /**
     * @var ExcludeFieldsFilter
     */
    protected $excludeFieldsFilter;

    /**
     * @var RestResponse
     */
    protected $response;

    /**
     * @param ExcludeFieldsFilter $excludeFieldsFilter
     * @param RestResponse $response
     */
    public function __construct(
        ExcludeFieldsFilter $excludeFieldsFilter,
        RestResponse $response
    ) {
        $this->excludeFieldsFilter = $excludeFieldsFilter;
        $this->response = $response;
    }

    /**
     * Apply excludeFields filtering after request processing
     *
     * @param \Magento\Webapi\Controller\Rest\RequestProcessor\Sync $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterProcess($subject, $result)
    {
        // Get the response body data
        $outputData = $this->response->getBody();

        if (is_array($outputData) && !empty($outputData)) {
            // Apply excludeFields filter if parameter is present
            $filteredData = $this->excludeFieldsFilter->filter($outputData);

            // Update response with filtered data
            if ($filteredData !== $outputData) {
                $this->response->setBody($filteredData);
            }
        }

        return $result;
    }
}
