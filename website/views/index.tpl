<html>
	<head>
		<title>Areascore - How good is your neighborhood?</title>
		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel="stylesheet" type="text/css" href="css/styles_nf.css">
		<link rel="stylesheet" type="text/css" href="css/animate.css">
		<link rel="stylesheet" type="text/css" href="css/fontello.css">
		<link href='http://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
	</head>
	<body style="overflow: hidden;">
    		<div class="container animated fadeInDown">
				<h1><span>Area</span>Score</h1>
				<h2>How good is your neighborhood?</h2>
				<center>
                <input id="search" type="text" name="search" onclick="this.select();" onfocus="this.select();" onblur="this.placeholder=!this.placeholder?'Type in your postcode and hit enter':this.placeholder;" onkeypress="searchKeyPress(event)" placeholder="Type in your postcode and hit enter" />
				</center>
				<p class="headline">Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec ullamcorper nulla non metus auctor fringilla. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
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
	</body>
</html>
