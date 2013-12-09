<?php

namespace Michael\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Controller extends FOSRestController
{
	/**
	 * This class can be overridden for changing the source and the way
	 * for retrieving the data
	 */
	protected function findOne($id)
	{
		
	}

	protected function countAll()
	{
		$em = $this->getDoctrine()->getManager();

		$repo = $em->getRepository($this->repository);

        $qb = $em->createQueryBuilder();
    	$qb->select('count(t.id)');
    	$qb->from($this->repository, 't');

    	return $qb->getQuery()->getSingleScalarResult();
	}

	protected function findPage($paginator)
	{

	}


	protected final function read($id)
	{
		$em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository($this->repository);

        $entity = $repo->find($id);

        $className = $repo->getClassName();

        if (!$entity instanceof $className) {
            throw new NotFoundHttpException('Resource not found');
        }

        return array('resource' => $entity);
	}



	// rename in create and create a new one called update
	protected final function myProcessForm($form, $entity, $pathName)
    {
        $statusCode = $entity->getId() ? 204 : 201;

        $form = $this->createForm($form, $entity);

        $clearMissing = !$entity->getId() ? true : false;

		$form->submit($this->getRequest(), $clearMissing);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if (!$entity->getId()) {
                $em->persist($entity);
            }
            
            $em->flush();

            $response = new Response();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location',
                    $this->generateUrl(
                        $pathName, array('id' => $entity->getId()),
                        true // absolute
                    )
                );

                $response->setContent($entity->getId());
            }

            return $response;
        }

        return $this->view($form, 400);
    }

    protected final function delete($id)
    {
    	$em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository($this->repository);

        $entity = $repo->find($id);

        $className = $repo->getClassName();

        if (!$entity instanceof $className) {
            throw new NotFoundHttpException('Resource not found');
        }

        $em->remove($entity);
        $em->flush();
    }
}