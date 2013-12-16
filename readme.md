# PHP Validation Library

## Key features

* set rules and fields separately
* rules can accept parameters
* variety of methods for outputting error messages
* field repopulation for fields that don't pass

## Examples:

Before any validation can take place the rules and fields need to be set. Rules can be set by the first parameter of __construct() or by the setRules() method, or individually by the setRule() method. Note: your rules and fields arrays must have the same indexes otherwise RulesFieldsMismatchException will be thrown.

Set $rules array using the constructor or setRules() methods:

	$rules = array(
		'title' => array(                   // field name
			'name' => 'title',              // field title
			'rules' => array('required')    // rules array
		),
		'email' => array(
			'name' => 'e-mail',
			'rules' => array('required', 'email')
		),
		'id' => array(
			'name' => 'identification',
			'rules' => array('required', 'between' => array(0, 10)
		)
	);

	// set the validation rules on the constructor
	$validator = new \Validator($rules);

	// or by setRules
	$validator->setRules($rules);

Set a rule individually using the setRule() method:

	$validator->setRule('email', 'e-mail', array('email', 'maxLength' => 20));

Set $fields array using setFields() method:

	$fields = array(
		'name' => 'Joe Bloggs',		// field name => field value
		'email' => 'test@test.com'
	);

	$validator->setFields($fields);

Set a field individually using the setField() method:

	$validator->setField('e-mail', 'hello@world.com');

Running the validator:

	if ( ! $validator->run())
	{
		echo 'Validation failed!';
	}
	else
	{
		echo 'Validation passed!';
	}

If there are any errors they will be avaliable by 4 methods; getAllErrors(), getAllFirstErrors(), getAllFieldErrors() and getFirstFieldError(). Each method can set the start and end delimeters for each error returned. If there are no errors an empty string is returned.

Getting the first error for the 'email' field:

	$validator->getFirstFieldError('email');

If errors were triggered you can repopulate the form values with the repopulate method:

	<input name='email' value='<?php echo $validator->repopulate('email'); ?>' />



License: [http://opensource.org/licenses/gpl-license.php](http://opensource.org/licenses/gpl-license.php) GNU Public License