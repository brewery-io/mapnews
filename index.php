<!DOCTYPE html>

<html >
	<head >
		<title >creds to pj for idea</title>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css' />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<style >
		*{
			margin:0px;
			font-family: 'Ubuntu Mono';
		}
		html, body{
			height:100%;
			background-color:gray;
		}
		.map{
			position:absolute;
			width:1440px;
			height:720px;
			top:70px;
			z-index:1;
			left:0px;
			right:0px;
			margin:auto;
		}
		.overlay{
			position:relative;
			width:1440px;
			height:720px;
			z-index:2;
			left:0px;
			right:0px;
			margin:auto;
			top:70px;
		}
		.menu{
			position:absolute;
			left:0px;
			right:0px;
			margin:auto;
			top:15px;
			z-index:3;
			width:700px;
		}
		.menu div{
			height:10px;
		}
		.menu input{
			border:0px;
			height:20px;
			font-size:20px;
			background-color:darkgray;
			padding-left:10px;
			padding-right:10px; 
			width:100px;
			text-align:center;
			color:white;
		}
		.menu input:nth-of-type(1){
			margin-left:calc(50% - 110px);
		}
		.menu button{
			border:0px;
			height:22px;
			font-size:20px;
			color:white;
			background-color:darkgray;
		}
		.menu button#toggle{
			background-color:#505050;
			margin-left:136px;
		}
		.menu button#toggle_red{
			background-color:red;
		}
		.menu button#toggle_green{
			background-color:green;
		}
		.menu button#toggle_purple{
			background-color:purple;
		}
		footer{
			color:white;
			text-align:center;
			margin-top:30px;
		}
	</style>
	<body >
		<div class="menu" >
			<input name="begin" type="text" />
			<input name="end" type="text" />
			<button id="update" >refresh map</button>
			<div ></div>
			<button id="toggle" >toggle map</button>
			<button id="toggle_red" >toggle reds</button>
			<button id="toggle_green" >toggle greens</button>
			<button id="toggle_purple" >toggle purples</button>
		</div>
		<img class="map" id="map" src="map.png" />
		<div class="overlay" ></div>

		<footer >
			interactive mapnews v.1 - rafal stapinski - 2015
		</footer>

		<script >

			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var yyyy = today.getFullYear();

			if (dd < 10) {
			    dd ='0' + dd;
			} 

			if (mm < 10) {
			    mm = '0' + mm;
			}

			yyyymmdd = yyyy + mm + dd;

			begin = $('input[name="begin"]');
			end = $('input[name="end"]');
			update = $('#update');
			toggle = $('#toggle');
			overlay = $('.overlay');
			map = $('.map');
			toggle_red = $('#toggle_red');
			toggle_green = $('#toggle_green');
			toggle_purple = $('#toggle_purple');

			begin.val("20150501");
			end.val(yyyymmdd);
			update.click();

			toggle_red.on("click", function() {

				$('.red').toggle();
			});

			toggle_green.on("click", function() {

				$('.green').toggle();
			});

			toggle_purple.on("click", function() {

				$('.purple').toggle();
			});

			toggle.on("click", function() {

				map.toggle();

			});

			update.on("click", function () {

				overlay.empty();

					$.ajax({
						url: "search.php",
						type: "post",
						data: {
							"begin_date": begin.val(),
							"end_date": end.val()
						},
						success: function(data) {

							var finance = [];
							var conflict = [];
							var pollution = [];
							var finance_count = {};
							var conflict_count = {};
							var pollution_count = {};

							for (obj in data) {

								if (data[obj]["category"] == "finance") {
									finance.push(data[obj]["location"]);
								} else if (data[obj]["category"] == "conflict") {
									conflict.push(data[obj]["location"]);
								} else if (data[obj]["category"] == "pollution") {
									pollution.push(data[obj]["location"]);
								}
							}

							for (i in finance) {
								item = finance[i]

								lat = Number(item.split(",")[0]);
								lng = Number(item.split(",")[1]);

								new_lat = Math.round(lat);
								new_lng = Math.round(lng);

								new_location = String(new_lat) + "," + String(new_lng);
								
								if (new_location in finance_count) {
									finance_count[new_location]++;
								}
								else {
									finance_count[new_location] = 1;
								}
							}

							for (loc in finance_count) {
								var count = finance_count[loc];
								size = count * 6;
								radius = size / 2;


								var lat = Number(loc.split(",")[0]);
								var lng = Number(loc.split(",")[1]);

								var left = ((lng + 180) / 360) * 100;
								var top = (-1 * ((lat + 90) / 180) * 100);

								var style = "position:absolute;background-color:green;\
											left:calc("+left+"% - 46px - "+radius+"px);top:calc("+top+"% + 721px - "+radius+"px);\
											height:"+size+"px;width:"+size+"px;\
											border-radius:"+radius+"px;\
								";
								overlay.append("<div class='green' style='"+ style +"'></div>");
							}

							for (i in conflict) {
								item = conflict[i]

								lat = Number(item.split(",")[0]);
								lng = Number(item.split(",")[1]);

								new_lat = Math.round(lat);
								new_lng = Math.round(lng);

								new_location = String(new_lat) + "," + String(new_lng);
								
								if (new_location in conflict_count) {
									conflict_count[new_location]++;
								}
								else {
									conflict_count[new_location] = 1;
								}
							}

							for (loc in conflict_count) {
								var count = conflict_count[loc];
								size = count * 6;
								radius = size / 2;


								var lat = Number(loc.split(",")[0]);
								var lng = Number(loc.split(",")[1]);

								var left = ((lng + 180) / 360) * 100;
								var top = (-1 * ((lat + 90) / 180) * 100);

								var style = "position:absolute;background-color:red;\
											left:calc("+left+"% - 46px - "+radius+"px);top:calc("+top+"% + 721px - "+radius+"px);\
											height:"+size+"px;width:"+size+"px;\
											border-radius:"+radius+"px;\
								";
								overlay.append("<div class='red' style='"+ style +"'></div>");
							}

							for (i in pollution) {
								item = pollution[i]

								lat = Number(item.split(",")[0]);
								lng = Number(item.split(",")[1]);

								new_lat = Math.round(lat);
								new_lng = Math.round(lng);

								new_location = String(new_lat) + "," + String(new_lng);
								
								if (new_location in pollution_count) {
									pollution_count[new_location]++;
								}
								else {
									pollution_count[new_location] = 1;
								}
							}

							for (loc in pollution_count) {
								var count = pollution_count[loc];
								size = count * 6;
								radius = size / 2;


								var lat = Number(loc.split(",")[0]);
								var lng = Number(loc.split(",")[1]);

								var left = ((lng + 180) / 360) * 100;
								var top = (-1 * ((lat + 90) / 180) * 100);

								var style = "position:absolute;background-color:purple;\
											left:calc("+left+"% - 46px - "+radius+"px);top:calc("+top+"% + 721px - "+radius+"px);\
											height:"+size+"px;width:"+size+"px;\
											border-radius:"+radius+"px;\
								";
								overlay.append("<div class='purple' style='"+ style +"'></div>");
							}
						},
						failure: function(data) {
							alert('whoopsies ajax error');
						}
					});
				}
			);
			
		</script>
	</body>
</html>