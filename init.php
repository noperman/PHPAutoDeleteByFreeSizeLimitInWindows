<?php
/**
 * Author: P@trick
 * Date: 2020-03-21
 * Time: 11:52 AM
 */

/** Set drive you need to auto delete */
$disk = disk_total_space("C:");
$free = disk_free_space("C:");
/** 1 Gb = 1073741824 Kb */
$gb = 1073741824;

$size = (integer)round($disk/$gb);
$freesize = (integer)round($free/$gb);

$sparator = "\n=============================\n";
echo "Free space :".$freesize." Gb of".$size." Gb".$sparator;

/** Set limit free size variable and conditiion */
$NeededSize = 150;
if($freesize < $NeededSize){
    /** Set directory you need to auto delete */
    $dir = "/dirname/";
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                /** Set except file */
                if ($file != "." && $file != ".." && $file != ".tmp.drivedownload" && $file != "desktop.ini") {
                    $files[filemtime($dir.$file)] = $file;
                }
            }
            closedir($dh);

            ksort($files);            
            $DeletedSize = 0;
            foreach($files as $file) {
                $lastModified = date('F d Y, H:i:s',filemtime($dir.$file));
                /** 
                 * Set extention file that you will able to delete 
                 * For Ex : .bak
                */
                if(strlen($file)-strpos($file,".bak")== 4){
                    $DeletedSize = $DeletedSize + (integer)filesize($dir.$file);

                    unlink($dir.$file);

                    echo $file." Created at ".$lastModified." =====> Deleted! \n";
                    if((integer)round($DeletedSize/$gb) > $NeededSize){
                        break;
                    }
                }
            }
            echo "Deleted Size : ".(integer)round($DeletedSize/$gb)." Gb of".$NeededSize." Gb needed!";
        }
    }
    echo $sparator;
}

echo "Complete!".$sparator;
?>