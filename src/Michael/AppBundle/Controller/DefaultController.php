<?php

namespace Michael\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// required for REST
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    /**
     * @Route(
     *      "/articles",
     *      name = "app.route.default.get.index",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $art = new \Michael\AppBundle\Entity\Article();

        $art->setTitle("ciao");

        return $art;
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
