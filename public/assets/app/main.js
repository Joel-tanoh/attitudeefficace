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

  // var xhr = getXMLHttpRequest();

  // // Actualiser la page chaque seconde.
  // setInterval(actualiserPage, 5000)
  // function actualiserPage() {
  //   httpRequest.open("GET", document.location, true)
  //   httpRequest.send()
  //   httpRequest.onreadystatechange = function () {
  //     if (httpRequest.readyState === 4) {
  //       document.innerHTML = httpRequest.response;
  //     }
  //   }
  // }

  // Customisation de input[type="file"]
  bsCustomFileInput.init();

  //Initialize Select2 Elements
  $('.select2').select2();

  // Editor CkEditor
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

});