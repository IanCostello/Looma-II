<html>
<head>
  <title> Looma Content Nav </title>

  <!-- JQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <link href="css/looma-contentNav-newDesign.css" type="text/css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid">
    <div class="row navbar">
      <!-- Logo and Title -->
      <div class="row heading">
        <div class="col-sm-2">
          <img class="img-responsive logo" src="images/logos/LoomaLogoTransparentTrimmed.png" alt="Looma Logo"</img>
        </div>
        <div class="col-sm-6">
          <div class="title">Content Navigation: Adding Activities</div>
        </div>
        <div class="col-sm-3"></div>
      </div>
      <!-- Search and Nav-->
      <div class="row search">
        <div class="centerVert">
          <div class="col-md-6">
            <form role="form">
              <div class="form-group">
                <input type="text" id="searchBar" class="form-control" placeholder="Search Activities" size="30" onkeyup="search(this.value, false, 0)">
              </div>
            </form>
          </div>
          <div class="col-md-1">
            <input id ="videosCheck" type="checkbox" name="fileType" value="videos" checked> Videos<br>
          </div>
          <div class="col-md-1">
            <input id = "webpagesCheck" type="checkbox" name="fileType" value="webpages" checked> Webpages<br>
          </div>
          <div class="col-md-1">
            <input id = "audioCheck" type="checkbox" name="fileType" value="audio" checked> Audio<br>
          </div>
          <div class="col-md-1">
            <input id = "imagesCheck" type="checkbox" name="fileType" value="images" checked> Images<br>
          </div>
        </div>
      </div>
    </div>
    <!-- results -->
    <div class="row">
      <div class="col-sm-6 results" id="resultsArea"></div>
      <div class="col-sm-6">
        <div class="row preview" id="preview">
        </div>
        <div class="row edit">
          Title: <input id="titleInput" type="text" name="firstname" value=""> <br><br>
          <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#contentNavModal">Location To Add To</button><br>
          <input id="inputButton" type="submit" value="Submit">
          <div id="outputField"></div>
        </div>
      </div>
    </div>


    <!-- Send Modal Last -->
    <div id="contentNavModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Location To Add To</h4>
          </div>
          <div class="modal-body">
            <div id="classSelect"> <?php include 'looma-contentNav-classNav.php' ?> </div>
            <div id="lessonSelect"> </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Init Script Last To Improve Load Time-->
  <script src="js/looma-contentNav.js"></script>
</body>
</html>
