<?php
$disk = disk_total_space("C:");
$free = disk_free_space("C:");
$gb = 1073741824;

$size = (integer)round($disk/$gb);
$freesize = (integer)round($free/$gb);

$sparator = "\n=============================\n";
echo "Free space :".$freesize." Gb of".$size." Gb".$sparator;

$NeededSize = 150;
if($freesize < $NeededSize){
    $dir = "/backup/daily backup/";
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                // echo $file.$sparator;
                if ($file != "." && $file != ".." && $file != ".tmp.drivedownload" && $file != "desktop.ini") {
                    // echo $file.$sparator;
                    // $files[filemtime($dir.$file)] = $file;
                    $files[] = array('file' => $file, 'time' => filemtime($dir.$file));
                }
            }
            closedir($dh);
            
            uasort($files, function($file1, $file2) {
                if ( $file1['time'] == $file2['time'] )
                return 0;
                return $file1['time'] < $file2['time'] ? -1 : 1;
            });
                       
            $DeletedSize = 0;
            $no = 1;
            foreach($files as $file) {
                $lastModified = date('F d Y, H:i:s',filemtime($dir.$file['file']));
                if(strlen($file['file'])-strpos($file['file'],".bak")== 4){
                    $DeletedSize = $DeletedSize + (integer)filesize($dir.$file['file']);

                    unlink($dir.$file['file']);
                    // echo $no.' '.$dir.$file['file']."\n";

                    echo $no." ".$file['file']." Created at ".$lastModified." =====> Deleted! \n";
                    if(($freesize + (integer)round($DeletedSize/$gb)) >= $NeededSize){
                        break;
                    }
                }
                $no++;
            }
            echo "Deleted Size : ".(integer)round($DeletedSize/$gb)." Gb of".$NeededSize." Gb needed!";
        }
    }
    echo $sparator;
}

echo "Complete!".$sparator;
?>
