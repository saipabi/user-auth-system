$(document).ready(function() {
  let token = localStorage.getItem("token");

  if (!token) {
    alert("Please login first");
    window.location.href = "login.html";
    return;
  }

  // Load profile data
  $.ajax({
    url: "../api/profile_get.php",
    type: "POST",
    data: { token: token },
    dataType: "json",
    success: function(d) {
      if (d.error) {
        alert(d.error);
        localStorage.removeItem("token");
        window.location.href = "login.html";
        return;
      }
      $("#age").val(d.age || "");
      $("#dob").val(d.dob || "");
      $("#contact").val(d.contact || "");
    },
    error: function(xhr, status, error) {
      console.error("Profile load error:", xhr.responseText);
      alert("Error loading profile. Please try again.");
    }
  });

  // Save profile
  $("#save").click(function() {
    var age = $("#age").val().trim();
    var dob = $("#dob").val();
    var contact = $("#contact").val().trim();
    
    $("#save").prop("disabled", true).text("Saving...");
    
    $.ajax({
      url: "../api/profile_update.php",
      type: "POST",
      data: {
        token: token,
        age: age,
        dob: dob,
        contact: contact
      },
      success: function(res) {
        var response = res.trim();
        if (response === "Updated" || response.includes("Updated")) {
          alert("Profile saved successfully!");
        } else {
          alert("Response: " + response);
        }
        $("#save").prop("disabled", false).text("Save");
      },
      error: function(xhr, status, error) {
        console.error("Profile save error:", xhr.responseText);
        alert("Error saving profile. Please try again.");
        $("#save").prop("disabled", false).text("Save");
      }
    });
  });

  // Logout
  $("#logout").click(function() {
    if (confirm("Are you sure you want to logout?")) {
      localStorage.removeItem("token");
      alert("Logged out successfully!");
      window.location.href = "login.html";
    }
  });
});
