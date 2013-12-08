<?php

namespace Michael\AppBundle\Annotations;

/**
 * @Annotation
 */
class FormElement
{
	public $name;
	
	public $type;

	public $label;

	public $required;

	public $attributes;
}