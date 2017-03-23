<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

function get_contract_config($obj_config, $obj_name = null)
{
    $CI = & get_instance();
    $config = $CI->config->item($obj_config);

    if (isset($_FILES['contracts']) && isset($_FILES['contracts']['name']))
    {
        $fileName = array();
        
        foreach ($_FILES['contracts']['name'] as $file)
        {   
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $name = pathinfo($file, PATHINFO_FILENAME);
            
            $fileName[]  = $name . ' ' . uniqid();
        }
        
        $config['file_name'] = $fileName;
    }
    
    return $config;
}


/**
 * Delete contract files
 * 
 * @param $files: array of file names
 * @param $type: contract category   
 */
function delete_contract_file($files, $type)
{
    $CI = & get_instance();
    $system_path = str_replace('system/', '', BASEPATH);
    
    $path = "documents/" . $type . "/";
    
    if (empty($files))
        return false;
    
    if (is_array($files))
    {
        foreach ($files as $file)
        {
            $full_path = $system_path . $path . $file['name'];
            
            @unlink($full_path);
        }
    }
    else
    {
        $full_path = $system_path . $path . $files;
        
        @unlink($full_path);
    }
}

function check_file_upload_limit()
{
    $counter = 0;
    foreach ($_FILES as $value)
    {
        if (isset($value['name']) && ! empty($value['name']))
        {
            foreach ($value['name'] as $name)
            {
                if (strlen($name))
                {
                    $counter ++;
                }
            }
        }
    }
    
    if ($counter > UPLOAD_FILE_LIMIT)
    {
        return false;
    }
    
    return true;
}

function get_contract_type($file)
{
    if (! empty($file['hotel_id']))
    {
        $type = 'hotels';
    }
    elseif (! empty($file['tour_id']))
    {
        $type = 'tours';
    }
    elseif (! empty($file['cruise_id']))
    {
        $type = 'cruises';
    }
    
    return $type;
}

function read_docx($filename){

    $striped_content = '';
    $content = '';

    $zip = zip_open($filename);

    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
    }// end while

    zip_close($zip);

    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);
    
    $striped_content = nl2br($striped_content);

    return $striped_content;
}

function is_contract_readable($filename)
{
    if (stripos($filename, '.pdf') !== false || stripos($filename, '.docx') !== false)
    {
        return true;
    }
    
    return false;
}

/**
 * Set the file name
 *
 * This function takes a filename/path as input and looks for the
 * existence of a file with the same name. If found, it will append a
 * number to the end of the filename to avoid overwriting a pre-existing file.
 *
 * @param	string
 * @param	string
 * @return	string
 */
function set_filename($path, $filename)
{
    if ($this->encrypt_name == TRUE)
    {
        mt_srand();
        $filename = md5(uniqid(mt_rand())) . $this->file_ext;
    }
    
    if (! file_exists($path . $filename))
    {
        return $filename;
    }
    
    $filename = str_replace($this->file_ext, '', $filename);
    
    $new_filename = '';
    for ($i = 1; $i < 100; $i ++)
    {
        if (! file_exists($path . $filename . $i . $this->file_ext))
        {
            $new_filename = $filename . $i . $this->file_ext;
            break;
        }
    }
    
    if ($new_filename == '')
    {
        $this->set_error('upload_bad_filename');
        return FALSE;
    }
    else
    {
        return $new_filename;
    }
}

function get_contract_size($size_kb)
{
    $size = $size_kb . 'Kb';
    if ($size_kb > 1024)
    {
        $size = round($size_kb / 1024, 1) . ' Mb';
    }
    
    return $size;
}

?>