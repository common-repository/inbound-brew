<?php

/**
 * User: Rico Celis
 * Date: 07/30/15
 * Time: 8:30 PM
 * Class handles the creation of HTML form Elements.
 */

namespace InboundBrew\Libraries;

class FormHelper {

  var $required_class = "ib-required";
  var $data = array(); // used for setting previous values.
  var $hiddenFields = false; // if user is going to be able to edit form from hidden fields.

  public function __construct() {

  }

  /**
   * Create from element
   * Add hidden field to handle ajax hooks based on form id
   *
   * @author Rico Celis
   * @param string $form_id form id also default value for hidden input field for ajax hook
   * @param array $attributes list of attributes for FROM element.
   */
  public function create($form_id, $attributes = array()) {
    $defaults = array(
      'ib_action' => $form_id,
      'id' => $form_id,
      'method' => "POST",
      'url' => $_SERVER["REQUEST_URI"],
    );
    $reserved = array("url", "type");
    $attributes = array_merge($defaults, $attributes);
    if (@$attributes['type'] == "file")
      $attributes['enctype'] = "multipart/form-data";
    $str = "<form action=\"" . $attributes['url'] . "\" ";
    foreach ($attributes as $attr => $value) {
      if (in_array($attr, $reserved))
        continue;
      $str.="{$attr}=\"{$value}\"";
    }
    $str.=">";
    if (@$attributes['ib_action']) {
      $str.="<input type=\"hidden\" name=\"action\" value=\"" . $attributes['ib_action'] . "\">";
    }
    return $str;
  }

  /**
   * create closing tag for FORM element
   *
   * @author Rico Celis
   * @return string closing tag for FORM element
   */
  public function end() {
    return "</form>";
  }

  /**
   * create INPUT of "text" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function text($field_name, $attributes = array()) {
    $default_value = stripcslashes(htmlspecialchars($this->getDefaultValue($field_name)));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "text",
      'div' => "input text"
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    if (strlen($default_value))
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"text\" name=\"" . $attributes['name'] . "\" ";
    if ($this->hiddenFields) {
      if (@$attributes['style']) {
        $attributes['style'] .=";display:none;";
      } else {
        $attributes['style'] = "display:none;";
      }
    }
    if ($attributes['required'])
      $str.= "required ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"text\">" . ((@$attributes['value']) ? $attributes['value'] : "N/A") . "</div>";
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "url" type (just like text, but uses a different regex to validate)
   *
   * @author Chris Fontes
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function url($field_name, $attributes = array()) {
    $default_value = stripcslashes(htmlspecialchars($this->getDefaultValue($field_name)));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "url",
      'div' => "input url"
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    if (strlen($default_value))
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"url\" name=\"" . $attributes['name'] . "\" ";
    if ($this->hiddenFields) {
      if (@$attributes['style']) {
        $attributes['style'] .=";display:none;";
      } else {
        $attributes['style'] = "display:none;";
      }
    }
    if ($attributes['required'])
      $str.= "required ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"text\">" . ((@$attributes['value']) ? $attributes['value'] : "N/A") . "</div>";
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "urlpath" type (just like text, but uses a different regex to validate)
   *
   * @author Chris Fontes
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function urlpath($field_name, $attributes = array()) {
    $default_value = stripcslashes(htmlspecialchars($this->getDefaultValue($field_name)));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "urlpath",
      'div' => "input urlpath"
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    if (strlen($default_value))
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"url\" name=\"" . $attributes['name'] . "\" ";
    if ($this->hiddenFields) {
      if (@$attributes['style']) {
        $attributes['style'] .=";display:none;";
      } else {
        $attributes['style'] = "display:none;";
      }
    }
    if ($attributes['required'])
      $str.= "required ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"text\">" . ((@$attributes['value']) ? $attributes['value'] : "N/A") . "</div>";
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "text" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function password($field_name, $attributes = array()) {
    $default_value = htmlspecialchars($this->getDefaultValue($field_name));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "text",
      'div' => "input text"
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    if (strlen($default_value))
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"password\" name=\"" . $attributes['name'] . "\" ";
    if ($attributes['required'])
      $str.= "required ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "text" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function email($field_name, $attributes = array()) {
    $default_value = htmlspecialchars($this->getDefaultValue($field_name));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "email",
      'div' => "input email"
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    if ($default_value)
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"email\" name=\"" . $attributes['name'] . "\" ";
    if ($this->hiddenFields) {
      if (@$attributes['style']) {
        $attributes['style'] .=";display:none;";
      } else {
        $attributes['style'] = "display:none;";
      }
    }
    if ($attributes['required'])
      $str.= "required ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"email\">" . ((@$attributes['value']) ? $attributes['value'] : "N/A") . "</div>";
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "textarea" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function textarea($field_name, $attributes = array()) {
    $default_value = $this->getDefaultValue($field_name);
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "text",
      'div' => "input text",
      'value' => ""
    );
    $reseved = array("label", "value", "required");
    $attributes = array_merge($defaults, $attributes);
    if ($default_value)
      $attributes['value'] = $default_value;
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<textarea type=\"text\" name=\"" . $attributes['name'] . "\" ";
    // hidden text
    if ($this->hiddenFields) {
      if (@$attributes['style']) {
        $attributes['style'] .=";display:none;";
      } else {
        $attributes['style'] = "display:none;";
      }
    }
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">{$attributes['value']}</textarea>"; // close intial select tag
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"textarea\">" . ((@$attributes['value']) ? $attributes['value'] : "N/A") . "</div>";
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * return and INPUT of "text" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param string $value value for hidden field
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function hidden($field_name, $value = "", $attributes = array()) {
    $default_value = htmlspecialchars($this->getDefaultValue($field_name));
    if (empty($value) && $default_value)
      $value = $default_value;
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "hidden",
    );
    $reseved = array("label", "required");
    $attributes = array_merge($defaults, $attributes);
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"hidden\" name=\"" . $attributes['name'] . "\" value=\"{$value}\" ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    return $str;
  }

  /**
   * return and INPUT of "file" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $attributes list of attributes for INPUT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   */
  public function file($field_name, $attributes = array()) {
    $field_name = $this->parseFieldName($field_name, false);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'required' => false,
      'data-type' => "text",
      'div' => "input text"
    );
    $reseved = array("label", "required", "type");
    $attributes = array_merge($defaults, $attributes);
    $str = "";
    // if textfield has a label
    if ($attributes['label']) {
      $rLabel = "";
      // if required
      if ($attributes['required']) {
        $rLabel = "<span class='required'>*</span>";
      }
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    // if field is required
    if ($attributes['required']) {
      $attributes['class'] = (@$attributes['class']) ? $attributes['class'] . " " . $this->required_class : $this->required_class;
    }
    $str .= "<input type=\"file\" name=\"" . $attributes['name'] . "\" ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * returns a SELECT HTML element
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the SELECT
   * @param array $options options for select EXAMPLE:
   * 	array(
   * 		"blue" => "Blue Color",
   * 		"red" => "Red Color");
   * @param array $attributes list of attributes for SELECT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   * 		"empty": the text for the first option in the select (empty value)
   * 		"selected": index in $options that needs to be selected.
   * 			"selected" attribute overwrites using default data to set selected
   */
  public function select($field_name, $options = array(), $attributes = array()) {
    $default_value = preg_replace("/[\n\r]/", "", $this->getDefaultValue($field_name));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'data-type' => "select",
      'div' => "input select",
      'required' => false
    );
    $reseved = array("empty", "selected", "label");
    $attributes = array_merge($defaults, $attributes);
    // handle label
    $str = "";
    if ($attributes['label']) {
      $rLabel = (@$attributes['required']) ? "<span class='required'>*</span>" : "";
      $str .= "<LABEL for=\"" . $attributes['id'] . "\">{$rLabel}" . $attributes['label'] . ":</LABEL>";
    }
    $str .= "<SELECT name=\"" . $attributes['name'] . "\" ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        if ($attribute == "required" && $value === false)
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .=">"; // close intial select tag
    // set selected option
    $selected = (@$attributes['selected']) ? $attributes['selected'] : $default_value;
    // empty option
    if (isset($attributes['empty'])) {
      $sel = (!$selected) ? "selected=\"selected\"" : ""; // current option selected?
      $str .="<option value=\"\" {$sel}>" . $attributes['empty'] . "</option>";
    }
    // loop through options
    if (!empty($options)) {
      $counter = 0;
      foreach ($options as $value => $option) {
        if (!$selected && $counter == 0 && !isset($attributes['empty']))
          $selected = $value;
        if ($value == $selected) {
          $sel = "selected=\"selected\""; // current option selected?
          $theValue = $option;
        } else {
          $sel = "";
        }
        $str.="<OPTION value=\"{$value}\" {$sel}>{$option}</OPTION>";
        $counter ++;
      }
    }
    $str.="</SELECT>"; // close select
    // if need to wrap input in a div?
    if ($attributes['div']) {
      if ($this->hiddenFields) {
        $display = "display:none;";
      }
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass} style=\"" . @$display . "\">{$str}</div>";
    }
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"select\">" . ((@$theValue) ? $theValue : "N/A") . "</div>";
    }
    return $str; // return SELECT string
  }

  /**
   */
  public function checkboxes($field_name, $options, $attributes = array()) {
    $default_value = $this->getDefaultValue($field_name);
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'div' => "input checkboxes",
      'data-type' => "checkbox",
    );
    $str = "";
    if (@$attributes['label'])
      $str .= "<label>{$attributes['label']}:</label>";
    $attributes = array_merge($defaults, $attributes);
    $counter = 0;
    $values = array();
    if (!empty($default_value)) {
      $d_array = explode("\n", $default_value);
      foreach ($d_array as $v) {
        $v = preg_replace("/[\n\r]/", "", $v);
        if ($v)
          $values[] = $v;
      }
    }
    $theValue = "";
    foreach ($options as $value => $label) {
      if (in_array($value, $values)) {
        $chk = "checked=\"checked\"";
        $theValue .= $label . " ";
      } else {
        $chk = "";
      }

      $str .= "<div class=\"inner-checkbox\"><input type=\"checkbox\" id=\"{$attributes['id']}{$counter}\" value=\"{$value}\" name=\"{$attributes['name']}[]\" {$chk}/> {$label}</div>";
    }

    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      if ($this->hiddenFields) {
        //$display = "display:none;";
      }
      $str = "<div {$dClass} style=\"" . @$display . "\">{$str}</div>";
    }
    // hidden content
    if ($this->hiddenFields) {
      $str = "<div {$dClass} style=\"" . @$display . "\">{$str}</div>";
      //$str.= "<div class='hidden-text' data-type=\"select\">" . ((@$theValue)? $theValue : "N/A") . "</div>";
    }
    return $str;
  }

  /**
   * returns a INPUT type=checkbox HTML element
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param string $value value attribute for checkbox
   * @param array $attributes list of attributes for SELECT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   * 		"empty": the text for the first option in the select (empty value)
   * 		"checked": determines if checkbox is checked
   * 			"checked" attribute overwrites using default data to set selected
   */
  public function checkbox($field_name, $value, $attributes = array()) {
    $default_value = $this->getDefaultValue($field_name);
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'div' => "input checkbox",
      'data-type' => "checkbox",
    );
    $reseved = array("label");
    $attributes = array_merge($defaults, $attributes);
    // handle label
    if (!isset($attributes['checked'])) {
      if ($default_value == $value)
        $attributes['checked'] = "checked";
    }else {
      if ($attributes['checked'] === false) { // if false
        unset($attributes['checked']);
      } else { // is set to something other than false
        $attributes['checked'] = "checked"; // force correct requirements for HTML 5
      }
    }
    $str = "<input type=\"checkbox\" value=\"" . $value . "\" ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .="/>"; // close intial select tag
    // handle label
    if ($attributes['label']) {
      $str .= " " . $attributes['label'];
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "radio" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $options options for radios
   * @param array $attributes list of attributes for SELECT.
   * reserved:
   * 	div: false = no div will be assign string = class assigned to div
   * @return string INPUT elements
   */
  function radio($field_name, $options = array(), $attributes = array()) {
    $default_value = preg_replace("/[\n\r]/", "", $this->getDefaultValue($field_name));
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'div' => "input radio",
      'id' => $field_name['id'],
      'checked' => "",
      'label' => false,
    );
    $attributes = array_merge($defaults, $attributes);
    if (empty($checked)) { // no checked passed
      if (empty($default_value))
        $checked = key($options); // first key in options.
      else
        $checked = $default_value; // use default value.
    }
    $str = "";
    if ($attributes['label'])
      $str.= "<label>{$attributes['label']}:</label>";
    $counter = 0;
    $reserved = array('div', 'id', 'checked', 'label');
    foreach ($options as $value => $label) {
      if ($checked == $value) {
        $chk = "checked=\"checked\"";
        $theValue = $label;
      } else {
        $chk = "";
      }
      if (@$attributes['in_divs'])
        $str .="<div class=\"{$attributes['in_divs']}\">";
      $str .="<input type=\"radio\" id=\"{$attributes['id']}{$counter}\" name=\"{$field_name['name']}\" value=\"{$value}\" {$chk}";
      foreach ($attributes as $attr => $attr_value) {
        if (in_array($attr, $reserved))
          continue;
        $str.=" {$attr}=\"{$attr_value}\"";
      }
      $str .=">";
      if ($label)
        $str.="{$label}&nbsp;&nbsp;&nbsp;";
      if (@$attributes['in_divs'])
        $str.="</div>";
      $counter ++;
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      if ($this->hiddenFields) {
        $display = "display:none;";
      }
      $str = "<div {$dClass} style=\"" . @$display . "\">{$str}</div>";
    }
    // hidden content
    if ($this->hiddenFields) {
      $str.= "<div class='hidden-text' data-type=\"select\">" . ((@$theValue) ? $theValue : "N/A") . "</div>";
    }
    return $str;
  }

  /**
   * create INPUT of "radio" type
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $options options for radios
   * @param array $attributes list of attributes for SELECT.
   * reserved:
   * 	div: false = no div will be assign string = class assigned to div
   * @return string INPUT elements
   */
  function wpUpload($field_name, $attributes) {
    $default_value = $this->getDefaultValue($field_name);
    $defaults = array(
      'class' => "ib_media-select",
      'field_attributes' => array(
        'div' => false,
      )
    );
    $attributes = array_merge($defaults, $attributes);
    $input = $this->text($field_name, $attributes["field_attributes"]);
    $str = "<div";
    foreach ($attributes as $attribute => $value) {
      if ($attribute == "field_attributes")
        continue; // skip field attributes
      $str .= " {$attribute}='{$value}'";
    }
    $str .=">";
    $str .= $input;
    $str .="<div class=\"ib-button\">{$attributes['label']}</div>
			<div class='clear'></div>";
    $str .= "</div>";
    return $str;
  }

  /**
   * create TinyMCE Editor window using wp_editor method
   *
   * @author Rico Celis
   * @param string $field_name Name attribute of the INPUT
   * @param array $settings options for tinymce
   * @param array $attributes list of attributes for SELECT.
   */
  public function wpEditor($field_name, $args = array(), $attributes = array()) {
    $default_value = $this->getDefaultValue($field_name);
    $field_name = $this->parseFieldName($field_name);
    // default attributes
    $defaults = array(
      'div' => "input tinymce",
    );
    $attributes = array_merge($defaults, $attributes);
    // default wp editor args
    $dargs = array(
      'wpautop' => false,
      'textarea_rows' => '40',
      'editor_height' => 400,
      'textarea_name' => $field_name['name'],
      'drag_drop_upload' => true
    );
    $args = array_merge($dargs, $args);
    if ($attributes['div'])
      echo "<div class=\"{$attributes['div']}\">";
    wp_editor($default_value, $field_name['id'], $args);
    if ($attributes['div'])
      echo "</div>";
  }

  function time($field_name, $attributes = array()) {
    $defaults = array(
      'div' => "input time",
      'on_the_hour' => false
    );
    $attributes = array_merge($defaults, $attributes);
    if (!$attributes['on_the_hour']) {
      $default_value = $this->getDefaultValue($field_name);
      $original = $field_name;
      $field_name = $this->parseFieldName($field_name);
      $hours = array();
      $minutes = array();
      $meridian = array("am" => "AM", "pm" => "PM");
      foreach (range(1, 12) as $hour) {
        $val = sprintf("%02d", $hour);
        $hours[$val] = $val;
      }
      foreach (range(0, 55) as $minute) {
        $val = sprintf("%02d", $minute);
        $minutes[$val] = $val;
      }
      // handle default value
      if ($default_value) {
        $time = date('h:i:a', strtotime($default_value));
      } else {
        $time = date("h:i:a");
      }
      list($h_selected, $m_selected, $md_selected) = explode(":", $time);
      $hours_select = $this->select($original . ".hours", $hours, array('div' => false, 'selected' => $h_selected));
      $minutes_select = $this->select($original . ".minutes", $minutes, array('div' => false, 'selected' => $m_selected));
      $meridian_select = $this->select($original . ".meridian", $meridian, array('div' => false, 'selected' => $md_selected));
      if ($attributes['div']) {
        $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
        $str = "<div {$dClass}>{$hours_select}{$minutes_select}{$meridian_select}</div>";
      }
    } else { // on the hour
      $options = array(
        '01:00:00' => "01:00 AM",
        '02:00:00' => "02:00 AM",
        '03:00:00' => "03:00 AM",
        '04:00:00' => "04:00 AM",
        '05:00:00' => "05:00 AM",
        '06:00:00' => "06:00 AM",
        '07:00:00' => "07:00 AM",
        '08:00:00' => "08:00 AM",
        '09:00:00' => "09:00 AM",
        '10:00:00' => "10:00 AM",
        '11:00:00' => "11:00 AM",
        '12:00:00' => "12:00 AM",
        '13:00:00' => "01:00 PM",
        '14:00:00' => "02:00 PM",
        '15:00:00' => "03:00 PM",
        '16:00:00' => "04:00 PM",
        '17:00:00' => "05:00 PM",
        '18:00:00' => "06:00 PM",
        '19:00:00' => "07:00 PM",
        '20:00:00' => "08:00 PM",
        '21:00:00' => "09:00 PM",
        '22:00:00' => "10:00 PM",
        '23:00:00' => "11:00 PM",
        '00:00:00' => "12:00 PM",
      );
      return $this->select($field_name, $options, $attributes);
    }
    return $str;
  }

  /**
   * creates name attribute and id based on field name:
   * Example
   * Model.first_word.second_word.0.third_word
   *
   * @author Rico Celis
   * @param string $field_name for input field
   * @param boolean $nest_in_data true if name starts with "data"
   * @return array with id and new "name" attribute field
   * 	id = ModelFirstWordSecondWord0ThirdWord
   * 	name = data[Model][first_word][second_word][0][third_word]
   *
   */
  function parseFieldName($field_name, $nest_in_data = true) {
    $arr = explode(".", $field_name);
    $id_str = "";
    $name_str = ($nest_in_data) ? "data" : "";
    $counter = 0;
    foreach ($arr as $value) {
      // check for underscores
      $val_arr = preg_split("/(_|-)/", $value);
      $cap = "";
      foreach ($val_arr as $lower) {
        $cap .= ucfirst($lower); // capitalize first letter
      }
      $id_str .= $cap;
      if ($nest_in_data) {
        $name_str .= "[{$value}]";
      } else {
        if ($counter)
          $name_str .= "[{$value}]";
        else
          $name_str .= $value;
      }

      $counter ++;
    }
    return array(
      'id' => $id_str,
      'name' => $name_str
    );
  }

  /**
   * Returns a default value set by $Helper->data in controller
   * uses the field name provide when the form input was created.
   * example: Model.field.0.field
   *
   * @author Rico Celis
   * @param string $field_name for input field
   * @return string value from data array (if found)
   *
   */
  function getDefaultValue($field_name) {
    $path = "";
    $explode = explode(".", $field_name);
    foreach ($explode as $value) {
      $path.="['{$value}']";
    }
    $eval = "return \$this->data" . $path . ";";
    return @eval($eval);
  }

  //nested array or object utf8_encoding - orignally created for Countries list that broke json_encode
  /**
   * Returns an object or array that has been crawled
   * and utf8 encoded
   * orignally created to fix country list that broke json_encode
   *
   * @author Chris Fontes (Oscar Broman from PHP.net)
   * @param string $iput as object or array
   * @return object or array that is utf* encoded
   *
   */
  static function utf8_encode_deep(&$input) {
    if (is_string($input)) {
      $input = utf8_encode($input);
    } else if (is_array($input)) {
      foreach ($input as &$value) {
        self::utf8_encode_deep($value);
      }
      //unset($value);
    } else if (is_object($input)) {
      $vars = array_keys(get_object_vars($input));

      foreach ($vars as $var) {
        self::utf8_encode_deep($input->$var);
      }
    }
  }

  /**
   * returns a INPUT type=checkbox HTML element
   *
   * @author Chirag
   * @param string $field_name Name attribute of the INPUT
   * @param string $value value attribute for checkbox
   * @param array $attributes list of attributes for SELECT.
   * 	reserved attributes:
   * 		"label": creates a LABEL tag with this value before field
   * 		"empty": the text for the first option in the select (empty value)
   * 		"checked": determines if checkbox is checked
   * 			"checked" attribute overwrites using default data to set selected
   */
  public function singlecheckbox($field_name, $value, $attributes = array()) {
    $default_value = $this->getDefaultValue($field_name);
    $field_name = $this->parseFieldName($field_name);
    $defaults = array(
      'id' => $field_name['id'],
      'name' => $field_name['name'],
      'label' => false,
      'div' => "input checkbox",
      'data-type' => "checkbox",
    );
    $reseved = array("label");
    $attributes = array_merge($defaults, $attributes);

    // handle label
    if (!isset($attributes['checked'])) {
      if ($default_value == $value)
        $attributes['checked'] = "checked";
    }else {
      if ($attributes['checked'] === false) { // if false
        unset($attributes['checked']);
      } else { // is set to something other than false
        $attributes['checked'] = "checked"; // force correct requirements for HTML 5
      }
    }
    $str = "<input type=\"checkbox\" value=\"" . $value . "\" ";
    // loop through attributes
    if (!empty($attributes)) {
      foreach ($attributes as $attribute => $value) {
        if (in_array($attribute, $reseved))
          continue;
        $str .= "{$attribute}=\"$value\" ";
      }
    }
    $str .="/>"; // close intial select tag
    // handle label
    if ($attributes['label']) {
      $str .= " " . $attributes['label'];
    }
    // if need to wrap input in a div?
    if ($attributes['div']) {
      $dClass = (is_string($attributes['div'])) ? "class=\"{$attributes['div']}\"" : "";
      $str = "<div {$dClass}>{$str}</div>";
    }
    return $str;
  }

}
