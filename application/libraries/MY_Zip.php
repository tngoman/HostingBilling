<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Zip extends CI_Zip  {

	function read_dir($path, $preserve_filepath = TRUE, $root_path = NULL, $ignore = array())
	{
		if ( ! $fp = @opendir($path))
		{
			return FALSE;
		}

		// Set the original directory root for child dir's to use as relative
		if ($root_path === NULL)
		{
			$root_path = dirname($path).'/';
		}

		while (FALSE !== ($file = readdir($fp)))
		{
			if (substr($file, 0, 1) == '.' || in_array($path.$file, $ignore))
			{
				continue;
			}

			if (@is_dir($path.$file))
			{
				$this->read_dir($path.$file."/", $preserve_filepath, $root_path, $ignore);
			}
			else
			{
				if (FALSE !== ($data = file_get_contents($path.$file)))
				{
					$name = str_replace("\\", "/", $path);

					if ($preserve_filepath === FALSE)
					{
						$name = str_replace($root_path, '', $name);
					}

					$this->add_data($name.$file, $data);
				}
			}
		}

		return TRUE;
	}

}

/* End of file MY_Zip.php */
/* Location: ./application/libraries/MY_Zip.php */