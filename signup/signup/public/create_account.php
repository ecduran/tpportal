<?php session_start();
//print_r($_SESSION);
include_once 'templates/header.php';
?>

    <div class="container">

      <div class="form-group">
        <label for="" class="font-weight-bold">Your Sponsor</label>
        <p>
          &emsp;Name: <input class="modal-input" type="text" id="modalSponsorName" value="<?php echo $_SESSION['sponsorName'];?>" readonly>
        <br> 
          &emsp;screenID: <input class="modal-input" type="text" id="modalSponsorScreenID" value="<?php echo $_SESSION['sponsorScreenID'];?>" readonly>
        </p>
      </div> 

      <hr>

      <form id="createAccount">

      <p class="font-weight-bold">Create an account</p>

      <div class="form-group">
        <label for="inputCompanyName">Company Name </label>
        <input type="text" class="form-control" id="companyName"  name="companyName" placeholder="Company Name">
      </div>

      <div class="form-group">
        <label for="selectCompanyType">Company Type <span class="text-danger">*</span></label>
        <select class="form-control" name="companyType" id="companyType" required>
          <option value="NA" selected>Not a company</option>
          <option value="NON-PROFIT">Registered non-profit</option>
          <option value="PROFIT">Company for profit</option>
        </select>
      </div>

      <div class="form-group">
        <label for="inputNameTitle">Title </label>
        <input type="text" class="form-control" name="nameTitle" id="nameTitle" placeholder="(Mr., Ms., Mrs.,etc.)">
      </div>

      <div class="form-group">
        <label for="inputFirstName">First Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="firstName" id="firstName" placeholder=""  required>
      </div>      

      <div class="form-group">
        <label for="inputMiddleName">Middle Name </label>
        <input type="text" class="form-control" name="middleName" id="middleName" placeholder="">
      </div>

      <div class="form-group">
        <label for="inputLastName">Last Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="" required>
      </div>

      <div class="form-group">
        <label for="selectGenderType">Gender <span class="text-danger">*</span></label>
        <select class="form-control" name="genderType" id="genderType" required>
          <option value="M" selected>Male</option>
          <option value="F">Female</option>
        </select>
      </div>

      <div class="form-group">
        <label for="inputEmail1">Email Address <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email1" id="email1" placeholder="youremail@mail.com" required>
                <div id="check-email1" class="text-danger"></div>
      </div>

      <div class="form-group">
        <label for="inputEmail2">Retype Email Address <span class="text-danger">*</span></label>
        <input type="email" class="form-control" name="email2" id="email2" placeholder="" required>
                <div id="check-email2" class="text-danger"></div>
      </div>

      <div class="form-group">
        <label for="inputUsername">ScreenID <span class="text-danger">*</span></label>
        <a class="" href="create_screenid.php">Change ScreenID?</a>
        <input type="text" class="form-control" id="username" value="<?php echo $_SESSION['screenID'];?>" readonly>
      </div>

      <div class="form-group">
        <label for="inputPassword">Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="password1" id="password1" autoload="off" required>
                <div id="check-password1" class="text-danger">Password must contain a symbol, a number and a capital letter.</div>
      </div>

      <div class="form-group">
        <label for="inputPassword">Retype Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" name="password2" id="password2" autoload="off" required>
                <div id="check-password2"></div>
      </div>      

      <div class="form-group">
        <img id="captcha" src="../vendor/securimage/securimage_show.php" alt="CAPTCHA Image" class="form-control" />
        <input type="text" class="form-control"  id="captcha_code" name="captcha_code" size="10" maxlength="6" required>
        <a href="#" onclick="document.getElementById('captcha').src = '../vendor/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
        <div id="check-captcha" class="text-danger"></div>
      </div>

        <button type="submit" id="btn-submit" class="btn btn-primary">Submit</button>

        <a type="button" class="btn btn-secondary" href="create_screenid.php">Back</a>

      </form>
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

$(function() {

  let email1 = $("#email1");
  let email2 = $("#email2");
  let password1 = $("#password1");
  let password2 = $("#password2");

  email1.keyup(function()
  {
    let email1Str = email1.val();

    if(email1Str.length == 0)
    {
      $('#check-email1').text("");
      return;
    }
    else
    {
      $.get("../src/check_email.php?q="+email1Str, function(responseText) 
      {
        $('#check-email1').html(responseText);
      });
    }
  });

  email2.keyup(function() 
  {
    let email2Str = email2.val();

    if (email2Str.length == 0)
    {
      $("#check-email2").text("");
      return false;
    }
    else if (email2Str !== email1.val())
    {
      $("#check-email2").html("<p class='font-weight-bold text-danger'>Email did not match.</p>");
      return false;
    }
    else
    {
      $("#check-email2").html("<p class='font-weight-bold text-success'>Email matched.</p>");
    }
  });

  password1.keyup(function() 
  {
    let password1Str = password1.val();

    if (password1Str.length == 0)
    {
      $("#check-password1").text("");
      return;
    }
    else
    {
      $.get("../src/check_password.php?q="+password1Str, function(responseText) 
        {
          $("#check-password1").html(responseText);
        });
    }
  });

  password2.keyup(function() 
  {
    let password2Str = password2.val();

    if (password2Str == 0)
    {
      $("#check-password2").html("");
      return ;
    }
    else if (password2Str !== password1.val())
    {
      $("#check-password2").html("<p class='font-weight-bold text-danger'>Password did not match.</p>");
      return false;
    }
    else
    {
      $("#check-password2").html("<p class='font-weight-bold text-success'>Password matched.</p>");
      return false;
    }
  });

  $("#btn-submit").click(function()
  {
    let password2Str = password2.val();

    let captchaCode = $("#captcha_code").val();
    let checkCaptcha = $("#check-captcha");

     $.post("../src/validate_captcha.php",
               {
                captcha_code: captchaCode
               }, 
               function(responseText)
               {

                  if (!responseText)
                  {
                    alert("Invalid Captcha");

                    checkCaptcha.text("Invalid Captcha.");
                    $('#captcha').attr('src','../vendor/securimage/securimage_show.php?'+Math.random());
                 
                  }
                  else
                  {
                     $.post("../src/signup_registry.php",
                       $("#createAccount").serialize(),
                       function(responseText)
                       {
                        if (responseText == 'false')
                        {
                          alert("All fields are required.");   
                        }
                        else
                        {
                          location.href = responseText;
                         ;
                        }
                       });
                  }
                  
                });
	return false;
  });

 
});


</script>

  </body>
</html>