$(document).ready(function(){

	// Faire apparaître le sous-menu caché lors du clic sur l'avatar dans la barre supérieure
	$('#btnAdministrateurIcon').click( function () {
		$('#btnAdministrateurContent').toggle('fast');
	});

	// Faire apparaître le sous-menu du bouton 'créer' de la navbar
	$('.add-button-icon').click( function () {
		$('.add-button-content').toggle('fast');
  	});

});