<?php include("puerta_principal.php");?>
<!DOCTYPE html>
<html>
<title>Sitio en Construcción</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="shortcut icon" href="https://www.hostname.cl/uploads/2019/08/admin-ajax.png" type="image/x-icon">
<style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
.bgimg {
  background-image: url('https://www.hostname.cl/uploads/2020/08/Hostname.png');
  min-height: 100%;
  background-position: center;
  background-size: cover;
}
.logo{
  margin-top: 40px;
  margin-left: 70px;
}
  .w3-jumbo.w3-animate-top{
    margin-top: 40px;
    margin-left: 0px;
}
/*MobileView*/
@media (max-width: 600px) {
  .logo{
    margin-top: 10px;
    margin-left: 10px;
  }
  .w3-jumbo.w3-animate-top{
    margin-top:0px;
    font-size: 2.5em !important;
  }

}
</style>
	<script language="javascript">
	$(document).ready(function() {
    window.location.href = 'login.php';
});

	</script>
<body>
<div class="bgimg w3-display-container w3-animate-opacity w3-text-white w3-large w3-centre">
    <div class="container">
        <div class="col-xs-hidden">
            <a href="https://www.hostname.cl">
                 <img class="logo" alt="Hostname" src="https://www.hostname.cl/uploads/2020/07/circulo-blanco-1-e1595434409775.png">
            </a>
        </div>
    </div>
  <div class="w3-display-middle">
    <h1 class="w3-jumbo w3-animate-top">QUEDO LOLASO</h1>
      <!-- Image Principal -->
      <div class="imgwrapper">
       <a href="https://www.hostname.cl" target="_blank"></a>
      </div>
    <hr class="w3-border-grey" style="margin:auto;width:100%">
    <p class="w3-large w3-center">Alojamiento web por Hostname</p>
  </div>

  </div>
</body>
</html>
