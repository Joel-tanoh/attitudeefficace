$(document).ready(function(){
 
  // Initialize Select2 Elements
  $('.select2').select2();

  // Editor CkEditor
	// ClassicEditor
  //   .create( document.querySelector( '#editor' ), {
  //     toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
  //   } )
  //   .then( editor => {
  //     window.editor = editor;
  //   } )
  //   .catch( err => {
  //     console.error( err.stack );
  //   } );

  // Bootstrap Custom File Input
  bsCustomFileInput.init();
  
  // Summernote
  $('#summernote').summernote({
    placeholder: 'Commencez à écrire...',
    tabsize: 2,
    height: 300,
    lang: 'fr-FR'
  });

});