$(document).ready(function(){
  
  var getXMLHttpRequest = function () {
    if (window.XMLHttpRequest) { // Mozilla, Safari, IE7+...
        httpRequest = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) { // IE 6 et antérieurs
        httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return httpRequest;
  }

  //== Customisation de input[type="file"] ==//
  // bsCustomFileInput.init();
  //=========================================//

  //=== Initialize Select2 Elements ==//
  $('.select2').select2();
  //==================================//

  //======== Editor CkEditor =========//
	ClassicEditor
    .create( document.querySelector( '#editor' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ]
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );
  //==================================//

});