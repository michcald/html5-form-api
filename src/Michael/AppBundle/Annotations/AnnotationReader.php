<?php

namespace Michael\AppBundle\Annotations;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;

class AnnotationReader
{
	/**
	 * Doctrine\Common\Annotations\AnnotationReader
	 */
	private $annotationReader;

	public function __construct()
	{
		$this->annotationReader = new DoctrineAnnotationReader();
	}

	public function getClassAnnotations($class)
	{
		$reflectionClass = new \ReflectionClass($class);
		return $this->annotationReader->getClassAnnotations($reflectionClass);
	}

	public function getObjectAnnotations($object)
	{
		$reflectionObject = new \ReflectionObject($object);
		return $this->annotationReader->getClassAnnotations($reflectionObject);
	}

	public function getPropertiesAnnotations($class)
	{
		$reflectionClass = new \ReflectionClass($class);
		$properties = $reflectionClass->getProperties();
		
		$results = array();
		foreach ($properties as $property) {
			$temp = $this->annotationReader->getPropertyAnnotations($property);

			foreach ($temp as $t) {
				if ($t instanceof \Michael\AppBundle\Annotations\FormElement) {
					$t->name = $property->getName();
					$results[] = $t;
				}
			}
		}

		return $results;
	}

	public function getPropertyAnnotations($class, $property)
	{
		$reflectionProperty = new \ReflectionProperty($class, $property);
		return $this->annotationReader->getPropertyAnnotations($reflectionProperty);
	}

	public function getMethodAnnotations($class, $method)
	{
		$reflectionMethod = new \ReflectionMethod($class, $method);
		return $this->annotationReader->getMethodAnnotations($reflectionMethod);
	}
}
