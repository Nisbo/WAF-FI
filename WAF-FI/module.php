<?
#################################################################################################################################
# Addon:	WAF-FI --> WAF Fernseh Interface
# Version:	1.0
# Date:		16.05.2019
# Autor:	Nisbo
# Based on an idea by: Fonzo
# https://www.symcon.de/forum/threads/31582-Tastenfeld-Navigationswippe-dynamische-Webseiten-im-Webfront-darstellen
#################################################################################################################################
// https://www.iconfinder.com/iconsets/simplicio
// https://www.iconfinder.com/iconsets/e-commerce-icon-set
// https://www.iconfinder.com/iconsets/developer-set-3

require_once __DIR__ . '/../libs/WafFiHelper.php';

// Klassendefinition
class IPS_Waf_FernsehInterface extends IPSModule {



	public function Create() {
		//Never delete this line!
		parent::Create();

		$this->RegisterPropertyString("backButton",						"Back");
		$this->RegisterPropertyInteger("channelDeviceObjectID",			0);
		$this->RegisterPropertyString("channelDown",					"ChannelDown");
		$this->RegisterPropertyString("channelListFileName",			"Kanalliste.csv");
		$this->RegisterPropertyString("channelUp",						"ChannelUp");
		$this->RegisterPropertyString("colorButtonBlue",				"Blue");
		$this->RegisterPropertyString("colorButtonGreen",				"Green");
		$this->RegisterPropertyString("colorButtonRed",					"Red");
		$this->RegisterPropertyString("colorButtonYellow",				"Yellow");
		$this->RegisterPropertyString("directionDown",					"DirectionDown");
		$this->RegisterPropertyString("directionLeft",					"DirectionLeft");
		$this->RegisterPropertyString("directionRight",					"DirectionRight");
		$this->RegisterPropertyString("directionUp",					"DirectionUp");
		$this->RegisterPropertyInteger("harmonyHubObjectID",			0);
		$this->RegisterPropertyInteger("harmonyHubStartActivityOn",		0);
		$this->RegisterPropertyInteger("harmonyHubStartActivityOff",	0);
		$this->RegisterPropertyString("okButton",						"Select");
		$this->RegisterPropertyString("pathToCSV",						"../webfront/user/waffi");
		$this->RegisterPropertyString("pathToImages",					"user/waffi/images");
		$this->RegisterPropertyBoolean("sendENTERkey",					true);
		$this->RegisterPropertyString("sendENTERkeyCode",				"Select");
		$this->RegisterPropertyBoolean("sendSingleDigits",				true);
		$this->RegisterPropertyInteger("volumeDeviceObjectID",			0);
		$this->RegisterPropertyString("volumeDown",						"VolumeDown");
		$this->RegisterPropertyString("volumeMute",						"Mute");
		$this->RegisterPropertyString("volumeUp",						"VolumeUp");

		$this->RegisterVariableString("channelListHTML",				"Diese Variabel ins FrontEnd einbinden, zum Ändern bitte Config ausführen", "~HTMLBox", 1);
	}

	public function ApplyChanges() {
		//Never delete this line!
		parent::ApplyChanges();
	}



	/**
	* Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
	* Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
	*
	* ABC_MeineErsteEigeneFunktion($id);
	*
	*/
	public function generateHTMLcontent() {
		//echo $this->InstanceID;

		$channelDeviceObjectID  = $this->ReadPropertyString("channelDeviceObjectID");
		$sendSingleDigits       = $this->ReadPropertyString("sendSingleDigits");
		$sendENTERkey           = $this->ReadPropertyString("sendENTERkey");
		$sendENTERkeyCode       = $this->ReadPropertyString("sendENTERkeyCode");
		$channelListFileName    = $this->ReadPropertyString("channelListFileName");
						
		$pathToImages           = $this->ReadPropertyString("pathToImages");
		$pathToCSV              = $this->ReadPropertyString("pathToCSV");
		$channelDown            = $this->ReadPropertyString("channelDown");
		$channelUp              = $this->ReadPropertyString("channelUp");
		$directionDown          = $this->ReadPropertyString("directionDown");
		$directionLeft          = $this->ReadPropertyString("directionLeft");
		$directionRight         = $this->ReadPropertyString("directionRight");
		$directionUp            = $this->ReadPropertyString("directionUp");

		$volumeDeviceObjectID   = $this->ReadPropertyString("volumeDeviceObjectID");// 0 = same than Receiver (idea for next version)
		$volumeDown             = $this->ReadPropertyString("volumeDown");
		$volumeMute             = $this->ReadPropertyString("volumeMute");
		$volumeUp               = $this->ReadPropertyString("volumeUp");

		$colorButtonBlue        = $this->ReadPropertyString("colorButtonBlue");
		$colorButtonGreen       = $this->ReadPropertyString("colorButtonGreen");
		$colorButtonRed         = $this->ReadPropertyString("colorButtonRed");
		$colorButtonYellow      = $this->ReadPropertyString("colorButtonYellow");

		$backButton             = $this->ReadPropertyString("backButton");
		$okButton               = $this->ReadPropertyString("okButton");

		$harmonyHubObjectID         = $this->ReadPropertyString("harmonyHubObjectID");
		$harmonyHubStartActivityOn  = $this->ReadPropertyString("harmonyHubStartActivityOn");
		$harmonyHubStartActivityOff = $this->ReadPropertyString("harmonyHubStartActivityOff");


		if(substr($pathToImages, -1, 1) != "/") $pathToImages          .= "/"; // to make sure there is a slash at the end
		if(substr($pathToCSV, -1, 1) != "/")    $pathToCSV             .= "/"; // to make sure there is a slash at the end
		if($sendENTERkey == "")                 $sendENTERkey           = 0;
		if($sendSingleDigits == "")             $sendSingleDigits       = 0;
		if($volumeDeviceObjectID == 0)          $volumeDeviceObjectID   = $channelDeviceObjectID;

		/*
		// /var/lib/symcon/scripts
		echo getcwd() . "\n";
		$ipsObject = IPS_GetObject ($kanallisteObjectID);
		print_r($ipsObject);
		*/

		$importer  = new CsvImporter($pathToCSV . $channelListFileName, true);
		$data      = $importer->get();
		asort ($data);

		foreach($data as $key => $val){
			// Kanal_123_SignleDigits_Enter_EnterCode_channelDeviceObjectID
			$imageID = 'Kanal_' . $val['channelNumber'] . '_' . $sendSingleDigits . '_' . $sendENTERkey . '_' . $sendENTERkeyCode . '_' . $channelDeviceObjectID;

			if($val['channelImage'] == "") {
				$image = '<div class="noImageAvailable"><br /><a id="' . $imageID . '" >' . $val['channelName'] . '</a></div>';
			} else { 
				$image = '<img class="zapimage" src="' . $pathToImages . $val['channelImage'] . '" id="' . $imageID . '" alt="' . $val['channelName'] . '">';
			}

			$channelListHTML .= '<div class="zapbutton buttonMouseOver zaptab" id="' . $val['channelName'] . 'Zap">' . $image . '</div>';
		} 

		//print_r($data); 

		$css = '<style>

		.navright{
			float: left;
		}

		.navleft{
			float: left;
		}

		.zapbuttons {
			float: left;
			background-image: -webkit-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: -moz-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: -o-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: linear-gradient(145deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			width: 520px;
			border-radius: 30px;
			display: block;
			-webkit-box-shadow: 2px 2px 5px;
			box-shadow: 2px 2px 5px;
		}

		.navigationbuttons {
			clear: left;
			position: relative;
			margin-left: 20px;
			background-image: -webkit-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: -moz-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: -o-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			background-image: linear-gradient(145deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
			width:  520px;
			height: 520px;
			border-radius: 30px;
			display: block;
			-webkit-box-shadow: 2px 2px 5px;
			box-shadow: 2px 2px 5px;
		}

		.zapbutton {
			border-radius: 13px;
			-webkit-box-shadow: 4px 4px 7px #272424;
			box-shadow: 4px 4px 7px #272424;
			text-align: center;
			vertical-align: middle;
			color: #D6D8CF;
			font-family: acme;
			font-style: normal;
			font-weight: 400;
			font-size: large;
			text-shadow: 2px 2px 4px #252323;
			background-repeat: no-repeat;
			background-size: 100% 100%;
			width:  80px;
			height: 80px;
		}

		.zaptab {
			margin-top:    20px;
			margin-left:   20px;
			margin-bottom:  0px;
		}

		.zapimage {
			margin-top:    5px;
			margin-bottom: 5px;
		}

		.noImageAvailable {

		}

		.zaptabbottom {
			height: 20px;
		}


		.buttonMouseOver {
			display: inline-block;
			vertical-align: middle;
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
			box-shadow: 0 0 1px rgba(0, 0, 0, 0);
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			-moz-osx-font-smoothing: grayscale;
			-webkit-transition-duration: 0.3s;
			transition-duration: 0.3s;
			-webkit-transition-property: box-shadow;
			transition-property: box-shadow;
			box-shadow: 0 0 8px rgba(0, 0, 0, 0.6);
		}
		.buttonMouseOver:hover, .buttonMouseOver:focus, .buttonMouseOver:active {
			box-shadow: 0 0 18px rgba(0, 0, 0, 0.6);
		}


		#volumeUp {
			position: absolute;
			top: 100px;
		}
		#volumeMute {
			position: absolute;
			top: 200px;
		}
		#volumeDown {
			position: absolute;
			top: 300px;
		}


		#channelUp {
			position: absolute;
			left: 400px;
			top: 100px;
		}
		#channelDown {
			position: absolute;
			top: 200px;
			left: 400px;
		}
		#buttonBack {
			position: absolute;
			top: 300px;
			left: 400px;
		}


		#buttonRed {
			position: absolute;
			top: 400px;
			left: 50px;
		}
		#buttonYellow {
			position: absolute;
			top: 400px;
			left: 150px;
		}
		#buttonGreen {
			position: absolute;
			top: 400px;
			left: 250px;
		}
		#buttonBlue {
			position: absolute;
			top: 400px;
			left: 350px;
		}


		#buttonLeft {
			position: absolute;
			top: 200px;
			left: 100px;
		}
		#buttonUp {
			position: absolute;
			left: 200px;
			top: 100px;
		}
		#buttonRight {
			position: absolute;
			top: 200px;
			left: 300px;
		}
		#buttonOK {
			position: absolute;
			top: 200px;
			left: 200px;
		}
		#buttonDown {
			position: absolute;
			top: 300px;
			left: 200px;
		}


		#buttonOn {
			position: absolute;
			left: 100px;
		}
		#buttonOff {
			position: absolute;
			left: 300px;
		}

		#wrapper {
			margin: 0 auto;
		}
		</style>

		<script type="text/javascript" src="user/waffi/js/jquery.js"></script>
		<script type="text/javascript" src="user/waffi/js/jquery.mobile-1.4.5.min.js"></script>
		<script type="text/javascript" src="user/waffi/js/jquery.ajax-ips-kanalliste.js"></script>

		';

		// Button_123_Sender
		// Button_123_' . $volumeDeviceObjectID

		$channelListHTML = $css . '

		<div id="wrapper">
			<div class="navleft">
				<section class="zapbuttons">
					' . $channelListHTML . '
					<div class="zaptabbottom"></div>
				</section>
			</div>
			<div class="navright">
				<section class="navigationbuttons">
					<div class="zapbutton buttonMouseOver zaptab" id="volumeUp"><img class="zapimage"       src="' . $pathToImages . 'volumeUp.png"    id="Button_'.$volumeUp.'_'.$volumeDeviceObjectID.'"       alt="volumeUp"></div>
					<div class="zapbutton buttonMouseOver zaptab" id="volumeMute"><img class="zapimage"     src="' . $pathToImages . 'volumeMute.png"  id="Button_'.$volumeMute.'_'.$volumeDeviceObjectID.'"     alt="volumeMute"></div>
					<div class="zapbutton buttonMouseOver zaptab" id="volumeDown"><img class="zapimage"     src="' . $pathToImages . 'volumeDown.png"  id="Button_'.$volumeDown.'_'.$volumeDeviceObjectID.'"     alt="volumeDown"></div>
					<div class="zapbutton buttonMouseOver zaptab" id="channelUp"><img class="zapimage"      src="' . $pathToImages . 'channelUp.png"   id="Button_'.$channelUp.'_'.$channelDeviceObjectID.'"     alt="channelUp"></div>
					<div class="zapbutton buttonMouseOver zaptab" id="channelDown"><img class="zapimage"    src="' . $pathToImages . 'channelDown.png" id="Button_'.$channelDown.'_'.$channelDeviceObjectID.'"   alt="channelDown"></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonBack"><img class="zapimage"     src="' . $pathToImages . 'ButtonBack.png"  id="Button_'.$backButton.'_'.$channelDeviceObjectID.'"    alt="ButtonBack"></div>

					<div class="zapbutton buttonMouseOver zaptab" id="buttonLeft"><img class="zapimage"     src="' . $pathToImages . 'ButtonLeft.png"   id="Button_'.$directionLeft.'_'.$channelDeviceObjectID.'"    alt="left"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonUp"><img class="zapimage"       src="' . $pathToImages . 'ButtonUp.png"     id="Button_'.$directionUp.'_'.$channelDeviceObjectID.'"      alt="up"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonRight"><img class="zapimage"    src="' . $pathToImages . 'ButtonRight.png"  id="Button_'.$directionRight.'_'.$channelDeviceObjectID.'"   alt="right"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonOK"><img class="zapimage"       src="' . $pathToImages . 'ButtonOK.png"     id="Button_'.$okButton.'_'.$channelDeviceObjectID.'"         alt="enter"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonDown"><img class="zapimage"     src="' . $pathToImages . 'ButtonDown.png"   id="Button_'.$directionDown.'_'.$channelDeviceObjectID.'"    alt="down"/></div>

					<div class="zapbutton buttonMouseOver zaptab" id="buttonRed"><img class="zapimage"      src="' . $pathToImages . 'ButtonRed.png"    id="Button_'.$colorButtonRed.'_'.$channelDeviceObjectID.'"       alt="red"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonYellow"><img class="zapimage"   src="' . $pathToImages . 'ButtonYellow.png" id="Button_'.$colorButtonYellow.'_'.$channelDeviceObjectID.'"    alt="yellow"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonGreen"><img class="zapimage"    src="' . $pathToImages . 'ButtonGreen.png"  id="Button_'.$colorButtonGreen.'_'.$channelDeviceObjectID.'"     alt="green"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonBlue"><img class="zapimage"     src="' . $pathToImages . 'ButtonBlue.png"   id="Button_'.$colorButtonBlue.'_'.$channelDeviceObjectID.'"      alt="blue"/></div>

					<div class="zapbutton buttonMouseOver zaptab" id="buttonOn"><img class="zapimage"       src="' . $pathToImages . 'ButtonOn.png"     id="ButtonOO_'.$harmonyHubStartActivityOn.'_'.$harmonyHubObjectID.'"  alt="On"/></div>
					<div class="zapbutton buttonMouseOver zaptab" id="buttonOff"><img class="zapimage"      src="' . $pathToImages . 'ButtonOff.png"    id="ButtonOO_'.$harmonyHubStartActivityOff.'_'.$harmonyHubObjectID.'" alt="Off"/></div>
				</section>
			</div>
		</div>

		';

		//SetValue($channelListHTMLid, $channelListHTML);

		SetValue($this->GetIDForIdent("channelListHTML"), $channelListHTML);

		//echo $channelListHTML;



	}

}
?>
