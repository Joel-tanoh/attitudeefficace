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

	function executeAjax (elementId) {
		let button = document.getElementById(elementId)
		let xhr = getHttpRexquest()

		if (button) {
			button.addEventListener("click", function (event) {
				event.preventDefault();

				xhr.open("GET", this.href);
				xhr.responseType = "json";
				xhr.send();
		
				// Dès que la reponse est prête
				xhr.onload = function () {
					if (xhr.status == 200) {
						let result = xhr.response;
						alert("Requête bien exécutée")
					} else {
						alert("Erreur " + xhr.status + " " + xhr.statusText);
					}
				}
		
				// Si la requête échoue
				xhr.onerror = function () {
					alert("La requête a échoué !")
				}
		
				// Pendant le téléchargement
				xhr.onprogress = function (event2){
					// Si la requête à une longueur calculable
					if (event2.lengthComputable) {
						alert(event2.loaded + " octets recus sur un total de " + event2.total);
					}
				}
			})
		}

	};
	
	executeAjax("postButton")
	executeAjax("unpostButton")
	
});