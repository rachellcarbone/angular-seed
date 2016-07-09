<?php namespace API;

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class JsonResponseView {

    private static $httpStatusCodes = array(
        //Informational 1xx
        100 => 'Informational: Continue',
        101 => 'Informational: Switching Protocols',
        102 => 'Informational: Processing',
        //Successful 2xx
        200 => 'Successful: OK',
        201 => 'Successful: Created',
        202 => 'Successful: Accepted',
        203 => 'Successful: Non-Authoritative Information',
        204 => 'Successful: No Content',
        205 => 'Successful: Reset Content',
        206 => 'Successful: Partial Content',
        207 => 'Successful: Multi-Status',
        208 => 'Successful: Already Reported',
        226 => 'Successful: IM Used',
        //Redirection 3xx
        300 => 'Redirection: Multiple Choices',
        301 => 'Redirection: Moved Permanently',
        302 => 'Redirection: Found',
        303 => 'Redirection: See Other',
        304 => 'Redirection: Not Modified',
        305 => 'Redirection: Use Proxy',
        306 => 'Redirection: Switch Proxy',
        307 => 'Redirection: Temporary Redirect',
        308 => 'Redirection: Permanent Redirect',
        //Client Error 4xx
        400 => 'Client Error: Bad Request',
        401 => 'Client Error: Unauthorized',
        402 => 'Client Error: Payment Required',
        403 => 'Client Error: Forbidden',
        404 => 'Client Error: Not Found',
        405 => 'Client Error: Method Not Allowed',
        406 => 'Client Error: Not Acceptable',
        407 => 'Client Error: Proxy Authentication Required',
        408 => 'Client Error: Request Timeout',
        409 => 'Client Error: Conflict',
        410 => 'Client Error: Gone',
        411 => 'Client Error: Length Required',
        412 => 'Client Error: Precondition Failed',
        413 => 'Client Error: Request Entity Too Large',
        414 => 'Client Error: Request-URI Too Long',
        415 => 'Client Error: Unsupported Media Type',
        416 => 'Client Error: Requested Range Not Satisfiable',
        417 => 'Client Error: Expectation Failed',
        418 => 'Client Error: I\'m a teapot',
        422 => 'Client Error: Unprocessable Entity',
        423 => 'Client Error: Locked',
        424 => 'Client Error: Failed Dependency',
        426 => 'Client Error: Upgrade Required',
        428 => 'Client Error: Precondition Required',
        429 => 'Client Error: Too Many Requests',
        431 => 'Client Error: Request Header Fields Too Large',
        451 => 'Client Error: Unavailable For Legal Reasons',
        //Server Error 5xx
        500 => 'Server Error: Internal Server Error',
        501 => 'Server Error: Not Implemented',
        502 => 'Server Error: Bad Gateway',
        503 => 'Server Error: Service Unavailable',
        504 => 'Server Error: Gateway Timeout',
        505 => 'Server Error: HTTP Version Not Supported',
        506 => 'Server Error: Variant Also Negotiates',
        507 => 'Server Error: Insufficient Storage',
        508 => 'Server Error: Loop Detected',
        510 => 'Server Error: Not Extended',
        511 => 'Server Error: Network Authentication Required'
    );

    /**
     * Render data formatted JSON oject.
     *
     * To use this class as a View, you can register it as a view in the
     * slim container. Then to render use $this->view->render(..) inside
     * of the route.
     *
     * $slimContainer['view'] = new \API\JsonResponseView();
     *
     * $this->view->render($response, 200, 'Request was successfull.');
     *
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param int                $status Http status code
     * @param mixed              $data
     *
     * @return ResponseInterface
     */
    public function render(\Psr\Http\Message\ResponseInterface $response, $status = false, $data = false) {
        /* Fetch return object. */
        $output = $this->fetch($response, $status, $data);

        /* Set response content type */
        $response->withJson($output, $output['meta']['status']);

        return $response;
    }
    
    /**
     * Formats data into a neatly formatted response array.
     *
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param int                $status Http status code
     * @param mixed              $data
     *
     * @return array
     */
    public function fetch(\Psr\Http\Message\ResponseInterface $response, $status = false, $data = false) {
        /* Build our consistent Return Object */
        $output = array();
        $output['data'] = (is_string($data)) ? array('msg' => $data) : $data;
        $output['meta'] = $this->getResponseMeta($status);

        return $output;
    }

    /**
     * Retrieves an array of meta data for a http status code.
     *
     * @param int   $status Http status code
     *
     * @return array
     */
    private function getResponseMeta($status = false) {
        /* Validate the parameter as an int - or make it an unknow error. */
        $status = ($status && intval($status)) ? intval($status) : 500;
        
        /* Create the meta data response object. */
        $meta = array('status' => $status, 'error' => false);

        /* Any http header code 400 or more is an error. */
        if($status >= 400) {
            $meta['error'] = true;
        }

        /* Get the header response descripton. */
        if(isset($this->httpStatusCodes[$status])) {
           $meta['desc'] = $this->httpStatusCodes[$status];
        } else {
           $meta['status'] = 500;
           $meta['desc'] = "System Error: Unknown Status Code Returned [{$status}]";
        }

        return $meta;
    }
}