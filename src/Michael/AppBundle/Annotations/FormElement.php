<?php

namespace Michael\AppBundle\Annotations;

/**
 *@Annotation
 */
class FormElement
{
	public $name;
	
	public $type;

	public $label;

	public $value;

	public $placeholder;

	public $options;
}