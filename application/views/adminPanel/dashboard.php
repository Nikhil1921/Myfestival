<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<div class="row">
	<div class="col-12 col-sm-6 col-md-3">
		<?= anchor(admin('upcoming'),
		'<div class="info-box mb-3">
			<span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-check"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Upcoming Events</span>
				<span class="info-box-number">'.$upcoming.'</span>
			</div>
		</div>', 'class="text-dark"') ?>
	</div>
	<div class="col-12 col-sm-6 col-md-3">
		<?= anchor(admin('category'),
		'<div class="info-box mb-3">
			<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-image"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Category</span>
				<span class="info-box-number">'.$category.'</span>
			</div>
		</div>', 'class="text-dark"') ?>
	</div>
	<div class="col-12 col-sm-6 col-md-3">
		<?= anchor(admin('users'),
		'<div class="info-box mb-3">
			<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Users</span>
				<span class="info-box-number">'.$users.'</span>
			</div>
		</div>', 'class="text-dark"') ?>
	</div>
	<div class="col-12 col-sm-6 col-md-3">
		<?= anchor(admin('banner'),
		'<div class="info-box">
			<span class="info-box-icon bg-info elevation-1"><i class="fas fa-image"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Banners</span>
				<span class="info-box-number">'.$banner.'</span>
			</div>
		</div>', 'class="text-dark"') ?>
	</div>
</div>