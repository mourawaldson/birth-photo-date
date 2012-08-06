<?php
header("Content-type: text/html; charset=utf-8");

date_default_timezone_set('America/Recife');

$dir = '/Volumes/PERSONAL/Fotos';

function format($value) {
	return str_pad($value,2,'0',STR_PAD_LEFT);
}

function ListFolder($path) {
    //using the opendir function
    $dir_handle = @opendir($path) or die("Unable to open $path");
    
    //Leave only the lastest folder name
    $dirname = end(explode("/", $path));
    
    //display the target folder.
    echo ("<li>$dirname\n");
    echo "<ul>\n";
    while (false !== ($file = readdir($dir_handle))) 
    {
        if($file!="." && $file!="..")
        {
            if (is_dir($path."/".$file))
            {
                //Display a list of sub folders.
                ListFolder($path."/".$file);
            }
            else
            {
                //Display a list of files.
                $created_date = getBirthDate($path.'/'.$file);
                echo "<li>$file - ".$created_date."</li>";
            }
        }
    }
    echo "</ul>\n";
    echo "</li>\n";
    
    //closing the directory
    closedir($dir_handle);
}

function getBirthDate($file) {
	if ($handle = popen('stat -f %B ' . escapeshellarg($file), 'r')) {
	    $btime = trim(fread($handle, 100));
	    $birth_date = date("d/m/Y H:i:s", $btime);
	    pclose($handle);
	}

	return $birth_date;
}

function createFolders($path) {
	for ($year=2012;$year>=2000;$year--) {
	 	for ($month=12;$month>=1;$month--) {
	 		$number_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
	 		for ($day=$number_of_days;$day>=1;$day--) { 
	 			@mkdir($dir.'/'.$year.'/'.format($month).'/'.format($day), 0777, true);
	 		}
	 	}
	}
}

ListFolder($dir);