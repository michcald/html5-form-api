<?php

namespace Michael\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// required for REST
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Michael\AppBundle\Controller\Controller;

use Michael\AppBundle\Util\Paginator;

use Michael\AppBundle\Entity\Article;
use Michael\AppBundle\Form\Type\ArticleType;

//use Symfony\Component\Form\FormView;

class ArticleController extends Controller
{
    protected $repository = 'MichaelAppBundle:Article';

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
            ->setTotalItemCount(parent::countAll())
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
        return parent::read($id);
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
    public function newAction()
    {
        return parent::myProcessForm(new ArticleType, new Article, 'app.route.default.get.get');
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

        $article = $repo->find($id);

        if (!$article instanceof Article) {
            throw new NotFoundHttpException('Article not found');
        }
        return $this->myProcessForm(new ArticleType, $article, 'app.route.default.get.get');
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
        return parent::delete($id);
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

    private function processForm(Article $article)
    {
        $statusCode = !$article->getId() ? 201 : 204;

        $form = $this->createForm(new ArticleType(), $article);

        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if (!$article->getId()) {
                $em->persist($article);
            }
            
            $em->flush();

            $response = new Response();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl(
                        'app.route.default.get.get', array('id' => $article->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        return $this->view($form, 400);
    }
}
