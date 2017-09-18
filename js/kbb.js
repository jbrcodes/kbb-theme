/* File: js/kbb.js */

function kbb_toggleSmallMenu() {
  var x = document.getElementById('menu-primary-small');
  if (x.className.indexOf('w3-show') == -1) {
    x.className += ' w3-show';
  } else { 
    x.className = x.className.replace(' w3-show', '');
  }
}