<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 

class Files extends CI_Model
{

    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        //$this->load->database();
    }
    //move files and directories from one location to another
    public function move_files($source, $dest)
    {
        if (is_dir($source)) {
            if (!is_dir($dest)) {
                mkdir($dest, 0777, true);
            }

            $dir_items = array_diff(scandir($source), array('..', '.'));

            if (count($dir_items) > 0) {
                foreach ($dir_items as $v) {
                    $this->move_files(rtrim(rtrim($source, '/'), '\\') . DIRECTORY_SEPARATOR . $v, rtrim(rtrim($dest, '/'), '\\') . DIRECTORY_SEPARATOR . $v);
                }
            }
        } elseif (is_file($source)) {
            copy($source, $dest);
        }
    }
    /// Delete a directory and it's contents
    function delete_directory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }


    public function deleteDir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->deleteDir($src . '/' . $file);
                }
                else {
                    unlink($src . '/' . $file);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    //get information about a file like name, extension, full file name with extension, path
    function get_file_info($file_path,$info='name'){
        $path_parts = pathinfo($file_path);
        switch($info){
            case 'name':
                $file_info =$path_parts['filename'];

                break;

            case 'ext':
                $file_info =$path_parts['extension'];

                break;

            case 'file':
                $file_info =$path_parts['basename'];

                break;

            case 'dir_path':
                $file_info =$path_parts['dirname'];

                break;
        }

        return $file_info;

    }

    ///return file name without extension
    function remove_extension($file){
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
    }

    //check if file exists in a particular folder
    function check_if_file_exists($file){
        foreach (glob($file) as $filename) {
            return true;
        }
        return false;
    }

    function delete_file($file){
        unlink($file);
    }

    function validate_xml_product($product)
    {
        $children=$product->children();
        foreach($children as $child){
            if ($child->getName()=='artnr') {
                return true;
            }
        }
        return false;
    }

    function if_node_exits($xml,$node)
    {

        $elms = simplexml_load_file($xml);
        foreach ($elms->$node as $elm) {
            $r[] = $elm;
        }

        return (isset($r)) ? TRUE : FALSE;

    }

    function get_xml_values($xml,$top){

        $elms= simplexml_load_file($xml);
        foreach($elms->$top as $elm){
            $topelms[] = $elm;
        }

        return get_object_vars($topelms[0]);
    }
    //check if xml has element in a given array
    function is_main_xml_correct($xml,$main_elms){
        $elm = $this->get_xml_values($xml,'plugin_info');

        $result = array_diff($main_elms, array_keys($elm));

        return (count($result) <= 0) ? TRUE : FALSE;

    }

    //check if a directory is empty. Returns TRUE if empty
    function is_dir_empty($dir) {
        if (!is_readable($dir)) return NULL;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                closedir($handle);
                return FALSE;
            }
        }
        closedir($handle);
        return TRUE;
    }

    //check if directory exists
    function folder_exist($folder)
    {
        // Get canonicalized absolute pathname
        $path = realpath($folder);

        // If it exist, check if it's a directory
        return ($path !== false AND is_dir($path)) ? true : false;
    }
    //execute sql file
    function run_sql_file($location){
        // Set line to collect lines that wrap
        $templine = '';

        // Read in entire file
        $lines = file($location);

        // Loop through each line
        foreach ($lines as $line)
        {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current templine we are creating
            $templine .= $line;

            // If it has a semicolon at the end, it's the end of the query so can process this templine
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                $this->CI->db->query($templine);

                // Reset temp variable to empty
                $templine = '';
            }
        }
    }



    function upload_files($input_name,$folder)
    {
        $config['upload_path'] = $folder;
        $config['allowed_types'] = 'jpeg|png|gif|zip';
        $config['max_size'] = '2048';
        $config['max_width']  = '0';
        $config['max_height']  = '0';

        $this->CI->load->library('upload', $config);

        if ( ! $this->CI->upload->do_upload($input_name))
        {
            $error = array('error' => $this->CI->upload->display_errors());

            print_r($error);
            die();
        }
        else { 
            $data = $this->CI->upload->data();
        }
        return $data ;
    }


}

