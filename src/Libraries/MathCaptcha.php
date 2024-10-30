<?php

namespace InboundBrew\Libraries;


/**
 * Class MathCaptcha
 * @package InboundBrew\Modules\Core
 */
class MathCaptcha {

    /**
     * @var int
     */
    private $min;
    /**
     * @var int
     */
    private $max;
    /**
     * @var string
     */
    private $session_var;
    /**
     * @var
     */
    private $result;
    /**
     * @var
     */
    private $operand1;
    /**
     * @var
     */
    private $operand2;
    /**
     * @var
     */
    private $operator;
    /**
     * @var array
     */
    private $op_symbols = array('+', '-', '*');

    /**
    * @param string $sess_var
    * @param int $min_val
    * @param int $max_val
    */

    public function __construct($sess_var = 'math_captcha_result', $min_val = 0, $max_val = 10) {

       session_start();
        $this->min = ($min_val <= 0) ? 0 : $min_val;
        $this->max = ($max_val <= $this->min) ? 10 : $max_val;
        if (!empty($sess_var)) {
            $this->session_var = $sess_var;
        } else {
            $this->session_var = 'math_captcha_result';
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;    
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;    
    }
    
    /**
    * Generate a new captcha and save the result into a session variable. 
    *
    */
    public function resetCaptcha() {
        $this->__set('operand1', rand($this->min, $this->max));
        $this->__set('operand2', rand($this->min, $this->max));
        $this->__set('operator', $this->op_symbols[rand(0, (count($this->op_symbols) - 1))]);
        $this->compute_result();
    // Save to $_SESSION
        $_SESSION[$this->session_var] = $this->result;
    }

    /**
     *
     */
    private function compute_result() {
        switch ($this->operator) {
            case '+':
                $this->result = ($this->operand1 + $this->operand2);
                break;
            case '-':
                $this->result = ($this->operand1 - $this->operand2);
                break;
            case '*':
                $this->result = ($this->operand1 * $this->operand2);
                break;
        }
    }
    /**
    * 
    * @param int $val Value to be compared to the result in session
    * @return boolean TRUE if the value matches; FALSE otherwise
    */
    public function validate($val) {
        if ($val == $_SESSION[$this->session_var]) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
    * 
    * @param string $format sprintf compatible format with text/html e.g "Compute Sum of {operand1} and {operand2}"
    * @return type
    */
    public function getCaptchaText($format = '{operand1} {operator} {operand2}') {
        if (!empty($format)) {
            return str_replace(array('{operand1}','{operand2}', '{operator}'), array($this->operand1, $this->operand2, $this->operator), $format);
        } else {
            return sprintf("%d %s %d", $this->operand1, $this->operator, $this->operand2);
        }
    }
  
}