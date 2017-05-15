/**
 * Created by mvl on 13.01.2017.
 */

/*var button = document.getElementById('create');

button.addEventListener('click', function (e) {
	e.preventDefault();
	e.target.setAttribute('class','btn btn-success');
	e.target.innerHTML = 'OK';
});*/

(function () {
	var httpRequest;
	var button = document.getElementById('create');
	var rt;

	button.disabled = false;

	button.onclick = function (e) {
		makeRequest('/pdf/create/1624');
		this.innerHTML = 'Загружаю...';
		this.disabled = true;
	};

	function makeRequest(url) {
		httpRequest = new XMLHttpRequest();

		if(!httpRequest){
			alert('Giving up :( Cannot create an XMLHTTP instance');
			return false;
		}

		httpRequest.onreadystatechange = alertContents;
		httpRequest.open('GET', url);
		httpRequest.send();
	}

	function alertContents() {
		if (httpRequest.readyState === XMLHttpRequest.DONE) {
			button.innerHTML = 'Готово!';

			if (httpRequest.status === 200) {
				var response = JSON.parse(httpRequest.responseText);
				console.log(response);
				if (confirm('Открыть файл: ' + response._link)){
					location.href = '/' + response._link;
				}
			} else {
				alert(httpRequest.status + ': ' + httpRequest.statusText);
			}
		}
	}
})();
