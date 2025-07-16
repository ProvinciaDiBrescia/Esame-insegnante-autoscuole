function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent( function(){ add_print_link( 'nav' ) } );

function add_print_link( id ){
  if( !document.getElementById ||
      !document.getElementById( id ) ) return;

  // add extra functions to page tools list
  var print_page = document.getElementById( id );

  // create print link
  var print_function = document.createElement('p');
  print_function.className = 'print-link';
  print_function.onclick = function(){ print_preview(); return false; };
  print_function.appendChild( document.createTextNode( 'Stampa la pagina' ) );

}

function print_preview() {
	// Switch the stylesheet
	setActiveStyleSheet('Print Preview');
	
	// Create preview message
	add_preview_message();

	// Print the page
	window.print();
}

function add_preview_message(){
if (document.getElementById('content')) {
	var main_content = document.getElementById('content');
} else {
	var main_content = document.getElementById('contentservizi');
}
var main_body = main_content.parentNode;

	if (document.getElementById){
		
		var preview_message = document.createElement('div');
		preview_message.id = 'preview-message';
	
		// Create Heading
		var preview_header = document.createElement('h3');
		var preview_header_text = document.createTextNode('Anteprima di stampa della pagina');
		preview_header.appendChild(preview_header_text);
		
		// Create paragraph
		var preview_para = document.createElement('p');
		var preview_para_text = document.createTextNode('');
		
		var cancel_function_link = document.createElement('a');
			cancel_function_link.onclick = function(){ cancel_print_preview(); return false; };
			cancel_function_link.setAttribute('href', '#');	
		var cancel_function_link_text = document.createTextNode('Torna alla pagina originale.');
		cancel_function_link.appendChild(cancel_function_link_text);
		preview_para.appendChild(preview_para_text); //
		preview_para.appendChild(cancel_function_link);
		
		// Put it all toegether
		preview_message.appendChild(preview_header); 
		preview_message.appendChild(preview_para);
		main_body.insertBefore(preview_message, main_content);

	}
}

function cancel_print_preview() {
	// Destroy the preview message
	var print_preview = document.getElementById('preview-message');
	var main_body = print_preview.parentNode;
	main_body.removeChild(print_preview);
	
	// Switch back stylesheet
	setActiveStyleSheet('default');
}

function setActiveStyleSheet(title) {
   var i, a, main;
   for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
     if(a.getAttribute("rel").indexOf("style") != -1
        && a.getAttribute("title")) {
       a.disabled = true;
       if(a.getAttribute("title") == title) a.disabled = false;
     }
   }
}

function updateTips(tips,t) {
    tips.text(t).effect("highlight",{},1500);
}

function checkLength(tips,o,n,min,max) {
    if ( o.val().length > max || o.val().length < min ) {
        o.addClass('ui-state-error');
        updateTips(tips,"La lunghezza del campo <" + n + "> deve essere compresa fra "+min+" e "+max+".");
        o.focus();
        return false;
    } else {
        return true;
    }
}

function checkRegexp(tips,o,regexp,n) {
    if ( !( regexp.test( o.val() ) ) ) {
        o.addClass('ui-state-error');
        updateTips(tips,n);
        o.focus();
        return false;
    } else {
        return true;
    }
}

function checkEmpty(tips,o,n) {
    if ( o.val() == 0 ) {
        o.addClass('ui-state-error');
        updateTips(tips,"Il campo <" + n + "> non puo' essere vuoto.");
        o.focus();
        return false;
    } else {
        return true;
    }
}

function checkPassword(tips,o1,o2,n) {
    if ( o1.val() != o2.val() ) {
        o2.addClass('ui-state-error');
        updateTips(tips,"I campi <" + n + "> devono corrispondere.");
        o.focus();
        return false;
    } else {
        return true;
    }
}