<?php
class Form {
	var $values = array();  //Holds submitted form field values
	var $errors = array();  //Holds submitted form error messages
	var $num_errors;        //The number of errors in submitted form

	function __construct(){
		if(isset($_SESSION['value_array']) && isset($_SESSION['error_array'])) {
			$this->values = $_SESSION['value_array'];
			$this->errors = $_SESSION['error_array'];

			$this->num_errors = count($this->errors);

			unset($_SESSION['value_array']);
			unset($_SESSION['error_array']);
		} else{
			$this->num_errors = 0;
		}
	}

	function error_type($error){
		return isset($this->errors[$error]);
	}

	function setError($error_type, $errmsg){
		$this->errors[$error_type] = $errmsg;
		$this->num_errors = count($this->errors);
	}

	function value($field){
		if(array_key_exists($field,$this->values)){
			return htmlspecialchars(stripslashes($this->values[$field]), ENT_QUOTES);
		}
	}

	function error($error_type){
		if(array_key_exists($error_type,$this->errors)){
			return $this->errors[$error_type];
		}
	}

	function getErrorArray(){
		return $this->errors;
	}
}
 
?>
