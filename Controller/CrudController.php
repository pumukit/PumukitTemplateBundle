<?php

namespace Pumukit\TemplateBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Pumukit\TemplateBundle\Document\Template as PumukitTemplate;
use Pumukit\TemplateBundle\Form\TemplateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CrudController extends AbstractController
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @Route("/admin/templates/")
     * @Template("@PumukitTemplate/Crud/index.html.twig")
     */
    public function indexAction(Request $request): array
    {
        $repository = $this->documentManager->getRepository(PumukitTemplate::class);
        $templates = $repository->findAll();

        $active = null;
        if ($activeName = $request->get('active')) {
            $actives = array_filter(
                $templates,
                static function ($t) use ($activeName) {
                    return $t->getName() == $activeName;
                }
            );
            $active = current($actives);
        }

        if (!$active) {
            if (count($templates) > 0) {
                $active = $templates[0];
            } else {
                $active = null;
            }
        }

        $deleteForm = null;
        $editForm = null;
        if ($active) {
            $deleteForm = $this->createDeleteForm($active);
            $editForm = $this->createForm(TemplateType::class, $active);

            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->documentManager->persist($active);
                $this->documentManager->flush($active);
            }
        }

        return [
            'templates' => $templates,
            'active' => $active,
            'delete_form' => $deleteForm ? $deleteForm->createView() : null,
            'edit_form' => $editForm ? $editForm->createView() : null,
        ];
    }

    /**
     * @Route("/admin/templates/create")
     */
    public function createAction(): RedirectResponse
    {
        $t = new PumukitTemplate();
        $t->setName(time());

        $this->documentManager->persist($t);
        $this->documentManager->flush();

        return $this->redirect($this->generateUrl('pumukit_template_crud_index'));
    }

    /**
     * @Route("/admin/templates/delete/{id}")
     */
    public function deleteAction(PumukitTemplate $t): RedirectResponse
    {
        $this->documentManager->remove($t);
        $this->documentManager->flush();

        return $this->redirect($this->generateUrl('pumukit_template_crud_index'));
    }

    private function createDeleteForm(PumukitTemplate $a): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pumukit_template_crud_delete', ['id' => $a->getId()]))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
