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

	// Déclaration des variables
	let contentContainer = document.getElementById("containerWithFixedSidebarNavbar")
	let commandeSidebarButton = document.getElementById("check")
	let sidebarLinks = document.querySelectorAll(".sidebar a")

	// Pour gérer la marge interne gauche du container du content
	let manageSidebar = function () {
		commandeSidebarButton.addEventListener("click", function () {
			if (this.checked === false) {
				contentContainer.style.paddingLeft = "1.45rem";
			} else {
				contentContainer.style.paddingLeft = "17.5rem";
			}
		})
	}

	// Pour donner la classe active au lien concerné dans la sidebar
	let setSidebarLinkActive = function () {
		let activeUrl = document.URL
		let urlParts = activeUrl.split("/")
		let recomposedUrl = urlParts[0]

		for (var i = 1; i < 5; i++ ) {
			recomposedUrl += "/" + urlParts[i]
		}

		for(var i = 2; i < sidebarLinks.length; i++) {
			if (sidebarLinks[i].href == recomposedUrl) {
				sidebarLinks[i].classList.add('active')
			}
		}
	}
	
	manageSidebar()
	setSidebarLinkActive()
});