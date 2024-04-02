$(".login").click(() => {
  var data = {
    username: $(".username").val(),
    pass: $(".password").val(),
    action: "loginAuth",
  };

  $.ajax({
    method: "POST",
    dataType: "JSON",
    data: data,
    url: "auth.php",
    success: (response) => {
      localStorage.setItem("token", response[0].token);
      console.log(response);
      window.location.href = "home.html";
    },
    error: (response) => {
      console.log(response);
    },
  });
});

$(".request").click(() => {
  $.ajax({
    method: "POST",
    dataType: "JSON",
    // contentType : 'application/x-www-form-urlencoded',
    data: {
      action: "request",
      token: localStorage.getItem("token")
    },
   
    url: "auth.php",
    success: (response) => {
        if(response.hasError){
            alert(response.message)
            return;
        }
      //  localStorage.setItem("token", response[0].token);
      console.log(response);
      //  window.location.href = "home.html";
    },
    error: (response) => {
      console.log(response);
    },
  });
});
