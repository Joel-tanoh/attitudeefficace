$(document).ready(function(){

	// Afficher le sous-menu du bouton dans la barre supérieure
	$('#btnAdministrateurIcon').click( function () {
		$('#btnAdministrateurContent').toggle('fast');
	});

	// Afficher le sous-menu du bouton 'créer' de la side-bar
	$('.add-button-icon').click( function () {
		$('.add-button-content').toggle('fast');
  });

});