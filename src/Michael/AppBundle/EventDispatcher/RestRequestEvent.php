<?php

namespace Michael\AppBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class RestRequestEvent extends Event
{
	private $entity;

	private $links

	public function __construct(Request $request, $entity, $links = array())
	{
		$this->request = $request;
		$this->entity = $entity;
		$this->links = $linksl
	}

	public function getRequest()
	{
		return $this->request;
	}

	public function getEntity()
	{
		return $this->entity;
	}

	public function getLinks()
	{
		return $this->links;
	}
}
