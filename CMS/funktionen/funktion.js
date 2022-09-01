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