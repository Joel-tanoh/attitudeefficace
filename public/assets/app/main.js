$(document).ready(function(){
 
  function getHttpRexquest () {
		if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+...
			return new XMLHttpRequest();
		}
		else if (window.ActiveXObject) { // IE 6 et antérieurs
			return ActiveXObject("Microsoft.XMLHTTP");
		}
	}
  
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
    height: 600,
    lang: 'fr-FR',
    toolbar: [
      // [groupName, [list of button]]
      ['fontname', ['fontname'] ],
      ['fontsize', ['fontsize'] ],
      ['style', ['bold', 'italic', 'underline', 'clear'] ],
      ['font', ['strikethrough', 'superscript', 'subscript'] ],
      ['color', ['color'] ],
      ['height', ['height'] ],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table'] ],
      ['insert', ['link',] ],
      ['view', ['code', 'help'] ],
      ['undo', ['undo'] ],
      ['redo', ['redo'] ],
    ],
  });

});