  window.fbAsyncInit = function() {
    FB.init({
      appId: '1135496416586463',
      cookie: true,
      xfbml: true,
      version: 'v2.2'
    });
  };

  function Login() {
    FB.login(function(response) {
      if (response.authResponse) {
        jQuery.ajax({
          url: 'php/fb-login.php',
          type: 'POST',
          data: {
            'access_token': response.authResponse.accessToken
          },
          success: function(result) {
            var res = result;
            if (res === 'false') {
              document.getElementById("fb_status").innerHTML = 'User is not logged in yet.';
            }
			if (res === 'true'){
				window.location.href = "https://www.drmwebdesign.com/dashboard.php";
          }
        }
      });
    }
  }, {
      scope: 'public_profile,email'
    });
  }

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
