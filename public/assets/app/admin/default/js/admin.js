$(document).ready(function(){
	
	function getHttpRexquest () {
		if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+...
			return new XMLHttpRequest();
		}
		else if (window.ActiveXObject) { // IE 6 et antérieurs
			return ActiveXObject("Microsoft.XMLHTTP");
		}
	}

	// Faire apparaître le sous-menu caché lors du clic sur l'avatar dans la barre supérieure
	$('#btnUserIcon').click( function () {
		$('#btnUserContent').toggle('fast');
	});

	// Faire apparaître le sous-menu du bouton 'créer' de la navbar
	$('.add-button-icon').click( function () {
		$('.add-button-content').toggle('fast');
	});

	
	let sidebarShadow = document.getElementById("sidebarShadow")
	let checkSidebar = document.getElementById("check")

	checkSidebar.addEventListener("click", function(){
		// if (checkSidebar.checked === true) {
		// 	alert("la sidebar est ouverte")
		// } else {
		// 	alert("La sidebar est fermée")
		// }
	})
	
});