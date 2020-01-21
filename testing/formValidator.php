<?php 

class BaseValidator {
	
	protected $objToValidate;
	private $validationErrors;
	private $defaultErrorMsg;
	
	// types of predefined validators:
	const REQUIRED = 1;
	const UNIQUE = 2;
	const INT = 3;
	const EMAIL = 4;
	const NUMBER = 5;
	const POSITIVE_NUMBER = 6;
	
	public function __construct() {
		$this->defaultErrorMsg = array(
			self::REQUIRED => "This field must be filled in",
			self::UNIQUE => "This value is already in use",
			self::INT => "Positive integer value is required",
			self::EMAIL => "Invalid e-mail address format",
			self::NUMBER => "Number is required",
			self::POSITIVE_NUMBER => "Positive number is required",
		);
	}
	
	/**
	 * Validate single field value with one of the predefined validators.
	 * If not validated then add error to $this->validationErrors
	 *
	 * @param $fieldName string
	 * @param $type int One of the constants for predefined validator
	 * @param $message string Error message to be displayed or NULL for default message
	 * @param $params array Additional params for validators that requre them, e.g.
	 * min and max values for a range validator, etc.
	 * @return bool Whether value has been validated correctly
	*/
	public function validateField($fieldName, $type, $message = null, $params = null) {
		// here I use a switch statement on $type and perform all the necessary
		// validation checks using regular expressions and other methods
		// ...
	}
	
	/**
	 * Add error to this object (to $this->validationErrors).
	 * Used for manually validating what can't be validated
	 * using the predefined validators.
	 *
	 * @param $fieldName string
	 * @param $message string Error message to be displayed
	*/
	public function addError($fieldName, $message) {
		// ...
	}

	/**
	 * Get validation errors for all fields that didn't pass validation.
	 * The result is an array where keys are field names and values are
	 * validation messages.
	 *
	 * @return array|NULL
	*/
	public function getValidationErrors() {
		return $this->validationErrors;
	}
}


class Validator extends BaseValidator {
	public function validateProductInAdmin(Product $product) {
		$this->objToValidate = $product;
		
		$this->validateField('name', self::REQUIRED);
		$this->validateField('name', self::UNIQUE, "Product name must be unique");
		$this->validateField('price', self::REQUIRED);
		$this->validateField('price', self::POSITIVE_NUMBER);
		
		// custom validator
		if ($product->discounted_price > $product->price) {
			$this->addError('discounted_price', "Discounted price cannot be higher than regular price");
		}
	}
	
	public function validatePage(Page $page) {
		$this->objToValidate = $product;
		
		$this->validateField('title', self::REQUIRED, "Page title must be provided");
		$this->validateField('content', self::REQUIRED);
	}
}


// Let's assume $product is an object with values from form
$validator = new Validator;
$validator->validateProductInAdmin($product);
$errors = $validator->getValidationErrors();

if ($errors) {
	// display the form again and pass $errors to the template
	// so they can be displayed to the user next to appropriate fields
	// ...
}

// validation passed
// ...