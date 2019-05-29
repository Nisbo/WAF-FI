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
		sendToReceiver($(event.target).attr('id'), $(event.target).attr('objectid'));
	});

	// there is no logo available, click on an A tag
	$('.noImageAvailable').on('click', function (){
		sendToReceiver($(event.target).attr('id'), $(event.target).attr('objectid'));
	});


	$('.navigationbuttons').on('click', 'img', function (){
		sendToReceiverButton($(event.target).attr('id'), $(event.target).attr('objectid'));
	});


	function sendToReceiver(channel, objectID){
		$.ajax({
			type: "GET",
			url: "hook/waffi_" + objectID + "/php/WafFiRequest.php",
			data: "kanal=" + channel,
			success: function () {
				//alert("Test " + channel);
			}
		});
	}

	function sendToReceiverButton(buttonCode, objectID){
		$.ajax({
			type: "GET",
			url: "hook/waffi_" + objectID + "/php/WafFiRequest.php",
			data: "button=" + buttonCode,
			success: function () {
				//alert("Test " + buttonCode);
			}
		});
	}
});
