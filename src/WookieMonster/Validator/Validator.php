<?php namespace WookieMonster\Validator;

/**
 * PHP Validation class
 * 
 * For example usage please see examples.php included
 *
 * PHP version 5.4
 *
 * @category   Validation and Security
 * @author     WookieMonster <ru.franks@gmail.com>
 * @license    GNU General Public License, version 3 (GPL-3.0)
 * @link       http://github.com/WookieMonster/Validator
 */
class Validator {
	
	protected $rules;
	protected $fields;
	protected $errors;

	/**
	 * Constructor - pass the rules array here
	 * 
	 * @param array
	 * @return null
	 */
	public function __construct($rules = array())
	{
		$this->setRules($rules);
		$this->fields = array();
		$this->errors = array();
	}

	// Default validation Rules
	// ----------------------------------------------------------------------

	/**
	 * Check if the value lies between two integers
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @param integer
	 * @return boolean
	 */
	public function ruleBetween($field, $data, $title, $start, $end)
	{
		if ( ! ($data >= $start && $data <= $end))
		{
			return $this->setError($field, "The {$title} field requires a number between {$start} and {$end}");
		}

		return TRUE;
	}

	/**
	 * Check if the field is required
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function ruleRequired($field, $data, $title)
	{
		if (trim($data) == '')
		{
			return $this->setError($field, "The {$title} field is required");
		}
		
		return TRUE;
	}

	/**
	 * Check if the field is a valid e-mail address
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function ruleEmail($field, $data, $title)
	{
		if ( ! filter_var(trim($data), FILTER_VALIDATE_EMAIL))
		{
			return $this->setError($field, "The {$title} field was not a valid e-mail");
		}

		return TRUE;
	}

	/**
	 * Check if the field is a valid URL
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function ruleUrl($field, $data, $title)
	{
		if ( ! filter_var(trim($data), FILTER_VALIDATE_URL))
		{
			return $this->setError($field, "The {$title} field was not a valid URL");
		}

		return TRUE;
	}

	/**
	 * Check if the field is a valid ip address
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function ruleIp($field, $data, $title)
	{
		if ( ! filter_var(trim($data), FILTER_VALIDATE_IP))
		{
			return $this->setError($field, "The {$title} field was not a valid ip address");
		}

		return TRUE;
	}

	/**
	 * Check if the field is not greated than a certain length
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function ruleMaxLength($field, $data, $title, $length)
	{
		if ( ! strlen($data) > $length)
		{
			return $this->setError($field, "The {$title} field was too long");
		}

		return TRUE;
	}

	/**
	 * Check if the field is not smaller than a certain length
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function ruleMinLength($field, $data, $title, $length)
	{
		if ( ! strlen($data) < $length)
		{
			return $this->setError($field, "The {$title} field was too short");
		}

		return TRUE;
	}

	/**
	 * Check if the field contains only alpha dash characters (a-zA-Z-_)
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	public function ruleAlphaDash($field, $data, $title)
	{
		$regex = preg_match("/^[a-zA-Z-_]+$/i", $data);

		if ($regex == 0 || $regex === FALSE)
		{
			return $this->setError($field, "The {$title} field must contain only alpha and dash characters");
		}

		return TRUE;
	}

	/**
	 * Check if the field contains a valid date in format Month/Day/Year
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function ruleDate($field, $data, $title)
	{
		$date = \DateTime::createFromFormat('m/d/Y', $data);
		$errors = \DateTime::getLastErrors();

		if ($errors['error_count'] > 0 || $errors['warning_count'] > 0)
		{
			return $this->setError($field, "The {$title} field must contain a valid date");
		}

		return TRUE;
	}

	// Validation Core
	// ----------------------------------------------------------------------

	/**
	 * Catch all for methods that don't exist - just throws exception
	 * 
	 * @param string
	 * @param array
	 * @return null
	 */
	public function __call($method, $args)
	{
		throw new \BadMethodCallException("Method: {$method} was not found, please implement me!");
	}

	/**
	 * Rules getter
	 * 
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * Fields getter
	 * 
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * Errors getter
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Set the rules array
	 * 
	 * @param array
	 * @return Object
	 */
	public function setRules($rules = array())
	{
		if (is_array($rules))
		{
			$this->rules = $rules;
		}

		return $this;
	}

	/**
	 * Set a single rule
	 * 
	 * @param string
	 * @param array
	 * @return Object
	 */
	public function setRule($field, $name, $rules = array())
	{
		if (is_string($field) && $field != '' && is_array($rules))
		{
			$this->rules[$field] = array('name' => $name, 'rules' => $rules);
		}

		return $this;
	}

	/**
	 * Set the fields array
	 * 
	 * @param array
	 * @return Object
	 */
	public function setFields($fields = array())
	{
		if (is_array($fields))
		{
			$this->fields = $fields;
		}

		return $this;
	}

	/**
	 * Set a single field value in the fields array
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return Object
	 */
	public function setField($field, $value = NULL)
	{
		$this->fields[$field] = $value;
		return $this;
	}

	/**
	 * Show only the first error for the field specified
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function getFirstFieldError($field, $startDelim = '<li class="error">', $endDelim = '</li>')
	{
		$message = '';

		if (isset($this->getErrors()[$field]))
		{
			if (count($this->getErrors()[$field]) > 0)
			{
				$message .= $startDelim.$this->getErrors()[$field][0].$endDelim;
			}
		}

		return $message;
	}

	/**
	 * Show all errors for the field specified
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function getAllFieldErrors($field, $startDelim = '<li class="error">', $endDelim = '</li>')
	{
		$message = '';

		if (isset($this->getErrors()[$field]))
		{
			if (count($this->getErrors()[$field]) > 0)
			{
				foreach ($this->getErrors()[$field] as $error)
				{
					$message .= $startDelim.$error.$endDelim;
				}
			}
		}

		return $message;
	}

	/**
	 * Show only the first errors for all fields
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function getAllFirstErrors($startDelim = '<li class="error">', $endDelim = '</li>')
	{
		$message = '';

		if (count($this->getErrors()) > 0)
		{
			foreach ($this->getErrors() as $field)
			{
				$message .= $startDelim.$field[0].$endDelim;
			}
		}

		return $message;
	}

	/**
	 * Show all errors for all fields
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function getAllErrors($startDelim = '<li class="error">', $endDelim = '</li>')
	{
		$message = '';

		if (count($this->getErrors()) > 0)
		{
			foreach ($this->getErrors() as $field)
			{
				foreach ($field as $error)
				{
					$message .= $startDelim.$error.$endDelim;
				}
			}
		}

		return $message;
	}

	/**
	 * Set error message for the field specified
	 * 
	 * @param string
	 * @param string
	 * @return boolean
	 */
	public function setError($field, $errorMsg)
	{
		if (is_string($field) && is_string($errorMsg))
		{
			$this->errors[$field][] = $errorMsg;
			return FALSE;
		}
	}

	/**
	 * Run validation rules - this will also populate the $errors array
	 * 
	 * @return boolean
	 */
	public function run()
	{
		foreach ($this->getRules() as $key => $value)
		{
			// check there is no mismatch between rules and fields
			if ( ! array_key_exists($key, $this->getFields()))
			{
				throw new Exception\RulesFieldsMismatchException("Mismatch between rules and fields at rule: {$key}");
			}

			$field = $this->getFields()[$key];

			// default arguments for method
			$args = array($key, $field, $value['name']);
			$this->callMethods($value['rules'], $args);
		}

		return $this->passed();
	}

	/**
	 * Call methods for current fields rules and pass default arguments
	 * 
	 * @param string
	 * @param array
	 * @return null
	 */
	protected function callMethods($methods, $args)
	{
		foreach ($methods as $key => $value)
		{
			if (is_string($key))
			{
				// key is a string so additional arguments expected in value array
				if (is_array($value))
				{
					$args = array_merge($args, $value);
					call_user_func_array(array($this, $this->ruleString($key)), $args);
				}
			}
			
			if (is_int($key))
			{
				// key is an integer so method has no additional arguments
				call_user_func_array(array($this, $this->ruleString($value)), $args);
			}
		}
	}

	/**
	 * Appends a prefix to rule string and capitalizes
	 * 
	 * @param string
	 * @return string
	 */
	protected function ruleString($str, $prefix = 'rule')
	{
		return $prefix.ucfirst($str);
	}

	/**
	 * Error count
	 * 
	 * @return integer
	 */
	public function countErrors()
	{
		return count($this->getErrors());
	}

	/**
	 * Check if any errors were tripped
	 * 
	 * @return boolean
	 */
	public function passed()
	{
		return ($this->countErrors() == 0) ? TRUE : FALSE;
	}

	// Field Repopulation
	// ----------------------------------------------------------------------

	/**
	 * Repopulate the field with given initial value
	 * 
	 * @param string
	 * @return string
	 */
	public function repopulate($field)
	{
		if ( ! array_key_exists($field, $this->getFields()))
		{
			return '';
		}

		return $this->getFields()[$field];
	}
}
