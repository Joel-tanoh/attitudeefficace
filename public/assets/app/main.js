$(document).ready(function(){
 
  let xhr = function getHttpRexquest () {
		if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+...
			return new XMLHttpRequest();
		}
		else if (window.ActiveXObject) { // IE 6 et antérieurs
			return ActiveXObject("Microsoft.XMLHTTP");
		}
	}
  
  // Initialize Select2 Elements pour la liste des select des parents
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

  // Bootstrap Custom File Input pour customiser l'input de fichier
  bsCustomFileInput.init();

  (function () {
    $('#summernote').summernote({
      placeholder: 'Commencez à écrire...',
      tabsize: 2,
      height: 450,
      lang: 'fr-FR',
      toolbar: [
        ['style', ['style']],
        ['fontname', ['fontname'] ],
        ['fontsize', ['fontsize'] ],
        ['style', ['bold', 'italic', 'underline', 'clear'] ],
        ['font', ['strikethrough', 'superscript', 'subscript'] ],
        ['color', ['color'] ],
        ['height', ['height'] ],
        ['para', ['ul', 'ol', 'paragraph'] ],
        ['table', ['table'] ],
        ['insert', ['link', 'picture'] ],
        ['view', ['codeview', 'help'] ],
        ['undo', ['undo'] ],
        ['redo', ['redo'] ],
      ],
    });
  
    articleContentToModify = document.getElementById("articleContentToModify").innerHTML
    $('#summernote').summernote('pasteHTML', articleContentToModify);
  })();

});