<?php
namespace WeAreNotMachines\Ratable;

use \ReflectionClass;
use \ReflectionProperty;

class Dimension {

	use \WeAreNotMachines\Ratable\Traits\TransformsValues;
	
	private $id;
	private $label;
	private $description;
	private $minimum;
	private $maximum;
	private $increment;
	private $active;

	public function __construct(array $attributes = null, Callable $transformer = null, array $applyTransformerTo = array()) {
		$this->init($attributes);
		$this->setTransformer($transformer, $applyTransformerTo);

	}

	private function mapProperties() {
		$r = new ReflectionClass($this);

		$props = array_map(
			function($item) {
				return $item->getName();
			},
			$r->getProperties(ReflectionProperty::IS_PRIVATE)
		);

		return $props;
	}

	private function init(array $attributes = null) {

		if (empty($attributes)) return;
		
		$props = $this->mapProperties();

		$fProps = array_filter($attributes, function($key) use ($props) {
			return in_array($key, $props);
		}, ARRAY_FILTER_USE_KEY);

		foreach ($fProps AS $key=>$value) {
			$this->$key = $value;
		}
	}

	/**
	 * Magic accessor for all properties;
	 * @param  string $prop
	 * @return mixed 
	 */
	public function __get($prop) {
		return in_array($prop, $this->applyTransformer) ? $this->transform($this->$prop, $prop) : $this->$prop;
	}

	public function __set($key, $value) {
		$props = $this->mapProperties();
		if (!in_array($key, $props)) throw new \RuntimeException("There is no property $key in WeAreNotMachines\\Ratable\\Dimension");
		$this->$key = $value;
	}

	public function getMinimum($transform = true) {
		return $transform ? $this->transform($this->minimum, "minimum") : $this->minimum;
	}

	public function getMaximum($transform=true) {
		return $transform ? $this->transform($this->maximum, "maximum") : $this->maximum;
	}

	public function getIncrement($transform = true) {
		return $transform ? $this->transform($this->increment, "increment") : $this->increment;
	}

	public function json() {
		return json_encode([
			"id" => $this->id,
			"label" => $this->label,
			"decription" => $this->description,
			"minimum" => $this->getMinimum(),
			"maximum" => $this->getMaximum(),
			"increment" => $this->getIncrement()
		]);
	}

}