/*
Name: Ian
Email: ian.costello@menloschool.org
Owner: VillageTech Solutions (villagetechsolutions.org)
Date: 2016 6
Revision: Looma 2.0.0
File: looma-contentNav.js
Description:  Javascript for looma-contentNav.php
*/
var resultsShown;

$(document).ready(function(){
  var page = 0;
  var lvlSelected;
  var classSelected;
  var chapter_id;
  var selectedId;
  var currSelectedFile;
  var currSelectedFileType;
  var inSearch = true;

  //Toggle Between activity adder and results
  $(document).on('click', '#modeSelector', function(){
    if (inSearch) {
      $("#headingTitle").text("Content Navigation: Adding Activities");
      $("#modeSelector").text("Editor");
      openContentNav("");
      inSearch = false;
    } else {
      $("#headingTitle").text("Content Navigation: Assigning Activities");
      $("#modeSelector").text("Adder");
      search($('#searchBar').val(), false, page);
      inSearch = true;
    }
  });

  $(document).on('click', '.directoryButton', function(){
    var linksTo = $(this).attr("linksTo");
    //If User Choose To Back
    if ($(this).attr("shouldGoToParent") == "true") {
      linksTo = linksTo.substr(0, linksTo.length-1);
      linksTo = linksTo.substr(0, linksTo.lastIndexOf("/")+1);
    }
    openContentNav(linksTo);
  });

  $(document).on('click', '.fileButton', function(){
    var filePath = "content/" + $(this).attr("linksTo");
    currSelectedFile = filePath.substring(filePath.lastIndexOf("/") + 1);
    currSelectedFileType = filePath.substring(filePath.lastIndexOf(".") + 1);

    //Update and load info
    loadPreviewWithParams(filePath, currSelectedFile, currSelectedFileType);
    $('#titleInput').val("");
    loadChapterId(currSelectedFile);
  });

  //When a chapter/lesson is selected set the database id and close the modal
  $(document).on('click', '.individualResult', function(){
    selectedId = $(this).attr('dbid');
    loadPreview(selectedId);
    $('#titleInput').val($(this).attr('title'));
    var currClass = $(this).attr('chid');
    if (currClass == "") {
      currClass = "Not Assigned";
    }
    $('#currClass').text(currClass);
  });

  //On Level Select Update the Selected level
  $('.lvlSelect').click(function(){
    $('.lvlSelect').removeClass('active');
    $(this).addClass('active');
    lvlSelected = this.id;
    if (classSelected != undefined) {
      loadPage(lvlSelected, classSelected)
    }
  });

  //On Class Select load the correct chapters
  $('.classSelect').click(function(){
    $('.classSelect').removeClass('active');
    $(this).addClass('active');
    classSelected = this.id
    if (lvlSelected != undefined) {
      loadPage(lvlSelected, classSelected);
    }
  });

  //On Class Select load chapters titles
  $(document).on('click', '#inputButton', function() {
    var titleText = $('#titleInput').val();

    //Make sure user selected activity and destination
    if (titleText == undefined) {
      alert("Please Select An Activity First!")
    } else if (chapter_id == undefined) {
      alert("Please Select A Destination To Insert!")
    } else {
      $('#confirmationModal').modal('show');
      $('#activityName').text(titleText);
      $('#locationName').text(chapter_id);
      if ($('#currClass').text() != "Not Assigned") {
        $('#confirmButton').removeClass('hidden');
      }
    }
  });

  //When a chapter/lesson is selected set the database id and close the modal
  $(document).on('click', 'button.lessonButton', function(){
    var button = event.target;
    chapter_id = this.getAttribute('data-ch');
    $('#contentNavModal').modal('toggle');
  });

  //If They Choose To Unassign An Activity
  $(document).on('click', '#unassignButton', function() {
    chapter_id = "Not Assigned";
    $('#contentNavModal').modal('toggle');
  });

  //Confirm placement of activity
  $(document).on('click', '#overrideButton', function() {
    var titleText = $('#titleInput').val();

    if (inSearch) {
      updateDatabase(selectedId, chapter_id, titleText, false);

      //Update Info On Page
      search($('#searchBar').val(), false, page);
    } else {
      insertToDatabase(chapter_id, titleText, currSelectedFile, currSelectedFileType);
    }

    $('#currClass').text(chapter_id);
    $('#confirmationModal').modal('hide');
    $('#confirmButton').addClass('hidden');
  });

  //Confirm placement of activity
  $(document).on('click', '#confirmButton', function() {
    var titleText = $('#titleInput').val();
    updateDatabase(selectedId, chapter_id, titleText, true);
    $('#confirmationModal').modal('hide');
    $('#confirmButton').addClass('hidden');

    //Update Info On Page
    search($('#searchBar').val(), false, page);
    $('#currClass').text(chapter_id);
  });

  //When The Bottom Of Results is hit load more
  $('#resultsArea').on('scroll', function() {
    //If There Are More Results To Show
    if (inSearch && resultsShown%10 == 0) {
      //If They've Hit Bottom of Div
      if($(this)[0].scrollHeight <= $(this).scrollTop() + $(this).innerHeight()) {
        page +=1;
        search($('#searchBar').val(), true, page);
      }
    }
  })

  //When user filters out a type update
  $(':checkbox').change(function() {
    search($('#searchBar').val(), false, page);
   });

  //On page load, load default results
  search("", false, page);
});

/*Search Function
Search: String To Search For
Append: Whether To Append to Exisiting Results or Replace
Page: What page of results to load
*/
function search(search, append, page) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      if (!append) {
        document.getElementById("resultsArea").innerHTML = xmlhttp.responseText;
      } else {
        document.getElementById("resultsArea").innerHTML += xmlhttp.responseText;
      }

      resultsShown = $("#resultsArea div").length;
    }
  };

  //Get Query Options
  var videosChecked = document.getElementById("videosChecked").checked;
  var webpagesChecked = document.getElementById("webpagesChecked").checked;
  var audioChecked = document.getElementById("audioChecked").checked;
  var imagesChecked = document.getElementById("imagesChecked").checked;
  var pdsChecked = document.getElementById("pdfsChecked").checked;
  if (!append) {
    page = 0;
  }

  //Build Search With Results
  var toLoad = "looma-contentNav-results.php?q=" + search + "&page=" + page;
  toLoad = toLoad + "&videosChecked=" + videosChecked;
  toLoad = toLoad + "&webpagesChecked=" + webpagesChecked;
  toLoad = toLoad + "&audioChecked=" + audioChecked;
  toLoad = toLoad + "&imagesChecked=" + imagesChecked;
  toLoad = toLoad + "&pdfsChecked=" + pdsChecked;

  //Send Request
  xmlhttp.open("GET", toLoad, true);
  xmlhttp.send();
} //END OF SEARCH

function openContentNav(toAppend) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById("resultsArea").innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "looma-contentNav-fileNav.php?q="+ toAppend, true);
  xmlhttp.send();
}

//Load A Content Preview Given a mongo ID
function loadPreview(db_id) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById("preview").innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "looma-contentPreview.php?" + "dbid=" + db_id, true);
  xmlhttp.send();
}

//Load A Content Preiew Given Relevant Info
function loadPreviewWithParams(filePath, fileName, fileType) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById("preview").innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "looma-contentPreview.php?" + "fp=" + filePath + "&fn=" + fileName + "&ft=" + fileType , true);
  xmlhttp.send();
}

function loadChapterId(fileName) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      var response = xmlhttp.responseText;
      if (response == "") {
        response = "Not Assigned";
      }
      document.getElementById("currClass").innerHTML = response;
    }
  };
  xmlhttp.open("GET", "looma-contentNav-databaseUpdate.php?cmd=getChapterId&fn="+fileName);
  xmlhttp.send();
}

function insertToDatabase(ch_id, title, fileName, fileType) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", "looma-contentNav-databaseUpdate.php?cmd=insert&title=" + title + "&chid=" + ch_id + "&fn=" + fileName + "&ft=" + fileType);
  xmlhttp.send();
}

//Update A Datebase ID given an id, ch id, and title
function updateDatabase(db_id, ch_id, title, isDuplicate) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", "looma-contentNav-databaseUpdate.php?cmd=update&title=" + title + "&dbid=" + db_id + "&chid=" + ch_id + "&duplicate=" + isDuplicate);
  xmlhttp.send();
}

//Loads all the chapters given a set className and subjectName
function loadPage(className, subjectName) {
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById("lessonSelect").innerHTML = xmlhttp.responseText;
    }
  };
  xmlhttp.open("GET", "looma-contentNav-lessonSelect.php?" + "class=" + className +
  "&subject=" +
  subjectName, true);
  xmlhttp.send();
}
