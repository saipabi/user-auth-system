$(document).ready(function () {
  function registerUser() {
    const username = $("#username").val().trim();
    const email = $("#email").val().trim();
    const password = $("#password").val();

    $("#error-msg").hide();
    $("#success-msg").hide();

    if (!username || !email || !password) {
      $("#error-msg").text("Please fill in all fields").show();
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      $("#error-msg").text("Please enter a valid email address").show();
      return;
    }

    $("#register").prop("disabled", true).text("Registering...");

    $.ajax({
      url: "../api/register.php",
      method: "POST",
      dataType: "json",
      data: {
        username: username,
        email: email,
        password: password
      },
      success: function (res) {
        console.log("Registration response:", res);

        if (res.status === "success") {
          $("#success-msg")
            .text("Registration successful! Redirecting to login...")
            .show();
          setTimeout(() => {
            window.location.href = "login.html";
          }, 1000);
        } else {
          let msg = "Registration failed";

          switch (res.message) {
            case "EMAIL_ALREADY_EXISTS":
              msg = "This email is already registered.";
              break;
            case "EMPTY_FIELDS":
              msg = "Please fill in all fields.";
              break;
            case "INVALID_EMAIL":
              msg = "Invalid email address.";
              break;
            default:
              msg = res.message || msg;
          }

          $("#error-msg").text(msg).show();
        }
      },
      error: function () {
        $("#error-msg").text("Server error. Please try again.").show();
      },
      complete: function () {
        $("#register").prop("disabled", false).text("Register");
      }
    });
  }

  $("#register").on("click", registerUser);

  $("#username, #email, #password").on("keypress", function (e) {
    if (e.which === 13) registerUser();
  });
});
