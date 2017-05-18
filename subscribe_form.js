'use strict';

var formData = document.forms.subscribe;
var _formData$elements = formData.elements,
    email = _formData$elements.email,
    senderName = _formData$elements.senderName,
    butSendEmail = _formData$elements.butSendEmail,
    javaScript = _formData$elements.javaScript,
    htmlCss = _formData$elements.htmlCss,
    php = _formData$elements.php;

// email.oninput = senderName.oninput = changeSubmitButtonState;
// email.onfocus = senderName.onfocus = removeInvalidClass;
// email.onblur = senderName.onblur  = addInvalidClasssIfNeeded;
$('#email').keypress(changeSubmitButtonState);
$('#senderName').keypress(changeSubmitButtonState);

$('#email').change(changeSubmitButtonState);
$('#senderName').change(changeSubmitButtonState);

$('#email').focus(removeInvalidClass);
$('#senderName').focus(removeInvalidClass);

$('#email').blur(addInvalidClasssIfNeeded);
$('#senderName').blur(addInvalidClasssIfNeeded);

initForm();

function initForm() {
  formData.addEventListener("submit", function (evt) {
    if (formData.checkValidity() === false) {
      evt.preventDefault();
      alert("Form is invalid - submission prevented!");
      return false;
    };
    if (formData.javaScript.checked || formData.htmlCss.checked || formData.php.checked) {
      evt.preventDefault();
      sendEmail();
      document.getElementById("alarma").className = "blok";
      document.getElementById("butSendEmail").disabled = true;
      formData.senderName.value = "";
      formData.email.value = "";
      formData.javaScript.checked = false;
      formData.htmlCss.checked = false;
      formData.php.checked = false;
      document.getElementById("label-html").classList.toggle("isSelected", false);
      document.getElementById("label-js").classList.toggle("isSelected", false);
      document.getElementById("label-php").classList.toggle("isSelected", false);
      return false;
    } else {
      evt.preventDefault();
      document.getElementById("alarma").className = "in";
      return false;
    }
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
    JavaScript: javaScript.checked,
    HTMLCSS: htmlCss.checked,
    PHP: php.checked
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