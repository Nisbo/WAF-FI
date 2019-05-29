/*
#################################################################################################################################
# Addon:	WAF-FI --> WAF Fernseh Interface
# Version:	1.0
# Date:		29.05.2019
# Autor:	Nisbo
# Based on an idea by: Fonzo
# https://www.symcon.de/forum/threads/31582-Tastenfeld-Navigationswippe-dynamische-Webseiten-im-Webfront-darstellen
#################################################################################################################################
*/

// console.log("channelList.js geladen");

$(document).ready(function(){
	$('.zapbuttons').on('click', 'img', function (){
		//alert('click!' + $(event.target).attr('id'));
		//alert('click!');
		sendToReceiver($(event.target).attr('id'));
	});

	// there is no logo available, click on an A tag
	$('.noImageAvailable').on('click', function (){
		sendToReceiver($(event.target).attr('id'));
	});


	$('.navigationbuttons').on('click', 'img', function (){
		sendToReceiverButton($(event.target).attr('id'));
	});


	function sendToReceiver(channel){
		$.ajax({
			type: "GET",
			url: "hook/waffi/php/WafFiRequest.php",
			data: "kanal=" + channel,
			success: function () {
				//alert("Test " + channel);
			}
		});
	}

	function sendToReceiverButton(buttonCode){
		$.ajax({
			type: "GET",
			url: "hook/waffi/php/WafFiRequest.php",
			data: "button=" + buttonCode,
			success: function () {
				//alert("Test " + buttonCode);
			}
		});
	}
});
