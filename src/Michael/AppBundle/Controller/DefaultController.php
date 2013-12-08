<?php

namespace Michael\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    public function ciao()
    {
    	$ann = new \Michael\MetaFormBundle\Annotations\AnnotationReader();

    	$class = 'Michael\TestBundle\Entity\Article';
    	$property = 'title';
		$annotations = $ann->getPropertiesAnnotations($class);

		echo '<pre>'.print_r($annotations, true).'</pre>';

    	
    	die();
        return array('name' => $name);
    }
}
