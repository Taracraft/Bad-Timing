function bilderdelete() {
    let confirmAction = confirm("Are you sure to execute this action?");
    if (confirmAction) {
      alert("Action successfully executed");
    } else {
      alert("Action canceled");
    }
  }