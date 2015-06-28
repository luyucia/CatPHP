<?php
/**
* 
*/
class Zip
{
	private $zip;
	private $res;

	function __construct($filename,$flag)
	{
		$this->zip = new ZipArchive;
        $this->res = $this->zip->open($filename,$flag);
	}

	public function addContent($content,$localname)
	{
		try {
			$this->zip->addFromString($localname, $content);
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function addFile($filename,$localname=false)
	{
		if (is_dir($filename)) {
			if ($localname!=false) {
				$this->zip->addEmptyDir($localname); 
			}else{
				$this->zip->addEmptyDir($filename); 
			}
		    $nodes = glob($filename . '/*'); 
		    foreach ($nodes as $node) { 
		        if (is_dir($node)) { 
		            $this->addFile($node); 
		        } else if (is_file($node))  { 
		        	$localfilename = substr($node, strpos($node, $localname));
		            $this->zip->addFile($node,$localfilename); 
		        } 
		    } 
		}else{
			$this->zip->addFile($filename);
		}
	}

	public function close()
	{
		$this->zip->close();
	}
}

?>
