<?php

namespace Michael\AppBundle\Util;

class Paginator
{
	private $currentPage = 0;

	private $totalItemCount = 0;

	private $itemCountPerPage = 20;

	public function __construct()
	{

	}

	public function setTotalItemCount($count)
	{
		$this->totalItemCount = (int)$count;

		return $this;
	}

	public function getTotalItemCount()
	{
		return $this->totalItemCount;
	}

	public function setCurrentPageNumber($page)
	{
		$this->currentPage = (int)$page;

		return $this;
	}

	public function getCurrentPageNumber()
	{
		return $this->currentPage;
	}

	public function getTotalPageNumber()
	{
		return ceil($this->getTotalItemCount() / $this->getItemCountPerPage());
	}

	public function getPrevPageNumber()
	{
		if ($this->getTotalPageNumber() <= 1) {
			return null;
		}

		if ($this->getCurrentPageNumber() == 1) {
			return null;
		}

		return $this->getCurrentPageNumber() - 1;
	}

	public function getNextPageNumber()
	{
		if ($this->getCurrentPageNumber() == $this->getTotalPageNumber()) {
			return null;
		}

		return $this->getCurrentPageNumber() + 1;
	}

	public function setItemCountPerPage($count)
	{
		$this->itemCountPerPage = (int)$count;

		return $this;
	}

	public function getItemCountPerPage()
	{
		return $this->itemCountPerPage;
	}

	public function getOffset()
	{
		return ($this->getCurrentPageNumber() - 1) * $this->getItemCountPerPage();
	}

	public function toArray()
	{
		return array(
			'item_count'      => $this->getTotalItemCount(),
			'page_item_count' => $this->getItemCountPerPage(),
			'page_count'      => $this->getTotalPageNumber(),
			'page'            => array(
				'current' => $this->getCurrentPageNumber(),
				'prev'    => $this->getPrevPageNumber(),
				'next'    => $this->getNextPageNumber()
			)
		);
	}
}
