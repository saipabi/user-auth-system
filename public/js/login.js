$(document).ready(function () {

  function loginUser() {
    var email = $("#email").val().trim();
    var password = $("#password").val();

    $("#error-msg").hide();

    if (!email || !password) {
      $("#error-msg").text("Please enter both email and password").show();
      return;
    }

    // More lenient email validation - just check if it contains @
    // Let server do the final validation
    if (email.indexOf('@') === -1) {
      $("#error-msg").text("Please enter a valid email address (must contain @)").show();
      return;
    }

    console.log("Attempting login with email:", email);

    $("#login").prop("disabled", true).text("Logging in...");

    $.ajax({
      url: "../api/login.php",
      type: "POST",
      dataType: "json",
      data: { 
        email: email.toLowerCase().trim(), // Normalize to lowercase and trim
        password: password 
      },

      success: function (data) {
        console.log("Login response:", data);
        
        if (data.token) {
          localStorage.setItem("token", data.token);
          console.log("Token saved:", data.token);
          alert("Login successful! Redirecting to profile...");
          window.location.href = "profile.html";
        } else {
          var errorMsg = data.error || "Login failed";
          console.error("Login failed:", errorMsg);
          $("#error-msg").text(errorMsg).show();
          $("#login").prop("disabled", false).text("Login");
        }
      },

      error: function (xhr, status, error) {
        console.error("Login AJAX error:", xhr.responseText, status, error);
        console.error("Status code:", xhr.status);
        console.error("Response text:", xhr.responseText);
        
        var errorMsg = "Server error. Please try again.";
        try {
          if (xhr.responseText) {
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
              errorMsg = response.error;
            }
          }
        } catch (e) {
          console.error("Parse error:", e);
          if (xhr.status === 0) {
            errorMsg = "Cannot connect to server. Please check if Apache is running.";
          } else if (xhr.status === 500) {
            errorMsg = "Server error. Please check PHP error logs.";
          } else if (xhr.responseText) {
            errorMsg = "Error: " + xhr.responseText.substring(0, 100);
          }
        }
        
        $("#error-msg").text(errorMsg).show();
        $("#login").prop("disabled", false).text("Login");
      }
    });
  }

  $("#login").click(loginUser);
  
  // Enter key support
  $("#email, #password").on("keypress", function(e) {
    if (e.which === 13) {
      loginUser();
    }
  });
  
  // Focus on email field when page loads
  $("#email").focus();
});
