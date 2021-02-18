<!DOCTYPE html>
<html>
<head>
	<title>2D-simulator</title>
	<link rel="icon" type="image/png" sizes="16x8" href="css/bcdr-img/logo.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/style2.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/style2d.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/check-box.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/reponsive.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/style.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/slide.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/build-your-car/reponsive-2d.css">
</head>
<body>

	<div class="banner-top">
		&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;
	</div>
	<div class="Simulator-2d">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="btn-aa nav-link text-bl active" id="Make/Models-tab" data-toggle="tab" href="#Make/Models" role="tab" aria-controls="Make/Models" aria-selected="true" onclick="myfunction()">Makes/Models</a>
			</li>
			<li class="nav-item">
				<a class="btn-bb nav-link text-bl btn-1" id="Exterior-tab" data-toggle="tab" href="#Exterior" role="tab" aria-controls="Exterior" aria-selected="false" onclick="myfunction1()">Exterior</a>
			</li>
			<li class="nav-item">
				<a class="btn-cc nav-link text-bl" id="Interior-tab" data-toggle="tab" href="#Interior" role="tab" aria-controls="Interior" aria-selected="false" onclick="myfunction2()">Interior</a>
			</li>
			<li class="nav-item">
				<a class="btn-dd nav-link text-bl" id="Motor-tab" data-toggle="tab" href="#Motor" role="tab" aria-controls="Motor" aria-selected="false" onclick="myfunction3()">Motor</a>
			</li>
		</ul>
		<div class="float-right z-index mg-checkbox">
			<label for="s1" id="s1-nd">Your View</label> 
			<input id="s1" type="checkbox" class="switch">
		</div>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="Make/Models" role="tabpanel" aria-labelledby="Make/Models-tab">
				<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img class="d-block w-100 dp-none-mobile" id="check-1" src="<?= base_url() ?>assets/build-your-car/bcdr-img/bg-selecter.png" alt="First slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100 dp-none-mobile" id="check-2" src="<?= base_url() ?>assets/build-your-car/bcdr-img/bg-selecter.png" alt="Second slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100 dp-none-mobile" id="check-3" src="<?= base_url() ?>assets/build-your-car/bcdr-img/bg-selecter.png" alt="Third slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
					</div>
					<a class="carousel-control-prev Back-cc" href="#carouselExampleIndicators" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next Next-cc" href="#carouselExampleIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="Exterior" role="tabpanel" aria-labelledby="Exterior-tab">
				<div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img class="d-block w-100" id="check-1" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png" alt="First slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" id="check-2" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png" alt="Second slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" id="check-3" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png" alt="Third slide">
							<img class="w-100 dp-none-mobile-1" id="check-1a" src="<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-4.png" alt="First slide">
						</div>
					</div>
					<a class="carousel-control-prev Back-cc" href="#carouselExampleIndicators2" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next Next-cc" href="#carouselExampleIndicators2" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="Interior" role="tabpanel" aria-labelledby="Interior-tab">
				<div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img class="d-block w-100" id="check-4" src="<?= base_url() ?>assets/build-your-car/bg/b2.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" id="check-5" src="<?= base_url() ?>assets/build-your-car/bg/161924013.png" alt="Second slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" id="check-6" src="<?= base_url() ?>assets/build-your-car/bg/b2.png" alt="Third slide">
						</div>
					</div>
					<a class="carousel-control-prev Back-cc" href="#carouselExampleIndicators3" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next Next-cc" href="#carouselExampleIndicators3" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="Motor" role="tabpanel" aria-labelledby="Motor-tab">
				<div id="carouselExampleIndicators4" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img class="d-block w-100" src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-engine-1548434_1280.png" alt="First slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-engine-1646784_1280.png" alt="Second slide">
						</div>
						<div class="carousel-item">
							<img class="d-block w-100" src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-engine-1548434_1280.png" alt="Third slide">
						</div>
					</div>
					<a class="carousel-control-prev Back-cc" href="#carouselExampleIndicators4" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next Next-cc" href="#carouselExampleIndicators4" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="properties-2d">
		<div class="properties-2d-1">
			<div class="tab-pane fade show active" id="nav-Wheels" role="tabpanel" aria-labelledby="nav-Wheels-tab">
				<div class="title">
					<div class="row" style="margin: 5px 0">
						<div class="col-sm-8">
							<p class="mg-0">My votes: 999 Votes <span><a href="#" style="color: red">+</a></span></p>
						</div>
						<div class="col-sm-4" style="padding-right: 0">
							<table class="note-vote">
								<tr>
									<td style="padding: 0 10px 0 0;">My vote</td>
									<td style="padding: 0 10px;"><img src="<?= base_url() ?>assets/build-your-car/bcdr-img/1x/Artboard 1.png" style="width: 25px"></td>
									<td style="padding: 0 10px;">Contest vote</td>
									<td style="padding: 0 10px;"><img src="<?= base_url() ?>assets/build-your-car/bcdr-img/1x/Artboard 2.png" style="width: 25px"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div class="row" id="style-2">
					<div class="col-sm-3 scrollbar mobile-dp-none" style="padding-right: 0">
						<div class="accordion" id="accordionExample" style="margin-right: 0">
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingOne-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" onclick="SelectCarA()" id="select-a">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Mustang
										</button>
									</p>
								</div>

								<div id="collapseOne" class="collapse" aria-labelledby="headingOne-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-1976-list" data-toggle="list" href="#list-1976" role="tab" aria-controls="1976">1976</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Thunderbird-list" data-toggle="list" href="#list-Thunderbird" role="tab" aria-controls="Thunderbird">Thunderbird</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Fairlane-list" data-toggle="list" href="#list-Fairlane" role="tab" aria-controls="Fairlane">Fairlane</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingTwo-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" onclick="SelectCarB()" id="select-b">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Chevrolet
										</button>
									</p>
								</div>
								<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Camaro-list" data-toggle="list" href="#list-Camaro" role="tab" aria-controls="Camaro" onclick="myselcetA1()">Camaro</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Chevelle-list" data-toggle="list" href="#list-Chevelle" role="tab" aria-controls="Red" onclick="myselcetA2()">Chevelle</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Nova-list" data-toggle="list" href="#list-Nova" role="tab" aria-controls="Nova"onclick="myselcetA3()">Nova</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingThree-1" style="height: 30px;margin-bottom: 10px;">
									<p class=" mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" onclick="SelectCarC()" id="select-c">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Dodge 
										</button>
									</p>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Charger-list" data-toggle="list" href="#list-Charger" role="tab" aria-controls="Charger">Charger</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Red-list" data-toggle="list" href="#list-Red" role="tab" aria-controls="Red">Red</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Dart-list" data-toggle="list" href="#list-Dart" role="tab" aria-controls="Dart">Dart</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Challenger-list" data-toggle="list" href="#list-Challenger" role="tab" aria-controls="Challenger">Challenger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingFour-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseOne-1" aria-expanded="true" aria-controls="collapseOne-1" onclick="SelectCarD()" id="select-d">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Kia morning
										</button>
									</p>
								</div>

								<div id="collapseOne-1" class="collapse" aria-labelledby="headingFour-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Blue1-list" data-toggle="list" href="#list-Blue1" role="tab" aria-controls="Blue1">Blue</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Red1-list" data-toggle="list" href="#list-Red1" role="tab" aria-controls="Red1">Red</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Green1-list" data-toggle="list" href="#list-Green1" role="tab" aria-controls="Green1">Green</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingFine-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseTwo-2" aria-expanded="false" aria-controls="collapseTwo-2" onclick="SelectCarE()" id="select-e">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Aston martin
										</button>
									</p>
								</div>
								<div id="collapseTwo-2" class="collapse" aria-labelledby="headingFine-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action active" id="list-Blue2-list" data-toggle="list" href="#list-Blue2" role="tab" aria-controls="Blue2">Blue</a>
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action" id="list-Red2-list" data-toggle="list" href="#list-Red2" role="tab" aria-controls="Red2">Red</a>
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action" id="list-Green2-list" data-toggle="list" href="#list-Green2" role="tab" aria-controls="Green2">Green</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingSix-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseThree-2" aria-expanded="false" aria-controls="collapseThree-2" onclick="SelectCarF()" id="select-f">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-icon.png" class="icon-select">Honda
										</button>
									</p>
								</div>
								<div id="collapseThree-2" class="collapse" aria-labelledby="headingSix-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action active" id="list-Blue3-list" data-toggle="list" href="#list-Blue3" role="tab" aria-controls="Blue3">Blue</a>
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action" id="list-Red3-list" data-toggle="list" href="#list-Red3" role="tab" aria-controls="Red3">Red</a>
											<a class="pd-0 NBD-card2 boder-none list-group-item list-group-item-action" id="list-Green3-list" data-toggle="list" href="#list-Green3" role="tab" aria-controls="Green3">Green</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-9 pd-r-mb">
						<div class="skills">
							<div class="charts">
								<div class="chart chart--dev">
									<ul class="chart--horiz" style="padding-left: 0">
										<li class="chart__bar" style="width: 98%;">
											<span class="chart__label">
												abc
											</span>
										</li>
										<li class="chart__bar chart__bar-1" style="width: 98%;">
											<span class="chart__label">
												xyz
											</span>
										</li>
										<li class="chart__bar" style="width: 70%;">
											<span class="chart__label">
												bca
											</span>
										</li>
										<li class="chart__bar" style="width: 40%;">
											<span class="chart__label">
												ddd
											</span>
										</li>
										<li class="chart__bar" style="width: 50%;">
											<span class="chart__label">
												ddd
											</span>
										</li>
										<li class="chart__bar" style="width: 60%;">
											<span class="chart__label">
												ddd
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row dp-none" id="style-3">
					<div class="col-sm-3 scrollbar mobile-dp-none" style="padding-right: 0">
						<div class="accordion" id="accordionExample" style="margin-right: 0">
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingOne-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseOne-2" aria-expanded="true" aria-controls="collapseOne-2">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/car-wheel.png" class="icon-select">Wheels
										</button>
									</p>
								</div>

								<div id="collapseOne-2" class="collapse" aria-labelledby="headingOne-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Conventional-list" data-toggle="list" href="#list-Conventional" role="tab" aria-controls="Conventional">Conventional Steel</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Modern-list" data-toggle="list" href="#list-Modern" role="tab" aria-controls="Modern">Modern Alloy</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Bottom-list" data-toggle="list" href="#list-Bottom" role="tab" aria-controls="Bottom">Bottom Line</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Forged-list" data-toggle="list" href="#list-Forged" role="tab" aria-controls="Forged">Forged versus Cast</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingTwo-2" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseTwo-2a" aria-expanded="false" aria-controls="collapseTwo-2a">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/color-palette.png" class="icon-select">Color
										</button>
									</p>
								</div>
								<div id="collapseTwo-2a" class="collapse" aria-labelledby="headingTwo-2" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Blue1b-list" data-toggle="list" href="#list-Blue1b" role="tab" aria-controls="Blue1b">Blue</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Red1-list" data-toggle="list" href="#list-Red1" role="tab" aria-controls="Red1" >Red</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Green1-list" data-toggle="list" href="#list-Green1" role="tab" aria-controls="Green1">Green</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingThree-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseThree-3" aria-expanded="false" aria-controls="collapseThree-3">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/mirror-icon.png" class="icon-select">Mirror
										</button>
									</p>
								</div>
								<div id="collapseThree-3" class="collapse" aria-labelledby="headingThree-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Plane-list" data-toggle="list" href="#list-Plane" role="tab" aria-controls="Plane">Plane</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Concave-list" data-toggle="list" href="#list-Concave" role="tab" aria-controls="Concave">Concave</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Convex-list" data-toggle="list" href="#list-Convex" role="tab" aria-controls="Convex">Convex</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="skills">
							<div class="charts">
								<div class="chart chart--dev">
									<ul class="chart--horiz" style="padding-left: 0">
										<li class="chart__bar" style="width: 98%;">
											<span class="chart__label">
												abc
											</span>
										</li>
										<li class="chart__bar chart__bar-1" style="width: 98%;">
											<span class="chart__label">
												xyz
											</span>
										</li>
										<li class="chart__bar" style="width: 70%;">
											<span class="chart__label">
												bca
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row dp-none" id="style-4">
					<div class="col-sm-3 scrollbar mobile-dp-none" style="padding-right: 0">
						<div class="accordion" id="accordionExample" style="margin-right: 0">
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingOne-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseOne-4" aria-expanded="true" aria-controls="collapseOne-4">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/Transmissions.png" class="icon-select-1">Transmissions
										</button>
									</p>
								</div>

								<div id="collapseOne-4" class="collapse" aria-labelledby="headingOne-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Automatic-list" dat-toggle="list" href="#list-Automatric" role="tab" aria-controls="Automatic">Automatic</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Manual-list" data-toggle="list" href="#list-Manual" role="tab" aria-controls="Manual">Manual</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingTwo-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseTwo-4" aria-expanded="false" aria-controls="collapseTwo-4">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/seal-icon.png" class="icon-select-2">Interior
										</button>
									</p>
								</div>
								<div id="collapseTwo-4" class="collapse" aria-labelledby="headingTwo-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Black-list" data-toggle="list" href="#list-Black" role="tab" aria-controls="Black">Black</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Red-list" data-toggle="list" href="#list-Red" role="tab" aria-controls="Red">Red</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-White-list" data-toggle="list" href="#list-White" role="tab" aria-controls="White">White</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="skills">
							<div class="charts">
								<div class="chart chart--dev">
									<ul class="chart--horiz" style="padding-left: 0">
										<li class="chart__bar" style="width: 98%;">
											<span class="chart__label">
												abc
											</span>
										</li>
										<li class="chart__bar chart__bar-1" style="width: 98%;">
											<span class="chart__label">
												xyz
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row dp-none" id="style-5">
					<div class="col-sm-3 scrollbar mobile-dp-none" style="padding-right: 0">
						<div class="accordion" id="accordionExample" style="margin-right: 0">
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingOne-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseOne-5" aria-expanded="true" aria-controls="collapseOne-5">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/motor-icon.png" class="icon-select">Motors
										</button>
									</p>
								</div>

								<div id="collapseOne-5" class="collapse" aria-labelledby="headingOne-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-small-list" data-toggle="list" href="#list-small" role="tab" aria-controls="small">Small Block</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-big-list" data-toggle="list" href="#list-big" role="tab" aria-controls="big">Big Block</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card NBD-style-card">
								<div class="card-header pd-0 boder-none bg-select-1" id="headingTwo-1" style="height: 30px;margin-bottom: 10px;">
									<p class="mb-0">
										<button class="btn btn-link collapsed pd-0 color-select" type="button" data-toggle="collapse" data-target="#collapseTwo-5" aria-expanded="false" aria-controls="collapseTwo-5">
											<img src="<?= base_url() ?>assets/build-your-car/bcdr-img/Fuel-Supply.png" class="icon-select"> Fuel Supply
										</button>
									</p>
								</div>
								<div id="collapseTwo-5" class="collapse" aria-labelledby="headingTwo-1" data-parent="#accordionExample">
									<div class="card-body" style="padding: 0 1rem 10px;">
										<div class="list-group" id="list-tab" role="tablist">
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action active" id="list-Injaction-list" data-toggle="list" href="#list-Injaction" role="tab" aria-controls="Injaction">Double Fuel Injaction</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Double-list" data-toggle="list" href="#list-Double" role="tab" aria-controls="Double">Double Carburetor</a>
											<a class="pd-0 NBD-card1 boder-none list-group-item list-group-item-action" id="list-Single-list" data-toggle="list" href="#list-Single" role="tab" aria-controls="Single">Single Carburetor</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="skills">
							<div class="charts">
								<div class="chart chart--dev">
									<ul class="chart--horiz" style="padding-left: 0">
										<li class="chart__bar" style="width: 98%;">
											<span class="chart__label">
												abc
											</span>
										</li>
										<li class="chart__bar chart__bar-1" style="width: 98%;">
											<span class="chart__label">
												xyz
											</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		function myfunction() {
			var x = document.getElementById('style-2');
			var y = document.getElementById('style-3');
			var z = document.getElementById('style-4');
			var a = document.getElementById('style-5');
			var b = document.getElementsByClassName('.btn-aa');
			if (x.style.display === "none" || b.hasAttribute("ariaSelected","true")) {
				x.style.display = "flex";
				y.style.display = "none";
				z.style.display = "none";
				a.style.display = "none";
			} else {
				x.style.display = "none";
				y.style.display = "flex";
				z.style.display = "none";
				a.style.display = "none";
			}
		}
		function myfunction1() {
			var x1 = document.getElementById('style-3');
			var y1 = document.getElementById('style-2');
			var z1 = document.getElementById('style-4');
			var a1 = document.getElementById('style-5');
			var b1 = document.getElementsByClassName('.btn-bb');
			if (x1.style.display === "none" || b1.hasAttribute("ariaSelected","true")) {
				x1.style.display = "flex";
				y1.style.display = "none";
				z1.style.display = "none";
				a1.style.display = "none";
			} else {
				x1.style.display = "none";
				y1.style.display = "flex";
				z1.style.display = "none";
				a1.style.display = "none";
			}
		}
		function myfunction2() {
			var x2 = document.getElementById('style-4');
			var y2 = document.getElementById('style-3');
			var z2 = document.getElementById('style-2');
			var a2 = document.getElementById('style-5');
			var b2 = document.getElementsByClassName('.btn-cc');
			if (x2.style.display === "none") {
				x2.style.display = "flex";
				y2.style.display = "none";
				z2.style.display = "none";
				a2.style.display = "none";
			} else {
				x2.style.display = "none";
				y2.style.display = "flex";
				z2.style.display = "none";
				a2.style.display = "none";
			}
		}
		function myfunction3() {
			var x3 = document.getElementById('style-5');
			var y3 = document.getElementById('style-4');
			var z3 = document.getElementById('style-3');
			var a3 = document.getElementById('style-2');
			var b3 = document.getElementsByClassName('.btn-dd');
			if (x3.style.display === "none") {
				x3.style.display = "flex";
				y3.style.display = "none";
				z3.style.display = "none";
				a3.style.display = "none";
			} else {
				x3.style.display = "none";
				y3.style.display = "flex";
				y3.style.display = "none";
				z3.style.display = "none";
				a3.style.display = "none";
			}
		}

		function SelectCarA() {
			var selectA = document.getElementById("select-a");
			var checkA  = document.getElementById("check-1");
			var checkB  = document.getElementById("check-2");
			var checkC  = document.getElementById("check-3");
			if (selectA.hasAttribute("aria-expanded","true")) {
				checkA.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-green.png");
				checkB.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-green.png");
				checkC.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-green.png");
			} else {
				checkA.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png");
				checkB.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png");
				checkC.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png");
			}
		}
		function SelectCarB() {
			var selectB  = document.getElementById("select-a");
			var checkA1  = document.getElementById("check-1");
			var checkB1  = document.getElementById("check-2");
			var checkC1  = document.getElementById("check-3");
			if (selectB.hasAttribute("aria-expanded","true")) {
				checkA1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
				checkB1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
				checkC1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
			} else {
				checkA1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png");
				checkB1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png");
				checkC1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png");
			}
		}
		function myselcetA1() {
			var selectA1  = document.getElementById("list-Camaro-list");
			var nbdcheck1 = document.getElementById("check-1");
			var nbdcheck2 = document.getElementById("check-2");
			var nbdcheck3 = document.getElementById("check-3");
			if (selectA1.hasAttribute("aria-selected","true")) {
				nbdcheck1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/Camaro-1-blue.png");
				nbdcheck2.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/Camaro-1-blue.png");
				nbdcheck3.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/Camaro-1-blue.png");
			} else {
				nbdcheck1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png");
				nbdcheck2.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png");
				nbdcheck3.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png");
			}
		}
		function myselcetA2() {
			var selectA2  = document.getElementById("list-Chevelle-list");
			var nbdcheck1a = document.getElementById("check-1");
			var nbdcheck2a = document.getElementById("check-2");
			var nbdcheck3a = document.getElementById("check-3");
			if (selectA2.hasAttribute("aria-selected","true")) {
				nbdcheck1a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
				nbdcheck2a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
				nbdcheck3a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/chaveles-1-green.png");
			} else {
				nbdcheck1a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png");
				nbdcheck2a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png");
				nbdcheck3a.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png");
			}
		}
		document.getElementById('s1').onclick = function(e){
			var check1 = document.getElementById("check-1");
			var check2 = document.getElementById("check-2");
			var check3 = document.getElementById("check-3");
			var dk = document.getElementsByClassName("carousel-item");
			var nd = document.getElementById('s1-nd');
			if (this.checked){
				nd.innerHTML = "Overall view";
				check1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-green.png");
				check2.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-green.png");
				check3.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-green.png");
			}
			else{
				check1.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-1-blue.png");
				check2.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-2-blue.png");
				check3.setAttribute("src", "<?= base_url() ?>assets/build-your-car/bcdr-img/mustang-3-blue.png");
				nd.innerHTML = "Your view";
			}
		};

	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>