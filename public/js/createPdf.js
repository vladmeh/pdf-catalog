/**
 * Created by mvl on 13.01.2017.
 */

var button = document.getElementById('create');

button.addEventListener('click', function (e) {
	e.preventDefault();
	e.target.setAttribute('class','btn btn-success');
	e.target.innerHTML = 'OK';
});
