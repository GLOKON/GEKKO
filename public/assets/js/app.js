// Avoid `console` errors in browsers that lack a console.
(function() {
	var method;
	var noop = function () {};
	var methods = [
		'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
		'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
		'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
		'timeStamp', 'trace', 'warn'
	];
	var length = methods.length;
	var console = (window.console = window.console || {});

	while (length--) {
		method = methods[length];

		// Only stub undefined methods.
		if (!console[method]) {
			console[method] = noop;
		}
	}
}());


function GetHost() {
	var urlProtocol = "http://"
	if(window.location.protocol == 'https:')
		urlProtocol = "https://"

	return (urlProtocol + window.location.hostname);
}


function AddLinks(linkArray, lastID) {
	var newLastID = lastID;
	for(var i = 0; i < linkArray.length; i++) {
		var trHTML = '<tr data-rowID="' + linkArray[i].id + '" data-linkCode="' + linkArray[i].code + '" class="fresh">';
		trHTML += '<td><a href="' + (GetHost() + '/' + linkArray[i].code) + '" target="blank">' + linkArray[i].code + '</a></td>';
		trHTML += "<td>" + linkArray[i].clicks + "</td>";
		trHTML += "<td>" + linkArray[i].destination + "</td>";
		trHTML += '<td><div class="btn-group">';
		trHTML += '<button type="button" id="link_view" class="btn btn-success btn-sm" data-id="' + linkArray[i].id + '" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Opening"><i class="fa fa-eye"></i>&nbsp;View</button>';
		trHTML += '<button type="button" id="link_delete" class="btn btn-danger btn-sm" data-id="' + linkArray[i].id + '" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Deleting"><i class="fa fa-times"></i>&nbsp;Delete</button>';
		trHTML += "</div></td>";
		trHTML += "</tr>";
		$("table#link_list tbody tr:first").before(trHTML);
		if(linkArray[i].id > newLastID) {
			newLastID = linkArray[i].id;
		}
	}
	$("div#lastLinkID").data("linkid", newLastID);
}


function UpdateLinkList() {
	var lastInList = $("div#lastLinkID").data("linkid");
	$.ajax({
		type: "POST",
		cache: false,
		url : "/manage/ajax/link/getLatest",
		data : { lastid: lastInList},
		success: function(result) {
			if(typeof result.ok !== 'undefined') {
				AddLinks(result.ok.data.links, lastInList);
			}
		}
	});
}


function RevertButtonContent(e) {
	$(e).find("span").hide();
}


function ChangeButtonContent(e) {
	$(e).find("span").show("slide", { direction: "right" });
}


function RemoveOldActiveViewLink() {
	$('#link_list tr').removeClass("link-viewed");
}


function ShortenURL(e) {
	$(e).button('loading');

	//Reset alert boxes
	$("form#form_shorten_url div#error_box span#message").text("");
	$("form#form_shorten_url div#error_box").hide();

	var new_url = $('form#form_shorten_url input[name="url_input"]').val();

	$.ajax({
		type: "POST",
		cache: false,
		url : "/manage/ajax/link/shorten",
		data : { url: new_url},
		success: function(result) {
			if(typeof result.ok !== 'undefined') {
				$("#shorten_url_output").val(result.ok.data.url);
			}
		}
	}).fail(function(result) {
		if(typeof result.responseJSON.error !== 'undefined') {
			var errorBoxElement = $("form#form_shorten_url div#error_box");
			var errorBoxText = $("form#form_shorten_url div#error_box span#message");
			errorBoxText.text("");
			if(typeof result.responseJSON.error.data !== 'undefined') {
				for (var key in result.responseJSON.error.data.validator_messages) {
					var obj = result.responseJSON.error.data.validator_messages[key];
					errorBoxText.append("<br /> - " + obj[0]);
				}
			}
			errorBoxElement.show();
		}
	}).always(function() {
		$(e).button('reset');
	});
}


function ChangePassword(e) {
	$(e).button('loading');

	//Reset alert boxes
	$("form#form_change_pw div#error_box span#message").text("");
	$("form#form_change_pw div#error_box").hide();

	var password_old = $('form#form_change_pw input[name=old_password]').val();
	var password_new = $('form#form_change_pw input[name=new_password]').val();
	var password_new_confirm = $('form#form_change_pw input[name=new_password_confirm]').val();

	$.ajax({
		type: "POST",
		cache: false,
		url : "/manage/ajax/user/changePassword",
		data : { old_password: password_old, new_password: password_new, new_password_confirm: password_new_confirm},
		success: function(result) {
			if(typeof result.ok !== 'undefined') {
				$('form#form_change_pw input[name=old_password]').val("");
				$('form#form_change_pw input[name=new_password]').val("");
				$('form#form_change_pw input[name=new_password_confirm]').val("");
				window.location.href = "/manage/logout/";
			}
		}
	}).fail(function(result) {
		if(typeof result.responseJSON.error !== 'undefined') {
			var errorBoxElement = $("form#form_change_pw div#error_box");
			var errorBoxText = $("form#form_change_pw div#error_box span#message");
			errorBoxText.text("");
			if(typeof result.responseJSON.error.data !== 'undefined') {
				for (var key in result.responseJSON.error.data.validator_messages) {
					var obj = result.responseJSON.error.data.validator_messages[key];
					errorBoxText.append("<br /> - " + obj[0]);
				}
			}
			if(typeof result.responseJSON.error.code !== 'undefined') {
				if(result.responseJSON.error.code == "MISMATCH-PASSWORD") {
					errorBoxText.append("<br /> - Your old password is incorrect.");
				}
			}
			errorBoxElement.show();
		}
	}).always(function() {
		$(e).button('reset');
	});
}


function EditProfile(e) {
	$(e).button('loading');

	//Reset alert boxes
	$("form#form_edit_profile div#error_box span#message").text("");
	$("form#form_edit_profile div#error_box").hide();
	$("form#form_edit_profile div#success_box span#message").text("");
	$("form#form_edit_profile div#success_box").hide();

	var new_email = $('form#form_edit_profile input[name=email]').val();

	$.ajax({
		type: "POST",
		cache: false,
		url : "/manage/ajax/user/updateProfile",
		data : { email: new_email},
		success: function(result) {
			if(typeof result.ok !== 'undefined') {
				$("form#form_edit_profile div#success_box span#message").html("<br /> - Your email change is pending, please confirm the change on your new email.");
				$("form#form_edit_profile div#success_box").show();
			}
		}
	}).fail(function(result) {
		if(typeof result.responseJSON.error !== 'undefined') {
			var errorBoxElement = $("form#form_edit_profile div#error_box");
			var errorBoxText = $("form#form_edit_profile div#error_box span#message");
			errorBoxText.text("");
			if(typeof result.responseJSON.error.data !== 'undefined') {
				for (var key in result.responseJSON.error.data.validator_messages) {
					var obj = result.responseJSON.error.data.validator_messages[key];
					errorBoxText.append("<br /> - " + obj[0]);
				}
			}
			errorBoxElement.show();
		}
	}).always(function() {
		$(e).button('reset');
	});
}


function Click_View(e) {
	RemoveOldActiveViewLink();
	$(e).button('loading');
	var linkID = $(e).data("id");
	var urlCode = $('tr[data-rowID="' + linkID + '"]').data("linkcode");
	var redirectWindow = window.open(GetHost() + "/" + urlCode, '_blank');
    redirectWindow.location;
	$('tr[data-rowID="' + linkID + '"]').addClass("link-viewed");
	$(e).button('reset');
}


function Click_Delete(e) {
	var reallyDelete = confirm("Are you sure you want to delete this link?\nThis action is permanent.");
	if (reallyDelete == true) {
		var linkID = $(e).data("id");
		$(e).button('loading');
		$.ajax({
			type: "POST",
			cache: false,
			url : "/manage/ajax/link/delete",
			data : { linkid: linkID },
			success: function(result) {
				if(typeof result.ok !== 'undefined') {
					$('tr[data-rowID="' + linkID + '"]').fadeOut(500, function() { $(this).remove(); });
				}
			}
		}).always(function() {
			$(e).button('reset');
		});
	}
}


function InitializeApp() {
	setInterval(function() {
		UpdateLinkList();
	}, 2000);
	$("div#btns_controls button").hover(function() {
		// On mouse enter
		ChangeButtonContent(this);
	}, function() {
		// On mouse leave
		RevertButtonContent(this);
	});
	$("button#btn_logout").click(function(e) {
		e.preventDefault();
		window.location.href = "/manage/logout/";
	});
	$("#api_key_input").click(function(e) {
		e.preventDefault();
		$("#api_key_input").focus();
		$("#api_key_input").select();
	});
	$("#shorten_url_output").click(function(e) {
		e.preventDefault();
		$("#shorten_url_output").focus();
		$("#shorten_url_output").select();
	});
	$("button#btn_show_api_key").click(function(e) {
		e.preventDefault();
		$("#api_key_input").val("Retrieving...");
		var buttonElement = this;
		$(buttonElement).button('loading');
		$.ajax({
			type: "GET",
			cache: false,
			url : "/manage/ajax/user/getApiKey",
			success: function(result) {
				if(typeof result.ok !== 'undefined') {
					$("#api_key_input").val(result.ok.data.api_key);
					$("#btn_show_api_key").hide();
				} else {
					$("#api_key_input").val("Could not retrieve key, please try again");
				}
			}
		}).always(function() {
			$(buttonElement).button('reset');
		});
	});
	$('#modal_api_key').on('hidden.bs.modal', function () {
		$("#api_key_input").val("");
		$("#btn_show_api_key").show();
	});
	$('#modal_change_pw').on('hidden.bs.modal', function () {
		$("form#form_change_pw div#error_box span#message").text("");
		$("form#form_change_pw div#error_box").hide();
		$('form#form_change_pw input[name=old_password]').val("");
		$('form#form_change_pw input[name=password]').val("");
		$('form#form_change_pw input[name=password_confirm]').val("");
	});
	$('#modal_profile').on('hidden.bs.modal', function () {
		$("form#form_edit_profile div#error_box span#message").text("");
		$("form#form_edit_profile div#error_box").hide();
		$("form#form_edit_profile div#success_box span#message").text("");
		$("form#form_edit_profile div#success_box").hide();
	});
	$('#modal_shorten_url').on('hidden.bs.modal', function () {
		$("#shorten_url_input").val("");
		$("#shorten_url_output").val("");
	});
	$("button#btn_shorten_url").click(function(e) {
		e.preventDefault();
		ShortenURL(this);
	});
	$("button#btn_edit_profile").click(function(e) {
		e.preventDefault();
		EditProfile(this);
	});
	$("button#btn_gen_api_key").click(function(e) {
		e.preventDefault();
		var reallyGenerate = confirm("Are you sure you want to generate a new key?\nThis will permanently disable the previous key and make it unrecoverable.");
		if (reallyGenerate == true) {
			$("#api_key_input").val("Generating...");
			var buttonElement = this;
			$(buttonElement).button('loading');
			$.ajax({
				type: "POST",
				cache: false,
				url : "/manage/ajax/user/generateApiKey",
				success: function(result) {
					if(typeof result.ok !== 'undefined') {
						$("#api_key_input").val(result.ok.data.api_key);
					} else {
						$("#api_key_input").val("Could not generate new key, please try again");
					}
				}
			}).always(function() {
				$(buttonElement).button('reset');
			});
		}
	});
	$("button#btn_change_pw").click(function(e) {
		e.preventDefault();
		ChangePassword(this);
	});
	$("a#api_key_selectall").click(function(e) {
		e.preventDefault();
		$("#api_key_input").focus();
		$("#api_key_input").select();
	});
	$("a#shorten_url_selectall").click(function(e) {
		e.preventDefault();
		$("#shorten_url_output").focus();
		$("#shorten_url_output").select();
	});
	// Delegated Clicks
	$('table#link_list').on('click', 'button#link_view', function() {
		Click_View(this);
	});
	$('table#link_list').on('click', 'button#link_delete', function() {
		Click_Delete(this);
	});
}