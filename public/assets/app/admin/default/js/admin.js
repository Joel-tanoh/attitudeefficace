$(document).ready(function(){
	let domain = "http://attitudeefficace.com/admin";
	
	function getHttpRexquest () {
		if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+...
			return new XMLHttpRequest();
		}
		else if (window.ActiveXObject) { // IE 6 et antérieurs
			return ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	let xhr = getHttpRexquest();

	// Faire apparaître le sous-menu caché lors du clic sur l'avatar dans la barre supérieure
	$('#btnUserIcon').click( function () {
		$('#btnUserContent').toggle('fast');
	});

	// Faire apparaître le sous-menu du bouton 'créer' de la navbar
	$('.add-button-icon').click( function () {
		$('.add-button-content').toggle('fast');
	});

	// Déclaration des variables

	/**
	 * Pour gérer la marge interne gauche du container du content
	 */
	let manageSidebar = function () {
		let contentContainer = document.getElementById("containerWithFixedSidebarNavbar")
		let commandeSidebarButton = document.getElementById("check")

		commandeSidebarButton.addEventListener("click", function () {
			if (this.checked === false) {
				contentContainer.style.paddingLeft = "1.45rem";
			} else {
				contentContainer.style.paddingLeft = "17.5rem";
			}
		})
	}
	manageSidebar();

	/**
	 * Pour donner la classe active au lien concerné dans la sidebar
	 */
	let setSidebarLinkActive = function () {
		let sidebarLinks = document.querySelectorAll(".sidebar a")
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
	setSidebarLinkActive();

	/**
	 * Permet d'actualiser le nombre de visiteur
	 */
	let refreshVisitorOnlineNumber = function () {
		setInterval(function () {
			xhr.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("visitorsOnlineNumber").innerHTML = this.response
				}
			}
			xhr.open("GET", domain + "/visitors/online/number", true);
			xhr.send();
		} , 10*1000)
	}
	refreshVisitorOnlineNumber();

});