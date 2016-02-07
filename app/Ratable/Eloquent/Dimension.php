<?php
namespace WeAreNotMachines\Ratable\Eloquent;

use Illuminate\Database\Eloquent\Model;


class Dimension extends Model {
	
	use \WeAreNotMachines\Ratable\Traits\TransformsValues;

	private $dimensionObject;
	protected $guarded = ["id"];


	public function __construct(array $attributes = array(), Callable $transformerFunction = null, array $transformableAttributes = null) {
		$this->transformer = $this->setTransformer($transformerFunction);
		$this->transformableAttributes = $transformableAttributes;
		parent::__construct($attributes);
	}

	public function getMinimumAttribute($value) {
		return $this->transform($this->attributes['minimum'], "minimum");
	}

	public function getMaximumAttribute($value) {
		return $this->transform($this->attributes['maximum'], "maximum");
	}

	public function getIncrementAttribute($value) {
		return $this->transform($this->attributes['increment'], "increment");
	}

	public function fill(array $attributes = array()) {
		parent::fill($attributes);
		$this->setTransformer(null);
		return $this;
	}





}