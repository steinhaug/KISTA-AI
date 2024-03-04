<?php

class XUploadForm
{
	var $cls_formName;	// form name
	var $cls_formAction;	// form action
	var $cls_formMethod;	// form method (default POST)
	var $cls_formXtraJS;	// for javascript
	var $cls_formEncType;	// form encoding type

	// constructor



	function __construct($action="",$name="myXUploadForm",$method="POST",$xtraJS="")
    {
        $this->XUploadForm($action,$name,$method,$xtraJS);
    }

	function XUploadForm($action="",$name="myXUploadForm",$method="POST",$xtraJS="")
	{

		$this->cls_formName = $name;
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
		echo '<form action="'.$this->cls_formAction.'" name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.' onSubmit="return chkFileUpload()">'."\n";
		else
		echo '<form action="'.$this->cls_formAction.'" name="'.$this->cls_formName.'" method="'.$this->cls_formMethod.'" enctype="multipart/form-data"'.$this->cls_formXtraJS.'>'."\n";
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
	function setFormFileInput( $name = "file" , $size = 40 , $maxLength = 150 )
	{
		echo "<input type=\"file\" name=\"$name\" id=\"$name\" size=\"$size\" maxlenght=\"$maxLength\"><br>\n";
	}


	## displays an button
	## returns nothing
	function setFormButton( $type = "submit" , $value = "Submit" , $name = "submit" , $xtraJS = "" )
	{
		echo "<input type=\"$type\" name=\"$name\" value=\"$value\" onClick=\"$xtraJS\"><br>\n";
	}

	## form ends
	## returns nothing
	function end()
	{
		echo '</form>'."\n";
	}

} // class ends

?>