<!--
Name: Ian Costello
Email: ian.costello@menloschool.org
Owner: VillageTech Solutions (villagetechsolutions.org)
Date: 2016 6
Revision: Looma 2.0.0
File: looma-contentPreview.php
Description: Loads a preview of a page given an id
-->
<?php
  include 'includes/mongo-connect.php';

  //Get chapter lookup
  $db_id = $_GET['dbid'];

  if ($db_id != null) {
    //Create Query For Activity
    $query = array('_id' => new MongoId($db_id));
    //Only Get File Type and Filename
    $projection = array(
              'ft' => 1,
              'fn' => 1,
              'fp' => 1,
              );

    $activity = $activities_collection->findOne($query, $projection);

    $ft = $activity['ft'];
    $fn = $activity['fn'];
    $fp = $activity['fp'];
    load_from_relative_path($ft, $fn, $fp);
  } else {
    $ft = $_GET['ft'];
    $fn = $_GET['fn'];
    $fp = $_GET['fp'];
    load_from_filePath($ft, $fp);
  }

  function load_from_filePath($ft, $fp) {
    switch ($ft) {
      case "video":
        video($fp, $ft, "$activities_collection");
        break;
      case "mp4":
        video($fp, $ft, "");
        break;
      case "mov":
        video($fp, $ft, "");
        break;
      case "image":
        image($fp, $ft, "");
        break;
      case "jpg":
        image($fp, $ft, "");
        break;
      case "png":
        image($fp, $ft, "");
        break;
      case "gif":
        image($fp, $ft, "");
        break;
      case "audio":
        audio($fp, $ft, "");
        break;
      case "mp3":
        audio($fp, $ft, "");
        break;
      case "pdf":
        pdf ($fp, $ft, "");
        break;
      case "EP":
        epaath($fp, $ft, "");
        break;
      case "html":
        webpage("", $fp);
        break;
      case "htm":
        webpage("", $fp);
        break;
      case "php":
        webpage("", $fp);
        break;
      case "loomaPage":
        webpage("", $fp);
        break;
      default:
        echo "<h4> The filetype <b> $ft </b> in <b> $fn </b> is not supported! </h4>";
        break;
    }
  }

  function load_from_relative_path($ft, $fn, $fp) {
    switch ($ft) {
      case "video":
        video($fn, $ft, "content/videos/");
        break;
      case "mp4":
        video($fn, $ft, "content/videos/");
        break;
      case "mov":
        video($fn, $ft, "content/videos/");
        break;
      case "image":
        image($fn, $ft, "content/pictures/");
        break;
      case "jpg":
        image($fn, $ft, "content/pictures/");
        break;
      case "png":
        image($fn, $ft, "content/pictures/");
        break;
      case "gif":
        image($fn, $ft, "content/pictures/");
        break;
      case "audio":
        audio($fn, $ft, "content/audio/");
        break;
      case "mp3":
        audio($fn, $ft, "content/audio/");
        break;
      case "pdf":
        pdf ($fn, $ft, "content/pdfs/");
        break;
      case "EP":
        epaath($fn, $ft, "content/epaath/activities/$fp");
        break;
      case "html":
        webpage("content/webpages/", $fp);
        break;
      case "htm":
        webpage("content/webpages/", $fp);
        break;
      case "php":
        webpage("content/webpages/", $fp);
        break;
      case "loomaPage":
        webpage("", $fp);
        break;
      default:
        echo "unknown filetype " . $ft . "in $activities_collection.php";
        break;
    }
  }
  function video($fn, $ft, $fp) {
    $fileLocation = $fp . $fn;
    $source = '<source src="' . $fileLocation . '" type="video/' . $ft . '">';
    echo '<video width="100%" height="90%" controls>';
      echo $source;
    echo '</video>';
    echo '<input type="button" class="newTabButton" onclick="window.open(\'' .  $fileLocation . '\')" value="Open In New Tab">';
  }

  function image($fn, $ft, $fp) {
    $fileLocation = $fp . $fn;
    $source = '<img src="' . $fileLocation . '" style="height:90%;width:90%;">';
    echo $source;
    echo '<br>';
    echo '<input type="button" class="newTabButton" onclick="window.open(\'' .  $fileLocation . '\')" value="Open In New Tab">';
  }

  function audio($fn, $ft, $fp) {
    $fileLocation = $fp . $fn;
    $source = '<source src="' . $fileLocation . '" type="audio/' . $ft . '">';
    echo '<audio controls>';
      echo $source;
    echo '</audio>';
  }

  function pdf($fn, $ft, $fp) {
    $fileLocation = $fp . $fn;
    $source = '<iframe src="' . $fileLocation . '" width="100%" height="90%">';
    echo $source;
    echo '<input type="button" class="newTabButton" onclick="window.open(\'' .  $fileLocation . '\')" value="Open In New Tab">';
  }

  function epaath($fn, $ft, $fp) {
    $fileLocation;
    if ($fp != "") {
      $fileLocation = $fp . $fn . "/start.html";
    } else {
      $fileLocation = $fn;
    }
    $source = '<iframe id="epaathFrame" src="' . $fileLocation . '" width="100%" height="90%"></iframe>';
    echo $source;
    echo '<input type="button" class="newTabButton" onclick="window.open(\'' .  $fileLocation . '\')" value="Open In New Tab">';
  }

  function webpage($fileRoot, $filePath) {
    $fileLocation = $fileRoot . $filePath;
    $source = '<iframe id="epaathFrame" src="' . $fileLocation . '" width="100%" height="90%"></iframe>';
    echo $source;
    echo '<input type="button" class="newTabButton" onclick="window.open(\'' .  $fileLocation . '\')" value="Open In New Tab">';
  }
  
?>
