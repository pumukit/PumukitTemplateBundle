<?php

namespace Pumukit\TemplateBundle\EventListener;

use Doctrine\ODM\MongoDB\DocumentManager;
use Pumukit\SchemaBundle\EventListener\LocaleListener;
use Pumukit\TemplateBundle\Document\Template;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RouteFoundHttpListener
{
    private $dm;
    private $httpKernel;
    private $requestStack;
    private $requestFixer;

    public function __construct(
        DocumentManager $dm,
        HttpKernelInterface $httpKernel,
        RequestStack $requestStack,
        LocaleListener $requestFixer
    ) {
        $this->dm = $dm;
        $this->httpKernel = $httpKernel;
        $this->requestStack = $requestStack;
        $this->requestFixer = $requestFixer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $repo = $this->dm->getRepository(Template::class);
        $exception = $event->getThrowable();

        if ($exception instanceof \Exception) {
            $request = $event->getRequest();
            $this->requestFixer->fixRequestLocale($request);

            $pathInfo = $request->getPathInfo();
            $name = substr($pathInfo, 1);

            $t = $repo->findOneBy(['name' => $name, 'hide' => false]);
            if ($t) {
                $response = $this->forward('PumukitTemplateBundle:List:index', ['name' => $name]);
                $response->headers->set('X-Status-Code', 200);
                $event->setResponse($response);
            }
        }
    }

    private function forward($controller, array $path = [], array $query = []): Response
    {
        $path['_controller'] = $controller;
        $subRequest = $this->requestStack->getCurrentRequest()->duplicate($query, null, $path);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
