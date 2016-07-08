<?php namespace API;

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class RouteAuthenticationMiddleware {
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
    public function __invoke($request, $response, $next)
    {
        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');

        return $response;
    }
}