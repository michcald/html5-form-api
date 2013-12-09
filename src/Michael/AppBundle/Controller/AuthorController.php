<?php

namespace Michael\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// required for REST
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Michael\AppBundle\Controller\Controller;

use Michael\AppBundle\Util\Paginator;

use Michael\AppBundle\Entity\Author;
use Michael\AppBundle\Form\Type\AuthorType;

//use Symfony\Component\Form\FormView;

class AuthorController extends Controller
{
	protected $repository = 'MichaelAppBundle:Author';

	/**
     * @Route(
     *      "/authors",
     *      name = "app.route.author.all",
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
            ->getRepository($this->repository);

        $paginator = new Paginator();
        $paginator
            ->setTotalItemCount(parent::countAll())
            ->setCurrentPageNumber($page);

        $authors = $repo->findBy(
            array(), 
            array(), 
            $paginator->getItemCountPerPage(), 
            $paginator->getOffset()
        );

        return array(
            'authors' => $authors,
            'paginator' => $paginator->toArray()
        );
    }

    /**
     * @Route(
     *      "/authors/{id}/articles",
     *      name = "app.route.author.articles",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "GET"
     *      })
     * @Rest\View
     */
    public function articlesAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository($this->repository);

        $author = $repo->find($id);

        if (!$author instanceof Author) {
            throw new NotFoundHttpException('Author not found');
        }

        return array(
            'articles' => $author->getArticles()
        );
    }

    /**
     * @Route(
     *      "/authors/{id}",
     *      name = "app.route.author.get",
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
     *      "/authors",
     *      name = "app.route.author.create",
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
        return parent::myProcessForm(new AuthorType(), new Author(), 'app.route.author.create');
    }

    /**
     * @Route(
     *      "/authors/{id}",
     *      name = "app.route.author.update",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "PUT"
     *      })
     * @Rest\View
     */
    public function updateAction($id)
    {
        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository($this->repository);

        $author = $repo->find($id);

        if (!$author instanceof Author) {
            throw new NotFoundHttpException('Author not found');
        }
        return $this->myProcessForm(new AuthorType, $author, 'app.route.author.update');
    }

    /**
     * @Route(
     *      "/authors/{id}/articles",
     *      name = "app.route.author.link",
     *      defaults = {
     *          "_format" = "json"
     *      },
     *      requirements = {
     *          "_method" = "LINK"
     *      })
     * @Rest\View
     */
    public function linkAction($id)
    {
    	$repo = $this->getDoctrine()
            ->getManager()
            ->getRepository($this->repository);

        $author = $repo->find($id);

        if (!$author instanceof Author) {
            throw new NotFoundHttpException('Author not found');
        }

        $linksHeaders = explode(',', $this->getRequest()->headers->get('link'));

        $articles = array();

        foreach ($linksHeaders as $linkHeader) {
        	preg_match_all('/\d+$/', $linkHeader, $res);
        	$id = $res[0][0];

        	$repo2 = $this->getDoctrine()
        		->getManager()
        		->getRepository('MichaelAppBundle:Article');

        	$articles[] = $repo2->find($id);
        }

        $request = $this->getRequest();

        if (count($articles) == 0) {
            throw new HttpException(400);
        }

        foreach ($articles as $article) {
            if ($author->hasArticle($article)) {
                throw new HttpException(409, 'Author already has the article');
            }

            $article->setAuthor($author);
            //$author->addArticle($article);

            $em = $this->getDoctrine()->getManager();
        	$em->persist($article);
            
        }

        $em->flush();
    }
}