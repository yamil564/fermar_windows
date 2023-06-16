// JavaScript Document
$(document).ready(function(){
var textboxes = $(":input.data-entry");
if ($.browser.mozilla) {
$(textboxes).keypress (checkForEnter);
} else {
$(textboxes).keydown (checkForEnter);
}
function checkForEnter (event) {
  var bol = true;
  if (event.keyCode == 13) {
    currentBoxNumber = textboxes.index(this);
    if (textboxes[currentBoxNumber + 1] != null) {
        nextBox = textboxes[currentBoxNumber + 1]
        nextBox.focus();
        nextBox.select();
        event.preventDefault();
        bol = false;
    }
  }
return bol;
}	


});
 