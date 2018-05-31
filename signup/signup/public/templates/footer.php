<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<!-- <script src="scripts/jquery-3.3.1.js"></script>
<script src="scripts/js/bootstrap.min.js"></script> -->

<script>
  function checkScreenID(str)
  {
    if (str.length == 0)
    {
      document.getElementById("checkScreenIDResult").innerHTML = "";
      return;
    }
    else 
    {
      let xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() 
      {
        if (this.readyState == 4 && this.status == 200)
        {
          document.getElementById("checkScreenIDResult").innerHTML = this.responseText;
        }
      };

      xmlhttp.open("GET", "../src/check_sponsor_screenid.php?q=" + str, true);
      xmlhttp.send();
    }
  }

$(function()
  {
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
              $("#confirmationModal").modal('show');
            }, "json");

        }

      }); 

  });

</script>
  </body>
</html>
