<html>
	<head>
		<title>Areascore - <?=strtoupper($this->postcode)?> in <?=$this->areaInfo['identifier']?> </title>
		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel="stylesheet" type="text/css" href="css/styles_nf.css">
		<link rel="stylesheet" type="text/css" href="css/animate.css">
		<link rel="stylesheet" type="text/css" href="css/fontello.css">
		<link href='http://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(function () {

				$('#container').highcharts({
				            
				    chart: {
				    	renderTo: 'container',
				        polar: true,
				        type: 'line'
				    },
				    
				    title: {
				        text: 'Budget vs spending',
				        x: -80
				    },
				    
				    pane: {
				    	size: '80%'
				    },
				    
				    xAxis: {
				        categories: ['Safety', 'Environment', 'Education', 'Transport', 'Nightlife'],
				        tickmarkPlacement: 'on',
				        lineWidth: 0
				    },
				        
				    yAxis: {
				        gridLineInterpolation: 'polygon',
				        lineWidth: 0,
				        min: 0,
				        max: 10

				    },
				    
				    tooltip: {
				    	shared: true,
				        pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
				    },
				   
				    series: [{
				        name: 'Points (out of 10)',
				        data: [10, 3, 7, 7, 9],
				        pointPlacement: 'on'
				    }]
				});
			});
		</script>
	</head>
	<body>
    		<div class="container animated fadeInDown">
				<h1><span>Area</span>Score</h1>
				<h2>How good is your neighborhood?</h2>

				<h3 class="postcode">You searched for XXX XXXX</h3>

				<div class="score animated tada">
					<h3 class="counting"><?=$this->areaScore?></h3><span>POINTS</span><span class="outof">(out of 100)</span>
				</div>
				<div class="spider">
					Here comes the mighty spider X
				</div>
				<div class="summary">
					<h2>Something about <?=$this->areaInfo['identifier']?></h2>
					<p><?=$this->areaInfo['description']?></p>
				</div>
				<div class="proscons">
					<h2>The good things:</h2>
					<ul>
                    <?php foreach($this->areaInfo['good'] as $good): ?>
						<li><span class="icon-ok"></span><?=$good?></li>
                    <?php endforeach; ?>
					</ul>
				</div>
				<div class="proscons">
					<h2>The not too good things:</h2>
					<ul>
                    <?php foreach($this->areaInfo['bad'] as $bad): ?>
						<li><span class="icon-cancel-1"></span><?=$bad?></li>
                    <?php endforeach; ?>
					</ul>
				</div>
				<div class="footer">
					<a class="animated fadeIn" href="http://about.esd.org.uk/" target="_blank"><img src="img/esd.png"></a>
				</div>
    		</div>		
		<div class="x1"><img src="img/x1.png"></div>
		<div class="x2"><img src="img/x2.png"></div>
		<div class="x3"><img src="img/x3.png"></div>
		<div class="x4"><img src="img/x4.png"></div>
    	<script src="js/highcharts.js"></script>
		<script src="js/highcharts-more.js"></script>
		
		<div id="container" style="width: 94%%; height: 100%; margin: 0 auto"></div>
	</body>
</html>
