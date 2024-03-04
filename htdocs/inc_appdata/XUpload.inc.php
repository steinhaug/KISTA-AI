<?php

class XUpload
{

	var $cls_upload_dir; 				// Directory to upload to
	var $cls_max_filesize; 				// max file size (must be set in form)
	var $cls_filename;				// Name of the uploaded file
	var $cls_filesize; 				// file size
	var $cls_file;					// file
	var $cls_copyfile; 				// Final filename to copy, after change
	var $cls_referer_domain;			// domain from script is called from
	var $cls_arr_ext_accepted; 			// Type of file we will accept.
	var $cls_rename_file; 				// must rename uploaded file or not
	var $cls_copyCode;				// error code
	var $cls_domain;				// our domain
	var $cls_domain_check;				// domain check flag: 1 check referrer domain, 0 not.
	var $cls_overWrite;	 			//Overwrite the file if exists



	function __construct( $inputName = "file" , $rename = 0 , $ow = 1)
	{
        $this->XUpload($inputName,$rename,$ow);
    }

	function XUpload( $inputName = "file" , $rename = 0 , $ow = 1)
	{
		$myfile=$inputName;
		$myfilename=$inputName.'_name';
		$myfilesize=$inputName.'_size';

		global $HTTP_REFERER,$$myfile,$$myfilename,$$myfilesize,$MAX_FILE_SIZE;

		$url=parse_url( $HTTP_REFERER );

		//configuration flags: modify them to fit your application
		$this->cls_domain = "http://www.xarelan.com";	// our domain
		$this->cls_domain_check=0;			// 1 check referrer domain, 0 do not.

		$this->cls_overWrite = $ow;
		$this->cls_rename_file = $rename;
		if(isset($url["scheme"]) && isset($url["host"]))
		  $this->cls_referer_domain = $url["scheme"]."://".$url["host"];
		  else
		  $this->cls_referer_domain = '://';
		$this->cls_max_filesize = $MAX_FILE_SIZE;
		$this->cls_filename = $$myfilename;
		$this->cls_file = $$myfile;
		$this->cls_filesize = $$myfilesize;
		$this->cls_arr_ext_accepted = array(
          '.jpg',
          '.jpeg',
          '.gif',
          '.bmp',
          '.tif',
          '.tiff',
          '.ai',
          '.pdf',
          '.eps',
          '.png'
          );
  	}

	/** setDir()
	** Accessor method to set the directory we will upload to.
	** @param String name of directory we upload to
	* @returns void
	**/
	function setDir( $dir )
	{
    		$this->cls_upload_dir = $dir;
  	}



   	/** removeFile()
   	** Remove a remote file in upload dir(if we have perms)
   	** @returns 1 if file was removed, 0 if an error occurred
   	**/
   	function removeFile( $file )
   	{
   		//check if file exists
		if (file_exists($this->cls_upload_dir."/".$file))
		{
			if( @unlink($this->cls_upload_dir."/".$file) )
			{
				return(1);
			}
			else
			{
				return(0);
			}
		}
		else
		{
			return (0);
		}
   	}


   	/** setCompleteFilename()
   	** Complete path where we want to upload the file.
   	** @returns void

	** Removed slash since already used in EU script, Kim
   	**/
   	function setCompleteFilename()
   	{
     		//$this->cls_copyfile = $this->cls_upload_dir ."/". $this->cls_filename;
     		$this->cls_copyfile = $this->cls_upload_dir . $this->cls_filename;
   	}



  	/** checkExtension()
   	** Function to make sure the extension of the file is accepted
   	** @returns boolean
   	**/
  	function checkExtension()
  	{
		//echo '--> ' . $this->cls_filename . ' <-- checkExtension <br>';
		// correct filename just in case
		$this->cls_filename = preg_replace('/\s+/', '_', $this->cls_filename );
		$this->cls_filename = preg_replace('/%20/', '_', $this->cls_filename );

		// Check if the extension is valid
		return( in_array( strtolower($this->get_extension( $this->cls_filename )), $this->cls_arr_ext_accepted ));
	}

  	/** checkFileSize()
	** Function to make sure the uploaded file is not too big
	** @returns boolean
	**/
	function checkFileSize()
	{
		return( !($this->cls_filesize > $this->cls_max_filesize) );
	}


	/** filenameExist()
	** Function to check if a file with the same filename
	** exist in the directory we upload to.
	** @returns Boolean
	*/
	function filenameExist()
	{
		return( file_exists( $this->cls_copyfile ) );
	}

	/** xCopy()
	** Funtion to copy the file.
	** @returns 1 if copy was succesfull, 0 if an error occurred.
	** Also sets error flag( look show_progressMessage() method below)
	*/
  	function xCopy($uploaded_name="")
	{

		global $HTTP_REFERER;

		$url=parse_url( $HTTP_REFERER );
		if(isset($url["scheme"]) && isset($url["host"]))
		  $this_domain = $url["scheme"]."://".$url["host"]; //get domain script is called from
		  else
		  $this_domain = '://';

		if ( $this->cls_file!="none")
		{
			if( !$this->checkExtension() )
			{
				$this->cls_copyCode = 1; // extension not accepted
			}
			else
			{
					$this->setCompleteFilename();

					if( $this->filenameExist( $this->cls_copyfile ) )
					{
						if ( $this->cls_overWrite ) //check overwrite flag
						{
							if (!unlink( $this->cls_copyfile ))
							{
								$this->cls_copyCode = 3; // can't delete remote file
							}
							else //continue the copy process
							{
								$this->cls_copyCode = $this->resumeCopy($this_domain,$uploaded_name);
							}
						}
						else
						{
							$this->cls_copyCode = 4; // file exists and OverWrite not set
						}
					}
					else
					{
						$this->cls_copyCode = $this->resumeCopy($this_domain,$uploaded_name);
					}
			}

		}
		else
		{
			if (!empty($this->cls_filename) )
				$this->cls_copyCode = 2;
			else
				$this->cls_copyCode = 8;
		}
		return (!$this->cls_copyCode);
	}
	/** resumeCopy()
	** Private funtion to resume the copy process.
	** DO NOT CALL THIS FUNCTION, IS AUTOMATICALLY CALLED IN xCopy() method
	** @returns 0 if copy was succesfull, int code if an error occurred.
	** Also sets error flag( look show_progressMessage() method below)
	*/
	function resumeCopy($this_domain,$uploaded_name)
	{
		if (isset($cls_domain_check))
		{
			// make sure calling from this domain
			if( $this_domain != $this->cls_referer_domain )
			{
				return 7; // external script!
			}
		}


//echo "Content : " . $this->cls_file . "<br>";

		// try to copy the file
		if( copy( $this->cls_file, $this->cls_copyfile ))
		{
			// rename uploaded file if needed
			if( $this->cls_rename_file )
			{

				//check if user give a new name or wants a random name
				$temp_name = ($uploaded_name)? $this->changeFilename($uploaded_name) : $this->changeFilename();

				if( !rename( $this->cls_copyfile, $temp_name ))
				{
					return 5; // copy sucess, but renaming file failed
				}
				else
				{
			              $this->cls_copyfile = $temp_name; // just in case we need final complete path
				}
          		}

			return 0; // copy success

        	}
		else
		{
			return 6; // copy failed!
		}

	}

	/** changeFilename()
	* Function to change the filename before copying the file in the new
	* directory. You can provide a name or get a random one
	*
	* @returns string new filename
	*/
	function changeFilename( $new_name="" )
	{
		if ( ! $new_name )
		{
			// Remove the extension
			$extension = $this->get_extension( $this->cls_copyfile );

			// Get a random name for the file, 10 chars long.
			$new_name = strtolower( rndName( 10 ));

			// put back extension
			$new_name .= $extension;
			$new_name = strtolower( $new_name );

		}

		// update the class var
		$this->cls_filename = $new_name;

		// rebuild complete path name
		return ( $this->cls_upload_dir ."/$new_name" );

	}



	/* ---------------------- */
	/* Some utilily functions */
	/* ---------------------- */

	/** get_extension()
	* Return everything after the . of the file name (including the .)
	* @ Returns String
	*/
	function get_extension( $filename )
	{
		return strrchr( $filename, "." );
  	}


  	## get_filename()
  	## get the uploaded filename, maybe to insert it into database
  	## returns the filename string
  	function get_filename ()
  	{
  		return ( $this->cls_filename );
  	}

  	## get_progressMessage()
  	## catch the copy code and returns the message for each file upload progress
  	## you can edit your error messages
  	## returns nothing
  	function show_progressMessage ($mode='echo')
  	{
		switch( $this->cls_copyCode )
  		{

			case 0:	$msg = "Filen <b>".$this->get_filename()."</b> ble lastet opp.\n";
				break;
			case 1:	$msg = "<b>".$this->get_filename()."</b> ble ikke lastet opp. <b>".$this->get_extension($this->get_filename())."</b> filtypen er ikke tillatt!\n";
				break;
			case 2:	$msg = "Filen <b>$this->cls_filename</b> er for stor eller eksisterer ikke!";
				break;
			case 3:	$msg = "Ekstern fil kunne ikke slettes!\n";
  		 		break;
			case 4:	$msg = "Filen <b>".$this->cls_filename."</b> eksisterer allerede og overskrivning er ikke aktivert!\n";
				break;
			case 5:	$msg = "Kopiering vellykket, men endring av navn misslyktes!\n";
				break;
			case 6:	$msg = "Klarte ikke å kopiere filen :(\n";
				break;
			case 7:	$msg = "Du har ikke rettigheter nok for å bruke opplastningsmodulen!\n";
				break;
			case 8:	$msg = ""; // if user does not select a file
				break;
			default:$msg = "Ukjent feil!\n";

  		}
  		if ($msg AND ($mode=='echo'))
  			echo $msg;
  		else if ($msg)
  			return $msg;
  	}

  	function show_progressStatus ()			// Extra added for wwr by Kim Steinhaug
  	{
		return $this->cls_copyCode;
  	}


}; // class ends

/*
==================================================
External function used to rename the uploaded file
==================================================
*/
if(!function_exists('rndName')){
function rndName( $name_len = 10 )
{
	$allchar = "ABCDEFGHIJKLNMOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890_" ;
	$str = "" ;
	mt_srand (( double) microtime() * 1000000 );
	for ( $i = 0; $i<$name_len ; $i++ )
		$str .= substr( $allchar, mt_rand (0,25), 1 ) ;
	return $str ;
}
}
?>