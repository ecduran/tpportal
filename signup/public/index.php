<?php session_start();
$_SESSION = array();
//print_r($_SESSION);
include_once 'templates/header.php';
?>
    <div class="container">
        <div class="form-group">
          <label for="formGroupUsernameInput">Sponsor screenID:</label>
          <input type="text" class="form-control" id="sponsorScreenID" placeholder="Sponsor screenID"  required>
          <div id="checkScreenIDResult"></div>
        </div>
        <div class="form-group">
          <label for="formGroupCountryResidency">Your country of residency:</label>
          <select class="form-control" id="countryResidency">
            <option value="" selected></option>
            <?php include_once "../src/countrylist_dropdown.php"; ?>
          </select> 
        </div>
        <button type="button" id="btn-submit" class="btn btn-primary">Submit</button>

    </div>

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalTitle">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form name="sponsorSignup" method="post" action="../src/sponsor_screenid.php"> <!-- start form -->
        <div class="modal-body">
<!--    <div class="row">
          <div class="col-sm">
            <p class="font-weight-bold">Your Sponsor's Name:</p>
          </div>
          <div class="col-sm">
           <input class="modal-input" type="text" id="modalSponsorName" name="sponsorName" readonly> 
          </div>
        </div>   
-->
          <div class="form-group">
            <label for="" class="font-weight-bold">Your Sponsor's Name</label>
            <input class="modal-input" type="text" id="modalSponsorName" name="sponsorName" readonly>
          </div>
<!--    <div class="row">
          <div class="col-sm">
            <p class="font-weight-bold">Your Sponsor's Company:</p>
          </div>
          <div class="col-sm">
           <input type="text" class="modal-input" id="modalSponsorCompany" name="sponsorCompany" readonly> 
          </div>
        </div>   
--> 
          <div class="form-group">
            <label for="" class="font-weight-bold">Your Sponsor's Company</label>
            <input class="modal-input" type="text" id="modalSponsorCompany" name="sponsorCompany" readonly>
          </div>               
<!--    <div class="row">
          <div class="col-sm">
            <p class="font-weight-bold">Your Sponsor's ScreenID:</p>
            <p class="small text-danger">(This will be permanent!)</p>
          </div>
          <div class="col-sm">
           <input type="text" class="modal-input" name="sponsorScreenID" id="modalSponsorScreenID" readonly> 
          </div>
        </div>  
-->  
          <div class="form-group">
            <label for="" class="font-weight-bold">Your Sponsor's ScreenID</label>
            <input class="modal-input" type="text" id="modalSponsorScreenID" name="sponsorScreenID" readonly>
          </div>         
<!--    <div class="row">
          <div class="col-sm">
            <p class="font-weight-bold">Country in which you reside:</p> 
          </div>
          <div class="col-sm">
            <input type="text" class="modal-input" name="countryName" id="modalCountryResidency" readonly> 
          </div>
        </div> 
-->
          <div class="form-group">
            <label for="" class="font-weight-bold">Country in which you reside</label>
            <input class="modal-input" type="text" id="modalCountryCodeResidency" name="countryCode" readonly>            
            <input class="modal-input" type="text" id="modalCountryResidency" name="countryName" readonly>
          </div>      
      </div> 
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="confirm-btn">Confirm</button>
      </div>
      </form> <!-- end form -->
    </div>
  </div>
</div>


<!-- 
<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
 -->
<script src="scripts/jquery-3.3.1.js"></script>
<script src="scripts/js/bootstrap.min.js"></script>

<script>
$(function()
  {
    $('#sponsorScreenID').keyup(function() 
      {
        let inputStr = $('#sponsorScreenID').val();
        if (inputStr.length == 0)
        {
          $('#checkScreenIDResult').text("");
          return;
        } 
        else
        {
          $.get("../src/check_sponsor_screenid.php?q="+inputStr, function(responseText)
            {
              $('#checkScreenIDResult').html(responseText);
            });
        }

      });

    $('#btn-submit').click(function() 
      {
        let sponsorScreenIdInput = $("#sponsorScreenID").val();
        let checkScreenIDRes = $("#checkScreenIDResult p").html();
        let countryResidencyInput = $("#countryResidency").val();
        let str = "Sponsor screenID does not exist.";

        if ( checkScreenIDRes === str )
        {
          alert( "Invalid sponsor screenID." );
        }
        else if ( sponsorScreenIdInput == ""  )
        {
          alert( "Please enter your sponsor screenID." );
          
        }
        else if ( countryResidencyInput == "")
        {
          alert( "Please enter your country." );
        }
        else 
        {
          let sponsorScreenID = $("#sponsorScreenID").val();
          let countryCodeResidency = $("#countryResidency").val();
          let countryNameResidency = $("#countryResidency option:selected").text();

          $.post("../src/getdata_to_modal.php", { screenID: sponsorScreenID },
            function ( data ) 
            {
              
              let firstName = data[0]["first_name"];
              let middleName = data[0]["middle_name"];
              let lastName = data[0]["last_name"];
              let companyName = data[0]["company_name"]; 
              
              let fullName = firstName +" "+ middleName +" "+ lastName;

              $("#modalSponsorName").val(fullName);
              $("#modalSponsorCompany").val(companyName); 
              $("#modalSponsorScreenID").val(sponsorScreenID);
              $("#modalCountryResidency").val(countryNameResidency);
              $("#modalCountryCodeResidency").val(countryCodeResidency);
              $("#confirmationModal").modal('show');
            }, "json");

        }

      }); 

  });

</script>
</body>
</html>