/**
 * Created by mvl on 11.01.2017.
 */

function move() {
	var elem = document.getElementById("myBar");
	var resetButton = document.getElementById('reset');
	var width = 6;
	var id = setInterval(frame, 100);
	function frame() {
		if (width >= 100) {
			clearInterval(id);
			resetButton.removeAttribute('disabled');
			resetButton.onclick = function () {
				elem.setAttribute('aria-valuenow', '6');
				elem.style.width = '6%';
				elem.innerHTML = '6%';
				resetButton.setAttribute('disabled','disabled');
			};

		} else {
			width++;
			elem.setAttribute('aria-valuenow', width);
			elem.style.width = width + '%';
			elem.innerHTML = width  + '%';
		}
	}
}
$(function () {
	$('div').each(function () {
		if ($(this).attr('id') == 'block'){
			$(this).html('');
		}
	});
});



