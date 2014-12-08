<?php

/* 
 * Author:LUYU
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Upload
{

    public $max_size = 0;
    public $max_width = 0;
    public $max_height = 0;
    public $max_filename = 0;
    public $allowed_types = "";
    public $file_temp = "";
    public $file_name = "";
    public $orig_name = "";
    public $file_type = "";
    public $file_size = "";
    public $file_ext = "";
    public $upload_path = "";
    public $overwrite = FALSE;
    public $encrypt_name = FALSE;
    public $isImage = FALSE;
    public $image_width = '';
    public $image_height = '';
    public $image_type = '';
    public $image_size_str = '';
    public $error_msg = array();
    public $mimes = array();
    public $remove_spaces = TRUE;
    public $xss_clean = FALSE;
    public $temp_prefix = "temp_file_";
    public $client_name = '';

    protected $_file_name_override = '';

    /**
     * Constructor
     *
     * @access    public
     */
    public function __construct($props = array())
    {
        if (count($props) > 0) {
            $this->initialize($props);
        }

//        log_message('debug', "Upload Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Initialize preferences
     *
     * @param    array
     * @return    void
     */
    public function initialize($config = array())
    {
        $defaults = array(
            'max_size' => 0,
            'max_width' => 0,
            'max_height' => 0,
            'max_filename' => 0,
            'allowed_types' => "",
            'file_temp' => "",
            'file_name' => "",
            'orig_name' => "",
            'file_type' => "",
            'file_size' => "",
            'file_ext' => "",
            'upload_path' => "",
            'overwrite' => FALSE,
            'encrypt_name' => FALSE,
            'isImage' => FALSE,
            'image_width' => '',
            'image_height' => '',
            'image_type' => '',
            'image_size_str' => '',
            'error_msg' => array(),
            'mimes' => array(),
            'remove_spaces' => TRUE,
            'xss_clean' => FALSE,
            'temp_prefix' => "temp_file_",
            'client_name' => ''
        );


        foreach ($defaults as $key => $val) {
            if (isset($config[$key])) {
                $method = 'set_' . $key;
                if (method_exists($this, $method)) {
                    $this->$method($config[$key]);
                } else {
                    $this->$key = $config[$key];
                }
            } else {
                $this->$key = $val;
            }
        }

        // if a file_name was provided in the config, use it instead of the user input
        // supplied file name for all uploads until initialized again
        $this->_file_name_override = $this->file_name;
    }

    // --------------------------------------------------------------------

    /**
     * Perform the file upload
     *
     * @return    bool
     */
    public function startUpload($field = 'userfile')
    {

        // 验证文件名是否正确
        if (!isset($_FILES[$field])) {
            $this->setError('upload_no_file_selected');
            return FALSE;
        }

        // 验证上传目录
        if (!$this->validatePath()) {
            // errors will already be set by validatePath() so just return FALSE
            return FALSE;
        }

        // 上传文件基本信息检测
        if (!is_uploaded_file($_FILES[$field]['tmp_name'])) {
            $error = (!isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];

            switch ($error) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $this->setError('upload_file_exceeds_limit');
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $this->setError('upload_file_exceeds_form_limit');
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $this->setError('upload_file_partial');
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $this->setError('upload_no_file_selected');
                    break;
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $this->setError('upload_no_temp_directory');
                    break;
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $this->setError('upload_unable_to_write_file');
                    break;
                case 8: // UPLOAD_ERR_EXTENSION
                    $this->setError('upload_stopped_by_extension');
                    break;
                default :
                    $this->setError('upload_no_file_selected');
                    break;
            }

            return FALSE;
        }


        // 设置全局变量
        $this->file_temp = $_FILES[$field]['tmp_name'];//文件临时目录
        $this->file_size = $_FILES[$field]['size'];//文件大小
        $this->_fileMimeType($_FILES[$field]);
        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);//文件扩展名
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->_prepFileName($_FILES[$field]['name']);//文件名
        $this->file_ext = $this->getExtension($this->file_name);//扩展名
        $this->client_name = $this->file_name;

        // 验证文件类型
        if (!$this->isAllowFileType()) {
            $this->setError('upload_invalid_filetype');
            return FALSE;
        }

        // 是否覆盖重名文件
        if ($this->_file_name_override != '') {
            $this->file_name = $this->_prepFileName($this->_file_name_override);

            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === FALSE) {
                $this->file_name .= $this->file_ext;
            } // An extension was provided, lets have it!
            else {
                $this->file_ext = $this->getExtension($this->_file_name_override);
            }

            if (!$this->isAllowFileType(TRUE)) {
                $this->setError('upload_invalid_filetype');
                return FALSE;
            }
        }

        // 将文件大小转化为KB
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size / 1024, 2);
        }

        // 限制文件大小
        if (!$this->isAllowFileSize()) {
            $this->setError('upload_invalid_filesize');
            return FALSE;
        }

        // 图片尺寸验证
        // Note: This can fail if the server has an open_basdir restriction.
        if (!$this->isAllowDimensions()) {
            $this->setError('upload_invalid_dimensions');
            return FALSE;
        }

        // Sanitize the file name for security(过滤文件名)
        $this->file_name = $this->cleanFileName($this->file_name);

        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limitFileNameLength($this->file_name, $this->max_filename);
        }

        // 将多个连续空格转为一个下划线
        if ($this->remove_spaces == TRUE) {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }

        /*
         * 重名文件加数字后缀
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;

        if ($this->overwrite == FALSE) {
            $this->file_name = $this->setFileName($this->upload_path, $this->file_name);

            if ($this->file_name === FALSE) {
                return FALSE;
            }
        }

        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file.  Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean) {
            if ($this->startXssClean() === FALSE) {
                $this->setError('upload_unable_to_write_file');
                return FALSE;
            }
        }

        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first.  If that fails
         * we'll use move_uploaded_file().  One of the two should
         * reliably work in most environments
         */
        if (!@copy($this->file_temp, $this->upload_path . $this->file_name)) {
            if (!@move_uploaded_file($this->file_temp, $this->upload_path . $this->file_name)) {
                $this->setError('upload_destination_error');
                return FALSE;
            }
        }

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        $this->setImageProperties($this->upload_path . $this->file_name);

        return TRUE;
    }

    // --------------------------------------------------------------------
    
    public function start_SliceUpload($field = 'userfile')
    {

        if (!isset($_FILES[$field])) {
            $this->setError('upload_no_file_selected');
            return FALSE;
        }

        // 路径验证
        if (!$this->validatePath()) {
            return FALSE;
        }

        // 上传结果
        if (!is_uploaded_file($_FILES[$field]['tmp_name'])) {
            $error = (!isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];

            switch ($error) {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $this->setError('upload_file_exceeds_limit');
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $this->setError('upload_file_exceeds_form_limit');
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $this->setError('upload_file_partial');
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $this->setError('upload_no_file_selected');
                    break;
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $this->setError('upload_no_temp_directory');
                    break;
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $this->setError('upload_unable_to_write_file');
                    break;
                case 8: // UPLOAD_ERR_EXTENSION
                    $this->setError('upload_stopped_by_extension');
                    break;
                default :
                    $this->setError('upload_no_file_selected');
                    break;
            }

            return FALSE;
        }


        // Set the uploaded data as class variables
        $this->file_temp = $_FILES[$field]['tmp_name'];
        $this->file_size = $_FILES[$field]['size'];
        $this->_fileMimeType($_FILES[$field]);
        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->_prepFileName($_POST['file_name']);
        $this->file_ext = $this->getExtension($this->file_name);
        $this->client_name = $this->file_name;

        // Is the file type allowed to be uploaded?
        if (!$this->isAllowFileType()) {
            $this->setError('upload_invalid_filetype');
            return FALSE;
        }

        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->_file_name_override != '') {
            $this->file_name = $this->_prepFileName($this->_file_name_override);

            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === FALSE) {
                $this->file_name .= $this->file_ext;
            } // An extension was provided, lets have it!
            else {
                $this->file_ext = $this->getExtension($this->_file_name_override);
            }

            if (!$this->isAllowFileType(TRUE)) {
                $this->setError('upload_invalid_filetype');
                return FALSE;
            }
        }

        // 大小转换为M
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size / 1024, 2);
        }

        // Is the file size within the allowed maximum?
        if (!$this->isAllowFileSize()) {
            $this->setError('upload_invalid_filesize');
            return FALSE;
        }

        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basdir restriction.
        if (!$this->isAllowDimensions()) {
            $this->setError('upload_invalid_dimensions');
            return FALSE;
        }

        // Sanitize the file name for security
        $this->file_name = $this->cleanFileName($this->file_name);

        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limitFileNameLength($this->file_name, $this->max_filename);
        }

        // Remove white spaces in the name
        if ($this->remove_spaces == TRUE) {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }

        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;

        if ($this->overwrite == FALSE) {
            $this->file_name = $this->setFileName($this->upload_path, $this->file_name);

            if ($this->file_name === FALSE) {
                return FALSE;
            }
        }

        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file.  Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean) {
            if ($this->startXssClean() === FALSE) {
                $this->setError('upload_unable_to_write_file');
                return FALSE;
            }
        }

        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first.  If that fails
         * we'll use move_uploaded_file().  One of the two should
         * reliably work in most environments
         */
        if (!@copy($this->file_temp, $this->upload_path . $this->file_name)) {
            if (!@move_uploaded_file($this->file_temp, $this->upload_path . $this->file_name)) {
                $this->setError('upload_destination_error');
                return FALSE;
            }
        }

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        $this->setImageProperties($this->upload_path . $this->file_name);

        return TRUE;
    }
    // --------------------------------------------------------------------

    /**
     * Finalized Data Array
     *
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @return    array
     */
    public function data()
    {
        return array(
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_path' => $this->upload_path,
            'full_path' => $this->upload_path . $this->file_name,
            'raw_name' => str_replace($this->file_ext, '', $this->file_name),
            'orig_name' => $this->orig_name,
            'client_name' => $this->client_name,
            'file_ext' => $this->file_ext,
            'file_size' => $this->file_size,
            'isImage' => $this->isImage(),
            'image_width' => $this->image_width,
            'image_height' => $this->image_height,
            'image_type' => $this->image_type,
            'image_size_str' => $this->image_size_str,
        );
    }

    // --------------------------------------------------------------------

    /**
     * Set Upload Path
     *
     * @param    string
     * @return    void
     */
    public function setUploadPath($path)
    {
        // Make sure it has a trailing slash
        $this->upload_path = rtrim($path, '/') . '/';
    }

    // --------------------------------------------------------------------

    /**
     * Set the file name
     *
     * This function takes a filename/path as input and looks for the
     * existence of a file with the same name. If found, it will append a
     * number to the end of the filename to avoid overwriting a pre-existing file.
     *
     * @param    string
     * @param    string
     * @return    string
     */
    public function setFileName($path, $filename)
    {
        if ($this->encrypt_name == TRUE) {
            mt_srand();
            $filename = md5(uniqid(mt_rand())) . $this->file_ext;
        }

        if (!file_exists($path . $filename)) {
            return $filename;
        }

        $filename = str_replace($this->file_ext, '', $filename);

        $new_filename = '';
        for ($i = 1; $i < 100; $i++) {
            if (!file_exists($path . $filename . $i . $this->file_ext)) {
                $new_filename = $filename . $i . $this->file_ext;
                break;
            }
        }

        if ($new_filename == '') {
            $this->setError('upload_bad_filename');
            return FALSE;
        } else {
            return $new_filename;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum File Size
     *
     * @param    integer
     * @return    void
     */
    public function setMaxFileSize($n)
    {
        $this->max_size = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum File Name Length
     *
     * @param    integer
     * @return    void
     */
    public function setMaxFileName($n)
    {
        $this->max_filename = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum Image Width
     *
     * @param    integer
     * @return    void
     */
    public function setMaxWidth($n)
    {
        $this->max_width = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Maximum Image Height
     *
     * @param    integer
     * @return    void
     */
    public function setMaxHeight($n)
    {
        $this->max_height = ((int)$n < 0) ? 0 : (int)$n;
    }

    // --------------------------------------------------------------------

    /**
     * Set Allowed File Types
     *
     * @param    string
     * @return    void
     */
    public function setAllowType($types)
    {
        if (!is_array($types) && $types == '*') {
            $this->allowed_types = '*';
            return;
        }
        $this->allowed_types = explode('|', $types);
    }

    // --------------------------------------------------------------------

    /**
     * Set Image Properties
     *
     * Uses GD to determine the width/height/type of image
     *
     * @param    string
     * @return    void
     */
    public function setImageProperties($path = '')
    {
        if (!$this->isImage()) {
            return;
        }

        if (function_exists('getimagesize')) {
            if (FALSE !== ($D = @getimagesize($path))) {
                $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

                $this->image_width = $D['0'];
                $this->image_height = $D['1'];
                $this->image_type = (!isset($types[$D['2']])) ? 'unknown' : $types[$D['2']];
                $this->image_size_str = $D['3']; // string containing height and width
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set XSS Clean
     *
     * Enables the XSS flag so that the file that was uploaded
     * will be run through the XSS filter.
     *
     * @param    bool
     * @return    void
     */
    public function xssClean($flag = FALSE)
    {
        $this->xss_clean = ($flag == TRUE) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate the image
     *
     * @return    bool
     */
    public function isImage()
    {
        // IE will sometimes return odd mime-types during upload, so here we just standardize all
        // jpegs or pngs to the same file type.

        $png_mimes = array('image/x-png');
        $jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');

        if (in_array($this->file_type, $png_mimes)) {
            $this->file_type = 'image/png';
        }

        if (in_array($this->file_type, $jpeg_mimes)) {
            $this->file_type = 'image/jpeg';
        }

        $img_mimes = array(
            'image/gif',
            'image/jpeg',
            'image/png',
        );

        return (in_array($this->file_type, $img_mimes, TRUE)) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Verify that the filetype is allowed
     *
     * @return    bool
     */
    public function isAllowFileType($ignore_mime = FALSE)
    {
        if ($this->allowed_types == '*') {
            return TRUE;
        }

        if (count($this->allowed_types) == 0 OR !is_array($this->allowed_types)) {
            $this->setError('upload_no_file_types');
            return FALSE;
        }

        $ext = strtolower(ltrim($this->file_ext, '.'));

        if (!in_array($ext, $this->allowed_types)) {
            return FALSE;
        }

        // Images get some additional checks
        $image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe');

        if (in_array($ext, $image_types)) {
            if (getimagesize($this->file_temp) === FALSE) {
                return FALSE;
            }
        }

        if ($ignore_mime === TRUE) {
            return TRUE;
        }

        $mime = $this->mimeType($ext);

        if (is_array($mime)) {
            if (in_array($this->file_type, $mime, TRUE)) {
                return TRUE;
            }
        } elseif ($mime == $this->file_type) {
            return TRUE;
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Verify that the file is within the allowed size
     *
     * @return    bool
     */
    public function isAllowFileSize()
    {
        if ($this->max_size != 0 AND $this->file_size > $this->max_size) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Verify that the image is within the allowed width/height
     *
     * @return    bool
     */
    public function isAllowDimensions()
    {
        if (!$this->isImage()) {
            return TRUE;
        }

        if (function_exists('getimagesize')) {
            $D = @getimagesize($this->file_temp);

            if ($this->max_width > 0 AND $D['0'] > $this->max_width) {
                return FALSE;
            }

            if ($this->max_height > 0 AND $D['1'] > $this->max_height) {
                return FALSE;
            }

            return TRUE;
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate Upload Path
     *
     * Verifies that it is a valid upload path with proper permissions.
     *
     *
     * @return    bool
     */
    public function validatePath()
    {
        if ($this->upload_path == '') {
            $this->setError('upload_path_is_not_set');
            return FALSE;
        }

        if (function_exists('realpath') AND @realpath($this->upload_path) !== FALSE) {
            $this->upload_path = str_replace("\\", "/", realpath($this->upload_path));
        }

        if (!is_dir($this->upload_path)) {
            $this->setError($this->upload_path . ' upload_no_filepath');

            return FALSE;
        }

        if (!$this->isReallyWritable($this->upload_path)) {
            $this->setError(realpath($this->upload_path) . ' upload_not_writable');
            return FALSE;
        }

        $this->upload_path = preg_replace("/(.+?)\/*$/", "\\1/", $this->upload_path);
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Extract the file extension
     *
     * @param    string
     * @return    string
     */
    public function getExtension($filename)
    {
        $x = explode('.', $filename);
        return '.' . end($x);
    }

    // --------------------------------------------------------------------

    /**
     * Clean the file name for security
     *
     * @param    string
     * @return    string
     */
    public function cleanFileName($filename)
    {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c", // <
            "%253c", // <
            "%3e", // >
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d" // =
        );

        $filename = str_replace($bad, '', $filename);

        return stripslashes($filename);
    }

    // --------------------------------------------------------------------

    /**
     * Limit the File Name Length
     *
     * @param    string
     * @return    string
     */
    public function limitFileNameLength($filename, $length)
    {
        if (strlen($filename) < $length) {
            return $filename;
        }

        $ext = '';
        if (strpos($filename, '.') !== FALSE) {
            $parts = explode('.', $filename);
            $ext = '.' . array_pop($parts);
            $filename = implode('.', $parts);
        }

        return substr($filename, 0, ($length - strlen($ext))) . $ext;
    }

    // --------------------------------------------------------------------

    /**
     * Runs the file through the XSS clean function
     *
     * This prevents people from embedding malicious code in their files.
     * I'm not sure that it won't negatively affect certain files in unexpected ways,
     * but so far I haven't found that it causes trouble.
     *
     * @return    void
     */
    public function startXssClean()
    {
        $file = $this->file_temp;

        if (filesize($file) == 0) {
            return FALSE;
        }

        if (function_exists('memory_get_usage') && memory_get_usage() && ini_get('memory_limit') != '') {
            $current = ini_get('memory_limit') * 1024 * 1024;

            // There was a bug/behavioural change in PHP 5.2, where numbers over one million get output
            // into scientific notation.  number_format() ensures this number is an integer
            // http://bugs.php.net/bug.php?id=43053

            $new_memory = number_format(ceil(filesize($file) + $current), 0, '.', '');

            ini_set('memory_limit', $new_memory); // When an integer is used, the value is measured in bytes. - PHP.net
        }

        // If the file being uploaded is an image, then we should have no problem with XSS attacks (in theory), but
        // IE can be fooled into mime-type detecting a malformed image as an html file, thus executing an XSS attack on anyone
        // using IE who looks at the image.  It does this by inspecting the first 255 bytes of an image.  To get around this
        // CI will itself look at the first 255 bytes of an image to determine its relative safety.  This can save a lot of
        // processor power and time if it is actually a clean image, as it will be in nearly all instances _except_ an
        // attempted XSS attack.

        if (function_exists('getimagesize') && @getimagesize($file) !== FALSE) {
            if (($file = @fopen($file, 'rb')) === FALSE) // "b" to force binary
            {
                return FALSE; // Couldn't open the file, return FALSE
            }

            $opening_bytes = fread($file, 256);
            fclose($file);

            // These are known to throw IE into mime-type detection chaos
            // <a, <body, <head, <html, <img, <plaintext, <pre, <script, <table, <title
            // title is basically just in SVG, but we filter it anyhow

            if (!preg_match('/<(a|body|head|html|img|plaintext|pre|script|table|title)[\s>]/i', $opening_bytes)) {
                return TRUE; // its an image, no "triggers" detected in the first 256 bytes, we're good
            } else {
                return FALSE;
            }
        }

        if (($data = @file_get_contents($file)) === FALSE) {
            return FALSE;
        }

        $CI =& get_instance();
        return $CI->security->xss_clean($data, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * Set an error message
     *
     * @param    string
     * @return    void
     */
    public function setError($msg)
    {
//        $CI =& get_instance();
//        $CI->lang->load('upload');

        if (is_array($msg)) {
            foreach ($msg as $val) {
//                $msg = ($CI->lang->line($val) == FALSE) ? $val : $CI->lang->line($val);
                $this->error_msg[] = $msg;
//                log_message('error', $msg);
            }
        } else {
//            $msg = ($CI->lang->line($msg) == FALSE) ? $msg : $CI->lang->line($msg);
            $this->error_msg[] = $msg;
//            log_message('error', $msg);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Display the error message
     *
     * @param    string
     * @param    string
     * @return    string
     */
    public function displayErrors($open = '<p>', $close = '</p>')
    {
        $str = '';
        foreach ($this->error_msg as $val) {
            $str .= $open . $val . $close;
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * List of Mime Types
     *
     * This is a list of mime types.  We use it to validate
     * the "allowed types" set by the developer
     *
     * @param    string
     * @return    string
     */
    public function mimeType($mime)
    {
//        global $mimes;
//
//        if (count($this->mimes) == 0)
//        {
//            if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
//            {
//                include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php');
//            }
//            elseif (is_file(APPPATH.'config/mimes.php'))
//            {
//                include(APPPATH.'config//mimes.php');
//            }
//            else
//            {
//                return FALSE;
//            }
//
//            $this->mimes = $mimes;
//            unset($mimes);
//        }
//
//        return ( ! isset($this->mimes[$mime])) ? FALSE : $this->mimes[$mime];
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Prep Filename
     *
     * Prevents possible script execution from Apache's handling of files multiple extensions
     * http://httpd.apache.org/docs/1.3/mod/mod_mime.html#multipleext
     *
     * @param    string
     * @return    string
     */
    protected function _prepFileName($filename)
    {
        if (strpos($filename, '.') === FALSE OR $this->allowed_types == '*') {
            return $filename;
        }

        $parts = explode('.', $filename);
        $ext = array_pop($parts);
        $filename = array_shift($parts);

        foreach ($parts as $part) {
            if (!in_array(strtolower($part), $this->allowed_types) OR $this->mimeType(strtolower($part)) === FALSE) {
                $filename .= '.' . $part . '_';
            } else {
                $filename .= '.' . $part;
            }
        }

        $filename .= '.' . $ext;

        return $filename;
    }

    // --------------------------------------------------------------------

    /**
     * File MIME type
     *
     * Detects the (actual) MIME type of the uploaded file, if possible.
     * The input array is expected to be $_FILES[$field]
     *
     * @param    array
     * @return    void
     */
    protected function _fileMimeType($file)
    {
        // We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
        $regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

        /* Fileinfo extension - most reliable method
         *
         * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
         * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
         */
        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME);
            if (is_resource($finfo)) // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
            {
                $mime = @finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                /* According to the comments section of the PHP manual page,
                 * it is possible that this function returns an empty string
                 * for some files (e.g. if they don't exist in the magic MIME database)
                 */
                if (is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $this->file_type = $matches[1];
                    return;
                }
            }
        }

        /* This is an ugly hack, but UNIX-type systems provide a "native" way to detect the file type,
         * which is still more secure than depending on the value of $_FILES[$field]['type'], and as it
         * was reported in issue #750 (https://github.com/EllisLab/CodeIgniter/issues/750) - it's better
         * than mime_content_type() as well, hence the attempts to try calling the command line with
         * three different functions.
         *
         * Notes:
         *    - the DIRECTORY_SEPARATOR comparison ensures that we're not on a Windows system
         *    - many system admins would disable the exec(), shell_exec(), popen() and similar functions
         *      due to security concerns, hence the function_exists() checks
         */
        if (DIRECTORY_SEPARATOR !== '\\') {
            $cmd = 'file --brief --mime ' . escapeshellarg($file['tmp_name']) . ' 2>&1';

            if (function_exists('exec')) {
                /* This might look confusing, as $mime is being populated with all of the output when set in the second parameter.
                 * However, we only neeed the last line, which is the actual return value of exec(), and as such - it overwrites
                 * anything that could already be set for $mime previously. This effectively makes the second parameter a dummy
                 * value, which is only put to allow us to get the return status code.
                 */
                $mime = @exec($cmd, $mime, $return_status);
                if ($return_status === 0 && is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $this->file_type = $matches[1];
                    return;
                }
            }

            if ((bool)@ini_get('safe_mode') === FALSE && function_exists('shell_exec')) {
                $mime = @shell_exec($cmd);
                if (strlen($mime) > 0) {
                    $mime = explode("\n", trim($mime));
                    if (preg_match($regexp, $mime[(count($mime) - 1)], $matches)) {
                        $this->file_type = $matches[1];
                        return;
                    }
                }
            }

            if (function_exists('popen')) {
                $proc = @popen($cmd, 'r');
                if (is_resource($proc)) {
                    $mime = @fread($proc, 512);
                    @pclose($proc);
                    if ($mime !== FALSE) {
                        $mime = explode("\n", trim($mime));
                        if (preg_match($regexp, $mime[(count($mime) - 1)], $matches)) {
                            $this->file_type = $matches[1];
                            return;
                        }
                    }
                }
            }
        }

        // Fall back to the deprecated mime_content_type(), if available (still better than $_FILES[$field]['type'])
        if (function_exists('mime_content_type')) {
            $this->file_type = @mime_content_type($file['tmp_name']);
            if (strlen($this->file_type) > 0) // It's possible that mime_content_type() returns FALSE or an empty string
            {
                return;
            }
        }

        $this->file_type = $file['type'];
    }


    public function isReallyWritable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
//        if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
//        {
//            return is_writable($file);
//        }

        // For windows servers and safe_mode "on" installations we'll actually
        // write a file then read it.  Bah...
//        if (is_dir($file))
//        {
//            $file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));
//
//            if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
//            {
//                return FALSE;
//            }
//
//            fclose($fp);
//            @chmod($file, DIR_WRITE_MODE);
//            @unlink($file);
//            return TRUE;
//        }
//        elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
//        {
//            return FALSE;
//        }

//        fclose($fp);
        return TRUE;
    }
}