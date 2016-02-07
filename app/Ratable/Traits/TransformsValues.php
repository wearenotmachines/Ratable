<?php

namespace WeAreNotMachines\Ratable\Traits;

trait TransformsValues {
	
	private $transformer;
	private $transformableAttributes = array();

	public function setTransformer(Callable $transformerFunction = null, array $applicableTo = array()) {
		$this->transformer = empty($transformerFunction) ? 
				function($value) {
					return $value;
				}
			:
				$transformerFunction;
		$this->transformableAttributes = $applicableTo;
	}

	public function getTransformer() {
		return $this->transformer;
	}

	public function transform($value, $property=null) {
		if (!empty($property) && !empty($this->transformableAttributes) && !in_array($property, $this->transformableAttributes)) return $value;
		return call_user_func($this->transformer, $value);
	}

	public function setTransformableAttributes(array $attributes) {
		$this->transformableAttributes = $attributes;
	}

	public function getTransformableAttributes() {
		return $this->transformableAttributes;
	}

}