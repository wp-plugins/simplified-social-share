/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery( document ).ready(function() {
	jQuery("#login_radius_counter_top_horizontal").click(function() {
		jQuery("#login_radius_horizontal_counter_providers_container,#login_radius_horizontal_providers_container").show();
		jQuery("#login_radius_horizontal_sharing_providers_container").hide();
	});

	jQuery("#login_radius_counter_top_vertical").click(function() {
		jQuery("#login_radius_horizontal_counter_providers_container,#login_radius_horizontal_providers_container").show();
		jQuery("#login_radius_horizontal_rearrange_container,#login_radius_horizontal_sharing_providers_container").hide();
	});

	jQuery("#login_radius_sharing_top_32").click(function() {
		jQuery("#login_radius_horizontal_rearrange_container,#login_radius_horizontal_sharing_providers_container,#login_radius_horizontal_providers_container").show();
		jQuery("#login_radius_horizontal_counter_providers_container").hide();
	});

	jQuery("#login_radius_sharing_top_16").click(function() {
		jQuery("#login_radius_horizontal_rearrange_container,#login_radius_horizontal_sharing_providers_container,#login_radius_horizontal_providers_container").show();
		jQuery("#login_radius_horizontal_counter_providers_container").hide();
	});

	jQuery("#login_radius_sharing_top_slarge").click(function() {
		jQuery("#login_radius_horizontal_providers_container,#login_radius_horizontal_rearrange_container").hide();
	});

	jQuery("#login_radius_sharing_top_ssmall").click(function() {
		jQuery("#login_radius_horizontal_providers_container,#login_radius_horizontal_rearrange_container").hide();
	});

	jQuery("#login_radius_sharing_vertical_32").click(function() {
		jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").show();
		jQuery("#login_radius_vertical_counter_providers_container").hide();
	});

	jQuery("#login_radius_sharing_vertical_16").click(function() {
		jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").show();
		jQuery("#login_radius_vertical_counter_providers_container").hide();
	});

	jQuery("#login_radius_counter_vertical_vertical").click(function() {
		jQuery("#login_radius_vertical_counter_providers_container").show();
		jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").hide();
	});

	jQuery("#login_radius_counter_vertical_horizontal").click(function() {
		jQuery("#login_radius_vertical_counter_providers_container").show();
		jQuery("#login_radius_vertical_rearrange_container,#login_radius_vertical_sharing_providers_container").hide();
	});

	jQuery("#show_horizontal_theme_content").click(function() {
		jQuery("#login_radius_horizontal").show();
		jQuery("#login_radius_vertical").hide();
	});
	jQuery("#show_vertical_theme_content").click(function() {
		jQuery("#login_radius_horizontal").hide();
		jQuery("#login_radius_vertical").show();
	});

});


