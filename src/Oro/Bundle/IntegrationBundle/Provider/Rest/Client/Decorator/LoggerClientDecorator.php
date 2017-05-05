<?php

namespace Oro\Bundle\IntegrationBundle\Provider\Rest\Client\Decorator;

use Psr\Log\LoggerInterface;

use Oro\Bundle\IntegrationBundle\Provider\Rest\Client\RestClientInterface;

class LoggerClientDecorator implements RestClientInterface
{
    /**
     * @var RestClientInterface
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @inheritDoc
     */
    public function __construct(RestClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function get($resource, array $params = array(), array $headers = array(), array $options = array())
    {
        $this->logRequest('get', $resource, $params);
        return $this->client->get($resource, $params, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function getJSON($resource, array $params = array(), array $headers = array(), array $options = array())
    {
        $this->logRequest('getJSON', $resource, $params);
        return $this->client->getJSON($resource, $params, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function getXML($resource, array $params = array(), array $headers = array(), array $options = array())
    {
        $this->logRequest('getXML', $resource, $params);
        return $this->client->getXML($resource, $params, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function post($resource, $data, array $headers = array(), array $options = array())
    {
        $this->logRequest('post', $resource);
        return $this->client->post($resource, $data, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function delete($resource, array $headers = array(), array $options = array())
    {
        $this->logRequest('delete', $resource);
        return $this->client->delete($resource, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function put($resource, $data, array $headers = array(), array $options = array())
    {
        $this->logRequest('post', $resource);
        return $this->client->put($resource, $data, $headers, $options);
    }

    /**
     * @inheritDoc
     */
    public function getLastResponse()
    {
        return $this->client->getLastResponse();
    }

    protected function logRequest($httpMethod, $action, $params = [])
    {
        /**
         * @todo Filter security sensitive information from params or replaced real value on '*****'
         */
        $this->logger->debug(
            sprintf(
                '[%.1fMB/%.1fMB] Call %s action with http method %s with %s parameters',
                memory_get_usage() / 1024 / 1024,
                memory_get_peak_usage() / 1024 / 1024,
                $action,
                $httpMethod,
                json_encode($params)
            )
        );
    }
}
