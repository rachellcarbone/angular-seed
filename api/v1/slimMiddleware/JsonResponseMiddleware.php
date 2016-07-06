<?php namespace API;

class JsonResponseMiddleware {
    /**
     * Slim PHP Middleware to turn API Responses into a clean formatted JSON oject.
     *
     * To use this class as a middleware, you can use ->add( new ExampleMiddleware() ); 
     * function chain after the $app, Route, or group(), which in the code below, 
     * any one of these, could represent $subject.
     * 
     * $subject->add( new ExampleMiddleware() );
     *
     * http://www.slimframework.com/docs/concepts/middleware.html
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {
        $response->getBody()->write('WHAT');
        ob_start();

        /* Let the other middleware (and routing) go first */
        $response = $next($request, $response);

        /* Get current buffer contents and delete current output buffer */
        // http://php.net/manual/en/function.ob-get-clean.php
        try {
            $body = ob_get_clean();
            ob_clean();
            ob_end_clean();
        } catch (Exception $e) {
            ob_end_clean();
            $body = 'meow';
            throw $e;
        }
        
        /* Build our consistent Return Object */
        if($body) {
            $output = array(
                'data' => $body, 
                'meta' => array('status' => 200, 'error' => false)
            );
        } else {
            $output = array(
                'data' => array('msg' => 'An unknown server error has occured.'), 
                'meta' => array('status' => 500, 'error' => false)
            );
        }

        /* Set response content type */
        //$response = $response->withHeader('Content-Type', 'application/json');

        return $response->withJson($output, 200);
    }
}