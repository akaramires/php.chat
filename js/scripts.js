/**
 * User: Abdurayimov Elmar
 * Date: 11/20/13
 * Time: 12:06 AM
 */

$(function () {
	$(document).ready(function () {
		load_msgs();
		setInterval(function () {
			load_msgs();
		}, 5000);
	});

	$("#btn-chat").click(function (event) {
		send_msg();
		event.preventDefault();
	});
	$("#input-msg").keypress(function (e) {
		code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			send_msg();
			e.preventDefault();
		}
	});

	function send_msg() {
		var _msg = $("#input-msg").val();
		if ($.trim(_msg).length) {
			$.ajax({
				url    : "/check.php",
				type   : "POST",
				data   : {
					type: "msg",
					text: encodeURIComponent(_msg)
				},
				success: function (response) {
					load_msgs();
				}
			});
		}
		$("#input-msg").val("");
	}

	function load_msgs() {
		$.ajax({
			url    : "/check.php",
			type   : "POST",
			data   : {
				type: "all"
			},
			success: function (response) {
				data = JSON.parse(response);

				$(".chat-msgs > ul.chat").html(data.msg);

				var users = "";
				for(i in data.users) {
					users += '<span class="label label-success">'+data.users[i]+'</span>';
				}
				$(".online-users").html(users);

				scroll_down();
			}
		});
	}

	function scroll_down() {
		var wtf = $('.chat-msgs');
		var height = wtf[0].scrollHeight;
		wtf.scrollTop(height);
	}

});