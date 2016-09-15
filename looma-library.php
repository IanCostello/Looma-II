<!doctype html>
<!--
Name: Skip
Email: skip@stritter.com
Owner: VillageTech Solutions (villagetechsolutions.org)
Date: 2015 10
Revision: Looma 2.0.0
File: looma-library.php
Description:  displays and navigates content folders for Looma 2
-->

<?php $page_title = 'Looma Library';
        require ('includes/header.php');
        require ('includes/mongo-connect.php');
        // load: function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
        require ('includes/activity-button.php');
?>
    </head>

    <body>
    <div id="main-container-horizontal" class="scroll">

<?php
    function folderName ($path) {
        // strip trailing '/' then get the last dir name, by finding the remaining last '/' and substr'ing
         $a = explode("/", $path);
         return $a[count($a) - 2];
    };  //end FOLDERNAME()
    function isEpaath($fp) {
        //echo "<br>DEBUG: in isEpaath, FP is " . $fp . " Substr is " . mb_substr($fp, -7, 7);
        if (mb_substr($fp, -7, 7) == "epaath/")
             return true;
        else return false;
    }; //end function isEpaath
    function isHTML($fp) {
        //echo "DEBUG: in isHTML - fp = " . $fp . " and fileexists = " . (file_exists($fp . "/index.html")?"true":"false"). "<br>";
        if (file_exists($fp . "/index.html") && !isEpaath($fp))
             return true;
        else return false;
    };  //end function isHTML
    function thumb_image ($fp) {  //for directories, look for filename "thumbnail.png" for a thumbnail representing the contents
        if (file_exists($fp . "/thumbnail.png")) {
             return "<img src='$fp/thumbnail.png' >"; }
        else return "";
    }; //end function thumbnail
            // get filepath to use for start of DIR traversal
            //this will be  "../content/" for Looma 2 Library starting folder [to be outside of web-accessible folder structure]
    if (isset($_GET['fp'])) $path = $_GET['fp'];
    else $path = "../content/";
            //echo "<br>DEBUG: directory is:  " .  $path . "<br><br>";
    //in ..resources/epaath/ DIR skip to ..resources/epaath/Activities/
    if (isEpaath($path)) {$path = $path . "activities/"; $ep = true;}
    else   $ep = false;
    // check if this DIR contains HTML media
    //$html = isHTML($path);  //handle folders containing 'index.html' differently below
                            //should also check for 'index.php' and others?
    // DEBUG echo "at path " . $path . "folderName is " . folderName($path);
    echo "<br><h3 class='title'>"; keyword('Looma Library'); echo ":  " . folderName($path) . "</h3>";
    //  first list directories in this directory
    echo "<br><table id='dir-table'><tr>";
    $buttons = 1;
    $maxButtons = 3;
    // iterate through the files in this DIR and make buttons for each included DIR
    if ( ! $ep ) {
        //TODO: should gather all the filenames into an array and sort it, use (natcasesort() or multisort(), before making the buttons
/*************** iterate through the files in this DIR and make buttons for the DIRs ******************/
/******************************************************************************************************/
/********************************/
/**********  DIRs  **************/
/********************************/
        foreach (new DirectoryIterator($path) as $fileInfo) {
            $file =  $fileInfo->getFilename();
            //if ($file{0}  == ".") continue;  //skips ".", "..", and any ".filename" (more thorough than isDot() )
            if (($fileInfo -> isDir()) && !isHTML($path . $file) && $file[0] !== "." && ( ! file_exists($path . $file . "/hidden.txt")))
                //skips ".", "..", and any ".filename" (more thorough that isDot() )
                //skips any directory with a file named "hidden.txt"
             {
                if (file_exists($path . $file . "/images.txt")) {
                    /************************************************************************
                     * EDITED: This if statement creates a slideshow play button linking directories containing images.txt files
                     * with Looma slideshow player
                     */
                    echo "<td>
                            <a href='looma-slideshow.php?dir=$path$file'>
                                    <button class='activity zeroScroll  with-play'>
                                        <img src='images/play-slideshow-icon.png' class='img-responsive'>
                                    </button>
                                </a>";
                    echo "<a href='looma-library.php?fp=" . $path . $file .
                         "/'><button class='activity img zeroScroll beside-play'>" .
                    thumb_image($path . $file) . $file . "</button></a></td>";
                    }
                else {
                    echo "<td><a href='looma-library.php?fp=" . $path . $file .
                    "/'><button class='activity img zeroScroll'>" .
                    thumb_image($path . $file) . $file . "</button></a></td>";
                }
                $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
            };
        }; // ********** end FOREACH directory  **************
    };
/******************************************************************************************************/
/******************************************************************************************************/
    echo "</tr></table>";
    //now list files in this directory
    echo "<br><table id='file-table'><tr>";
    $buttons = 1;
    $maxButtons = 3;
    $specials = array("_", "-", ".", "/", "'");
echo "<hr>";
    // iterate through the files in this DIR and make buttons for each included FILE
    //TODO: should gather all the filenames into an array and sort it, use (natcasesort() or multisort(), before making the buttons
/*************** iterate through the files in this DIR and make buttons each of the FILES ******************/
/******************************************************************************************************/
/********************************/
/**********  FILEs  *************/
/********************************/
        //modifications for EDITED VIDEOS
        //make buttons for EDITED VIDEOS directory -- virtual folder, populated from edited_videos collection in mongoDB
        //***************************
        if($path == "../content/edited videos/") {  //populate virtual folder of EDITED VIDEOs
            //If the folder being filled is the edited videos it fills it using
            //all of the entries from the edited_videos collection in the database
            $editedVideos = $edited_videos_collection->find();
             foreach ($editedVideos as $doc) {
                    echo "<td>";
                    $dn = $doc['dn'];
                    $fn = $doc['vn'] . ".mp4"; //NOTE: BUG: assumes all videos are .mp4. should save extension with evi collection
                    $fp = $doc['vp'];
                    $ft = "evi";
                    $id = $doc['_id'];
                    //$json = $doc['JSON'];  //NOTE: this passed the full text of the edited script in the URL.
                                           // should just pass the mongo ID and have the player retrieve the script's full text
                     // change to use: function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                    makeActivityButton($ft, $fp, $fn, $dn, "", "", $id, "", "");
                    //makeEditedVideoButton($dn, $path, $ext, $file, $json);
                    echo "</td>";
                    $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
            }
        }  //end IF edited videos
        else
        //modifications for SLIDESHOW
        //***************************
        //make buttons for SLIDESHOW directory -- virtual folder, populated from SLIDESHOWS collection in mongoDB
        if($path == "../content/slideshows/") {   //populate virtual folder of SLIDESHOWs
            //If the folder being filled is the slideshow it fills it using
            //all of the entries from the slideshows collection in the database
           //echo "DEBUG number of slideshows is :" . $slideshows_collection->count();
            $slideshows = $slideshows_collection->find();
           //echo "DEBUG length of slideshows array is :" . count($slideshows);
             foreach ($slideshows as $slideshow) {
                        //echo "DEBUG   found slideshow " . $slideshow['dn'] . "<br>";
                    echo "<td>";
                    $dn = $slideshow['dn'];
                    $fn = $slideshow['fn'];
                    $fp = $slideshow['fp'];
                    $thumb = thumbnail($fn);
                      //NOTE: for now, fp and fn are concatenated in fn
                    //$path = $slideshow['fp'];
                    $ft = "slideshow";
                    $id = $slideshow['_id'];  //mongoID of the descriptor for this slideshow
                    makeActivityButton($ft, $fp, $fn, $dn, $thumb, "", $id, "", "");
                    echo "</td>";
                    $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
            } //end FOREACH slideshow
        }  //end IF slideshows
        else {
        foreach (new DirectoryIterator($path) as $fileInfo) {
            $file =  $fileInfo->getFilename();
            //echo "DEBUG     found " . $file . "<br>";
            //skip ".", "..", and any ".filename" and any filename with '_thumb' in the name
            if (($file[0]  == ".") || strpos($file, "_thumb") || $file == "thumbnail.png") continue;
            if ($fileInfo -> isFile()) {
                //insert code here to sort the filenames before sending them to client side
                // iterate thru the directory, storing valid files in an array
                /* $files = arr
                 foreach {$files[] =$file;]
                 * $files = array_sort($files);
                 * while ?? $next = array)shift($files);
                 *
                */
                //then SORT
                //then iterate thru the array  making buttons
            //this code is also in looma-activities.php - should be a FUNCTION
            echo "<td>";
            $ext = $fileInfo -> getExtension();
            $file = $fileInfo -> getFilename();
            $base = trim($fileInfo -> getBasename($ext), ".");  //$base is filename w/o the file extension
            // look in the database to see if this file has a DISPLAYNAME
                $query = array('fn' => $file);
                $projection = array('_id' => 0,
                                    'dn' => 1,
                                    );
                $activity = $activities_collection -> findOne($query, $projection);
                $dn = ($activity && array_key_exists('dn', $activity)) ? $activity['dn'] : str_replace($specials, " ", $base);
            //
            //DEBUG   echo "activity is " . $activity['dn'] . " looked up '" . $file . "' and got '" . $dn . "'";
                switch (strtolower($ext)) {
                    case "video":
                    case "mp4":
                    case "m4v":
                    case "mov":
                    case "image":
                    case "jpg":
                    case "png":
                    case "gif":
                    case "audio":
                    case "mp3":
                    case "pdf":
                    case "html":
                        // use UTILITY function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                        makeActivityButton ($ext, $path, $file, $dn, "", "", "", "", "");
                        //makeButton($file, $path, $ext, $base, $dn, $path . $base . "_thumb.jpg");
                        break;
                    default:
                        // ignore unknown filetypes
                        // echo "DEBUG: " . $fileInfo -> getFilename() . "unkown filetype in looma-library.php";
                };  //end SWITCH
                echo "</td>";
                $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
                }
                else {  //handle Epaath special case
                    if ($ep) {
                        // display an EPAATH play button
                        echo "<td>";
                        $thumb = $file . "/thumbnail.jpg";
                        $dn = 'ePaath ' . $file;
                        // use UTILITY function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                        makeActivityButton("epaath", $path, $file, $dn, $thumb, "", "", "", "");
                        //makeButton($file, $path, 'epaath', $file, 'ePaath ' . $file, $path . $file . "/thumbnail.jpg");
                        // change to use: function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                        echo "</td>";
                        $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
                    } //end if EP
               else if (isHTML($path . $file)) {
                        // display an HTML play button
                        echo "<td>";
                        $thumb = $file . "/thumbnail.jpg";
                        $dn = $file;
                        // use UTILITY function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                        makeActivityButton("html", $path, $file . "/index.html", $dn, $thumb, "", "", "", "");
                        //makeButton($file, $path, 'epaath', $file, 'ePaath ' . $file, $path . $file . "/thumbnail.jpg");
                        // change to use: function makeActivityButton($ft, $fp, $fn, $dn, $thumb, $ch_id, $mongo_id, $pg, $zoom)
                        echo "</td>";
                        $buttons++; if ($buttons > $maxButtons) {$buttons = 1; echo "</tr><tr>";};
                   } // end if HTML
                }
              } //end FOREACH file
            }
        echo "</tr></table>";
?>

    </div>

    <?php include ('includes/toolbar.php'); ?>
    <?php include ('includes/js-includes.php'); ?>
    <script src="js/looma-library.js"></script>
    </body>
