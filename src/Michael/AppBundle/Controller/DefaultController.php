<?php

namespace Michael\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// required for REST
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Michael\AppBundle\Util\Paginator;

class DefaultController extends FOSRestController
{
    /**
     * @Route(
     *      "/articles",
     *      name = "app.route.default.get.all",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function allAction()
    {
        $page = (int)$this->getRequest()->query->get('page', 1);

        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('MichaelAppBundle:Article');

        $paginator = new Paginator();
        $paginator
            ->setTotalItemCount(count($repo->findAll()))
            ->setCurrentPageNumber($page);

        $articles = $repo->findBy(
            array(), 
            array(), 
            $paginator->getItemCountPerPage(), 
            $paginator->getOffset()
        );

        return array(
            'articles' => $articles,
            'paginator' => $paginator->toArray()
        );
    }

    /**
     * @Route(
     *      "/articles/{id}",
     *      name = "app.route.default.get.get",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET",
     *          "id" = "\d+"
     *      })
     * @Rest\View
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('MichaelAppBundle:Article');

        $article = $repo->find($id);

        if (!$article instanceof \Michael\AppBundle\Entity\Article) {
            throw new NotFoundHttpException('Article not found');
        }

        return array('article' => $article);
    }

    /**
     * @Route(
     *      "/articles/meta",
     *      name = "app.route.default.get.meta",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function metaAction()
    {
    	$ann = new \Michael\AppBundle\Annotations\AnnotationReader();

    	$class = 'Michael\AppBundle\Entity\Article';
		$annotations = $ann->getPropertiesAnnotations($class);

        return $annotations;
    }
}
