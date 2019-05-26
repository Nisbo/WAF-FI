<?php
#################################################################################################################################
# Addon:	WAF-FI --> WAF Fernseh Interface
# Version:	1.0
# Date:		16.05.2019
# Autor:	Nisbo
# Based on an idea by: Fonzo
# https://www.symcon.de/forum/threads/31582-Tastenfeld-Navigationswippe-dynamische-Webseiten-im-Webfront-darstellen
#################################################################################################################################

header('Content-type: text/xml');
header('Cache-Control: must-revalidate, pre-check=0, no-store, no-cache, max-age=0, post-check=0');

// WAF-FI --> WAF Fernseh Interface
// Kanal_123_SignleDigits_Enter_EnterCode_Sender

$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
$xml .= '<xml_content>';

if (isset($_GET["kanal"]))	{
	$config = explode("_", $_GET["kanal"]);

	// Single Digits, should be the standard
	if($config[2] == "1"){
		foreach (str_split($config[1]) as $key=>$val) {
			if ($key!==3) {
				LHD_Send($config[5], $val);
				// maybe we need a small delay here
			}
		}
	}else{
		LHD_Send($config[5], $config[1]);
	}

	// ENTER Button and ENTER Code
	if($config[3] == "1") LHD_Send($config[5], $config[4]);

	$xml .= "<action>kanal</action>";
}

// Button_123_Sender
if (isset($_GET["button"]))	{
	$config = explode("_", $_GET["button"]);

	// OnOff
	if($config[0] == "ButtonOO"){
		HarmonyHub_startActivity($config[2], $config[1]);
	}else{
		LHD_Send($config[2], $config[1]);
	}

	$xml .= "<action>button</action>";
}

$xml .= '</xml_content>';

echo $xml;

?>
