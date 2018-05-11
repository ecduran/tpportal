<?php session_start();
//print_r($_SESSION);
include_once 'templates/header.php';
?>
    <div class="container">
      <form name="formScreenID" method="post" action="../src/screenid.php">
        <p class="font-weight-bold">Please choose your ScreenID</p>
        <p class="font-italic text-danger">Please choose your ScreenID carefully. Your screenID will never be changed. 
           You will use it to refer/sponsor other people. This is your TP Portal screen name.</p>
        <p class="text-danger">Only lowercase letters (a - z), numbers and underscore ( _ ) are allowed.</p>
        
        <div>  
          <div class="form-group"> 
            <label for="screenID">ScreenID: </label> 
            <input type="text" class="form-control" name="screenID" id="screenIDInput"
            required>   
            <div id="validateScreenIDResult"></div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary" id="btn-submit">
        Submit</button>
        <a type="button" class="btn btn-secondary" href="index.php">Back</a>
      </form>
    </div>
    
<!-- <script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script> -->

<script src="scripts/jquery-3.3.1.js"></script>
<script src="scripts/js/bootstrap.min.js"></script>

<script>
$(function()
{
  $("#screenIDInput").keyup(function()
  {
    let inputStr = $("#screenIDInput").val();

    if(inputStr.length == 0)
    {
       $('#validateScreenIDResult').text("");
          return;
    } 
    else
    {
      $.get("../src/check_screenid.php?q="+inputStr, function(responseText)
            {
              $('#validateScreenIDResult').html(responseText);
            });
    }
  });

  $("#btn-submit").click(function() 
  {
    let validateScreenIDResult = $("#validateScreenIDResult p").html();
    let screenIDInput = $("#screenIDInput").val();
    let str1 = "Invalid screenID. Try again.";
    let str2 = "ScreenID is already taken.";

    if (validateScreenIDResult == str1)
    {
      alert("Invalid ScreenID. Enter correct screenID.");
      return false;
    }
    else if (validateScreenIDResult == str2)
    {
      alert("Invalid ScreenID. Enter correct screenID.");
      return false;
    }
    else if (screenIDInput == "")
    {
      alert("Invalid ScreenID. Enter correct screenID.");
      return false;
    }
  });

});

</script>
  </body>
</html>
