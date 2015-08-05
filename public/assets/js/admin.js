function InitializeAdminApp() {
	console.log("ADMIN: Initializing Administrator Application")
	$("button#btn_admin").click(function(e) {
		e.preventDefault();
		window.location.href = "/admin/";
	});
}