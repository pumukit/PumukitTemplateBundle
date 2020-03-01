<?php

namespace Pumukit\TemplateBundle\Controller;

use Pumukit\TemplateBundle\Document\Template as PumukitTemplate;
use Pumukit\WebTVBundle\Services\BreadcrumbsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    private $breadcrumbsService;

    public function __construct(BreadcrumbsService $breadcrumbsService)
    {
        $this->breadcrumbsService = $breadcrumbsService;
    }

    /**
     * @Route("/t/{name}", name="pumukit_template")
     * @Template("@PumukitTemplate/List/index.html.twig")
     */
    public function indexAction(Request $request, PumukitTemplate $template): array
    {
        if ($template->isHide()) {
            throw $this->createNotFoundException('Page not found!');
        }

        $routeName = $request->get('_route');
        if ($request->get('_forwarded') && $request->get('_forwarded')->get('_route')) {
            $routeName = $request->get('_forwarded')->get('_route');
        }

        if (!$routeName) {
            $routeName = 'pumukit_webtv_index_index';
        }

        $this->breadcrumbsService->addList($template->getName(), $routeName, [], true);

        return ['template' => $template];
    }
}
