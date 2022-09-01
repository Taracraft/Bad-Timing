function bilderdelete() {
  let confirmAction = confirm("Are you sure to execute this action?");
  if (confirmAction) {
    alert("Action successfully executed");
  } else {
    alert("Action canceled");
  }
}
function copyToClipBoard() {

var content = document.getElementById('textArea');

content.select();
document.execCommand('copy');

alert("Copied!");
}
function copyToClipboard(text) {
var inputc = document.body.appendChild(document.createElement("input"));
inputc.value = window.location.href;
inputc.focus();
inputc.select();
document.execCommand('copy');
inputc.parentNode.removeChild(inputc);
alert("URL Copied.");
}