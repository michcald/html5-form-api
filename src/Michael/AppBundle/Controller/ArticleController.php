<?php

namespace Michael\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// required for REST
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

// for documentation
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Michael\AppBundle\Util\Paginator;

use Michael\AppBundle\Entity\Article;
use Michael\AppBundle\Form\Type\ArticleType;

//use Symfony\Component\Form\FormView;

class ArticleController extends FOSRestController
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

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('MichaelAppBundle:Article');

        $query = $em->createQueryBuilder();
        $query->select('count(t.id)');
        $query->from('MichaelAppBundle:Article', 't');
        $count = $query->getQuery()->getSingleScalarResult();

        $paginator = new Paginator();
        $paginator
            ->setTotalItemCount($count)
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

        $entity = $repo->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Article not found');
        }

        return array('article' => $entity);
    }

    /**
     * @Route(
     *      "/articles",
     *      name = "app.route.default.get.new",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "POST"
     *      })
     * @Rest\View
     */
    public function createAction()
    {
        $entity = new Article();

        $form = $this->createForm(new ArticleType(), $entity);

        $form->submit($this->getRequest(), $clearMissing = true);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            
            $em->flush();

            $response = new Response();
            $response->setStatusCode(201);

            // set the `Location` header only when creating new resources
            $response->headers->set(
                'Location',
                $this->generateUrl(
                    'app.route.default.get.get', 
                    array('id' => $entity->getId()),
                    true // absolute
                )
            );

            $response->setContent($entity->getId());

            return $response;
        }

        return $this->view($form, 400);
    }

    /**
     * @Route(
     *      "/articles/{id}",
     *      name = "app.route.default.put.edit",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "PUT"
     *      })
     * @Rest\View
     */
    public function editAction($id)
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('MichaelAppBundle:Article');

        $entity = $repo->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Article not found');
        }

        $form = $this->createForm(new AuthorType(), $entity);

        $form->submit($this->getRequest(), $clearMissing = false);

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $response = new Response();
            $response->setStatusCode(204);

            return $response;
        }

        return $this->view($form, 400);
    }

    /**
     * @Route(
     *      "/articles/{id}",
     *      name = "app.route.default.delete.delete",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "DELETE"
     *      })
     * @Rest\View(statusCode=204)
     */
    public function deleteAction($id)
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('MichaelAppBundle:Article');

        $entity = $repo->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Article not found');
        }

        $em->remove($entity);
        $em->flush();
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

    /**
     * @Route(
     *      "/articles/{id}",
     *      name = "app.route.article.options",
     *      defaults = {
     *          "_format" = "json",
     *          "id" = ""
     *      },
     *      requirements = {
     *          "_method" = "OPTIONS",
     *          "id" = "\d+"
     *      })
     * @Rest\View(statusCode=200)
     */
    public function optionsAction($id)
    {
        
    }
}
