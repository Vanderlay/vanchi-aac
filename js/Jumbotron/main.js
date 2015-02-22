var submitLogin = function() {
	$('form.login-form').on('submit', function(event) {
		event.preventDefault();
		var username = $('.login-username').val();
		var password = sha1($('.login-password').val());
		if(login(username, password)) {

		}
	});
}

var login = function(username, password) {
	var postData = {"username": username, "password": password}
	$.ajax({
		url: '/ajax/login',
		type: "POST",
		data: postData
	}).done(function(response) {
		return response;
	});
}

$(document).ready(function() {
	submitLogin();
});