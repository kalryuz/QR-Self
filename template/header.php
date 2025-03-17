<div class="header">
	<div class="header-left">
		<div class="menu-icon dw dw-menu"></div>
		<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
		<div class="header-search">
			<form>
				<div class="form-group mb-0">
					<i class="dw dw-search2 search-icon"></i>
					<input type="text" class="form-control search-input" placeholder="Search Here">
					<div class="dropdown">
						<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
							<i class="ion-arrow-down-c"></i>
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="header-right">
		<div class="d-flex align-content-center justify-content-around" style="width: 120px; font-size:16px; padding-top:23px;">
			<i class="ion-android-person"></i>
			<p class="fw-bold"><?= $_SESSION['name']?></p>
		</div>
		<div class="github-link">
			<a href="https://github.com/kalryuz" target="_blank"><img src="vendors/images/github.svg" alt=""></a>
		</div>
	</div>
</div>	