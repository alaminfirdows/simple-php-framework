<?php

namespace Framework;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Core implements HttpKernelInterface
{
    /** @var RouteCollection */
    protected $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * Handle app request
     *
     * @param Request $request
     * @param $type
     * @param boolean $catch
     * @return Response
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true): Response
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $attributes = $matcher->match($request->getPathInfo());

            $response = call_user_func_array($attributes['controller'], array_slice($attributes, 1));
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    /**
     * Register GET route
     *
     * @param string $path
     * @param callable $controller
     * @return void
     */
    public function get(string $path, callable $controller): void
    {
        $this->routes->add($path, new Route(
            $path,
            array(
                'controller' => $controller
            )
        ));
    }
}
