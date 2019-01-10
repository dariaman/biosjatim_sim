function close(){
  var validNavigation = false;

  $(document.body).on( "keypress keydown", function(e) {
     console.log(e.keyCode)
     if (e.keyCode == 116){
         validNavigation = true;
     }
  });


  $(document.body).on("mousedown", this, function (e) {
    console.log(e)
    if( e.button == 2 ) {
      validNavigation = true;
    }
  });

  $("a").on( "click", function() {
    validNavigation = true;
  });

  $( "body" ).on( "click", "a", function() {
    validNavigation = true;
  });

 $("form").on( "submit", function(e) {
   validNavigation = true;
 });

 $("button").on( "submit", function(e) {
   validNavigation = true;
 });

 $("input[type=submit]").bind("click", function() {
    validNavigation = true;
  });


  window.onbeforeunload = function(e) {
    console.log(validNavigation);
    if (!validNavigation) {
       $.get( "../dologout.php?msg=Sesi anda telah berakhir." );
    }
  };
}
