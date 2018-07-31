<?php

namespace OntraportAPI\Models;

interface Request
{
    /**
     * @brief Converts a response to a Request Object
     *
     * @param array $data A pre-decoded array of response data
     *
     * @return Request A new object with the data from the response
     */
    public static function CreateFromResponse(array $data);

    /**
     * @brief Converts the current Model to an array for use as request parameters.
     *
     * @return mixed[] Array of parameters to submit with an API request.
     */
    public function toRequestParams();
}