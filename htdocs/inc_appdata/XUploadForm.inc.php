<?php

class XUploadForm
{
	var $cls_formName;	// form name
	var $cls_formAction;	// form action
	var $cls_formMethod;	// form method (default POST)
	var $cls_formXtraJS;	// for javascript
	var $cls_formEncType;	// form encoding type
    var $cls_formClass;

	function __construct($action='',$name='myXUploadForm',$method='POST',$xtraJS=''){
        $this->XUploadForm($action,$name,$method,$xtraJS);
    }

	function XUploadForm($action='',$name='myXUploadForm',$method='POST',$xtraJS=''){
      if(is_array($name)){
        foreach ($name AS $key=>$val){ 
          $temp = 'cls_form' . ucfirst($key);
          $this->$temp = $val;
        }
      } else {
        $this->cls_formName = $name;
      }
		$this->cls_formAction = $action;
		$this->cls_formMethod = $method;
		$this->cls_formXtraJS = $xtraJS;
		$this->setFormEncType("multipart/form-data");

	}

	## form begins
	## returns nothing
	function begin($onsubmit=1)
	{
		if($onsubmit)
		echo '<form ' . (strlen($this->cls_formClass)?' class="' . $this->cls_formClass . '" ':'') . 'action="'.$this->cls_formAction.'" id="'.$this->cls_formName.'" name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.' onSubmit="return chkUpload()">'."\r\n";
		else
		echo '<form ' . (strlen($this->cls_formClass)?' class="' . $this->cls_formClass . '" ':'') . 'action="'.$this->cls_formAction.'" id="'.$this->cls_formName.'" name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.'>'."\r\n";
	}
	function setForm($id='',$onsubmit='')
	{
        if(strlen($id))
          $id = ' id=\'' . $id . '\'';
          else
          $id = '';
		if(strlen($onsubmit))
		echo '<form action="'.$this->cls_formAction.'" ' . $id . ' name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.' onSubmit="return ' . $onsubmit . '">'."\r\n";
		else
		echo '<form action="'.$this->cls_formAction.'" ' . $id . ' name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.'>'."\r\n";
	}



	## set form encoding type
	## returns nothing
	function setFormEnctype($encType)
	{
		$this->cls_formEncType = $encType;
	}

	## set max file size
	## returns nothing
	function setFormMaxFileSize( $size = "2097152" ) // default max file size 2MB
	{
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$size\">\n";
	}

	## displays an input file
	## returns nothing
	function setFormFileInput( $name = "file" , $size = 40 , $maxLength = 150 , $class='', $style = '' ){
        $xtra = '';
        if($style)
          $xtra .= ' style="' . $style . '"';
        if($class)
          $xtra .= ' class="' . $class . '"';

		echo '<input type="file" name="' . $name . '" size="' . $size . '" maxlenght="' . $maxLength . '"' . $xtra . "><br>\n";
	}


	## displays an button
	## returns nothing
	function setFormButton( $type = "submit" , $value = "Submit" , $name = "submit" , $xtraJS = "", $class='', $style='' )
	{
        $xtra = '';
        if($style)
          $xtra .= ' style="' . $style . '"';
        if($class)
          $xtra .= ' class="' . $class . '"';

		echo "<input type=\"$type\" class=\"submit\" name=\"$name\" value=\"$value\"";
        if(strlen($xtraJS))
          echo " onClick=\"$xtraJS\"";
        echo $xtra . "><br>\n";
	}

	## form ends
	## returns nothing
	function end()
	{
		echo '</form>'."\n";
	}

} // class ends

?>