'use strict';

var formData = document.forms.callBack;
var _formData$elements = formData.elements,
    email = _formData$elements.email,
    senderName = _formData$elements.senderName,
    message = _formData$elements.message,
    butSendEmail = _formData$elements.butSendEmail;

// email.oninput = sendersenderName.oninput = message.oninput = changeSubmitButtonState;
// email.onfocus = senderName.onfocus = message.onfocus = removeInvalidClass;
// email.onblur = senderName.onblur = message.onblur = addInvalidClasssIfNeeded;
// email.onchange = senderName.onchange = message.onchange = addInvalidClasssIfNeeded;
//Jquery

$('#email').keypress(changeSubmitButtonState);
$('#senderName').keypress(changeSubmitButtonState);
$('#message').keypress(changeSubmitButtonState);

$('#email').change(changeSubmitButtonState);
$('#senderName').change(changeSubmitButtonState);
$('#message').change(changeSubmitButtonState);

$('#email').focus(removeInvalidClass);
$('#senderName').focus(removeInvalidClass);
$('#message').focus(removeInvalidClass);

$('#email').blur(addInvalidClasssIfNeeded);
$('#senderName').blur(addInvalidClasssIfNeeded);
$('#message').blur(addInvalidClasssIfNeeded);

initForm();

function initForm() {
  formData.addEventListener("submit", function (evt) {
    if (formData.checkValidity() === false) {
      evt.preventDefault();
      alert("Form is invalid - submission prevented!");
      return false;
    }
    evt.preventDefault();
    sendEmail();
    document.getElementById("butSendEmail").disabled = true;
    formData.senderName.value = "";
    formData.email.value = "";
    formData.message.value = "";
    return false;
  });
}

function changeSubmitButtonState(event) {
  var element = event.target;
  if (everyInputIsValid(element)) {
    butSendEmail.disabled = false;
  } else {
    butSendEmail.disabled = true;
  }
}

function everyInputIsValid(element) {
  var inputs = Array.from(element.form.elements);
  return inputs.every(function (input) {
    return input.checkValidity();
  });
}

function addInvalidClasssIfNeeded(event) {
  var element = event.target;
  if (!element.checkValidity()) {
    element.classList.add("dirty");
  }
}

function removeInvalidClass(event) {
  event.target.classList.remove("dirty");
}


function sendEmail() {
  var url = "/sender.php";
  var method = 'POST';
  var json = JSON.stringify({
    name: senderName.value,
    email: email.value,
    message: message.value
  });

  var xhr = new XMLHttpRequest();

  xhr.open(method, url, true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function() {
    if (xhr.readyState != 4) return;
    if (xhr.status != 200) {
    document.getElementById("failed").className = "send-failed";
    } else {
    document.getElementById("successful").className = "send-successful";
    }
  }

  xhr.send(json);
}