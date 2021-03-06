<html>
	<head>
		<title>Areascore - How good is your neighborhood?</title>
		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel="stylesheet" type="text/css" href="css/styles_nf.css">
		<link rel="stylesheet" type="text/css" href="css/animate.css">
		<link rel="stylesheet" type="text/css" href="css/fontello.css">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
	</head>
	<body style="overflow: hidden;">
    		<div class="container animated fadeInDown">
				<h1><span>Area</span>Score</h1>
				<h2>How good is your neighborhood?</h2>
				<center>
                <input id="search" type="text" name="search" onclick="this.select();" onfocus="this.select();" onblur="this.placeholder=!this.placeholder?'Type in your postcode and hit enter':this.placeholder;" onkeypress="searchKeyPress(event)" placeholder="Type in your postcode and hit enter" />
				</center>
				<p class="headline">AreaScore is your place to check the quality of neighborhood in the Greater London Area!</p>
				<div class="footer">
					<a class="animated fadeIn" href="http://about.esd.org.uk/" target="_blank"><img src="img/esd.png"></a>
				</div>
    		</div>
		
		<div class="x1"><img src="img/x1.png"></div>
		<div class="x2"><img src="img/x2.png"></div>
		<div class="x3"><img src="img/x3.png"></div>
		<div class="x4"><img src="img/x4.png"></div>
    	<script type="text/javascript">
        window.onload = function() {
            document.getElementById("search").focus();
        };
        function searchKeyPress(e) {
            // look for window.event in case event isn't passed in
            if (typeof e == 'undefined' && window.event) {
                e = window.event; 
            }
            if (e.keyCode == 13) {
                window.location = "/" + document.getElementById("search").value;
            }
        }
        </script>
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40502769-1', 'areascore.co.uk');
  ga('send', 'pageview');

</script>
	</body>
</html>
