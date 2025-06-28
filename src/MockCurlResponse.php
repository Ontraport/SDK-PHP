<?php

namespace OntraportAPI;

class MockCurlResponse
{
    public string $Method;

    /** @var ICurlResponse */
    public $Response;

    /**
     * @param string $method
     * @param ICurlResponse|mixed $response
     */
    public function __construct(string $method, $response)
    {
        $this->Method = $method;
        $this->Response = $response;
    }
}
