/**
 * Created by mvl on 11.01.2017.
 */
$(document).ready(function() {
	setInterval(timestamp, 1000);
});

function timestamp() {
	$.ajax({
		url: '/pdf/liveTime',
		success: function(data) {
			//console.log(data);
			$('#timestamp').html(data.time);
		}
	});
}