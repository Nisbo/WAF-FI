<?
#################################################################################################################################
# Addon:	WAF-FI --> WAF Fernseh Interface
# Version:	1.0
# Date:		27.05.2019
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
		$this->RegisterPropertyString("pathToCSV",						"/var/lib/symcon/webfront/user/waffi");
		$this->RegisterPropertyString("pathToImages",					"user/waffi/images");
		$this->RegisterPropertyBoolean("sendENTERkey",					true);
		$this->RegisterPropertyString("sendENTERkeyCode",				"Select");
		$this->RegisterPropertyBoolean("sendSingleDigits",				true);
		$this->RegisterPropertyInteger("volumeDeviceObjectID",			0);
		$this->RegisterPropertyString("volumeDown",						"VolumeDown");
		$this->RegisterPropertyString("volumeMute",						"Mute");
		$this->RegisterPropertyString("volumeUp",						"VolumeUp");
		$this->RegisterPropertyBoolean("useBouquet",					false);
		
		$this->RegisterPropertyInteger("designButtonBackGroundColor", 13884121); // #D3DAD9
		$this->RegisterPropertyInteger("designButtonWidth", 120); // 120
		$this->RegisterPropertyInteger("designButtonHeight", 80); // 80
		$this->RegisterPropertyInteger("designButtonEdge", 13); // 13
		$this->RegisterPropertyInteger("designButtonMarginTop", 5); // 5
		$this->RegisterPropertyInteger("designButtonMarginLeft", 5); // 5


		$this->RegisterVariableString("channelListHTML",				"Diese Variabel ins FrontEnd einbinden, zum Ändern bitte Config ausführen", "~HTMLBox", 1);
	}

	public function ApplyChanges() {
		//Never delete this line!
		parent::ApplyChanges();

		$this->RegisterHook("/hook/waffi");
	}


	/**
	* The Hook Functions are from the Symcon Demo Code
	*/
	private function RegisterHook($WebHook) {
		$ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");
		if(sizeof($ids) > 0) {
			$hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
			$found = false;
			foreach($hooks as $index => $hook) {
				if($hook['Hook'] == $WebHook) {
					if($hook['TargetID'] == $this->InstanceID)
						return;
					$hooks[$index]['TargetID'] = $this->InstanceID;
					$found = true;
				}
			}
			if(!$found) {
				$hooks[] = Array("Hook" => $WebHook, "TargetID" => $this->InstanceID);
			}
			IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
			IPS_ApplyChanges($ids[0]);
		}
	}


	/**
	* This function will be called by the hook control. Visibility should be protected!
	*/
	protected function ProcessHookData() {
		
		$root = realpath(__DIR__ . "/www");
		//reduce any relative paths. this also checks for file existance
		$path = realpath($root . "/" . substr($_SERVER['SCRIPT_NAME'], strlen("/hook/waffi/")));
		if($path === false) {
			http_response_code(404);
			die("File not found!");
		}
		
		if(substr($path, 0, strlen($root)) != $root) {
			http_response_code(403);
			die("Security issue. Cannot leave root folder!");
		}
		//check dir existance
		if(substr($_SERVER['SCRIPT_NAME'], -1) != "/") {
			if(is_dir($path)) {
				http_response_code(301);
				header("Location: " . $_SERVER['SCRIPT_NAME'] . "/\r\n\r\n");
				return;
			}
		}
		//append index
		if(substr($_SERVER['SCRIPT_NAME'], -1) == "/") {
			if(file_exists($path . "/index.html")) {
				$path .= "/index.html";
			} else if(file_exists($path . "/index.php")) {
				$path .= "/index.php";
			}
		}
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		if($extension == "php") {
			include_once($path);
		} else {
			header("Content-Type: ".$this->GetMimeType($extension));
			readfile($path);
		}
	}


	private function GetMimeType($extension) {
		$lines = file(IPS_GetKernelDirEx()."mime.types");
		foreach($lines as $line) {
			$type = explode("\t", $line, 2);
			if(sizeof($type) == 2) {
				$types = explode(" ", trim($type[1]));
				foreach($types as $ext) {
					if($ext == $extension) {
						return $type[0];
					}
				}
			}
		}
		return "text/plain";
	}


	public function generateHTMLcontent() {
		$channelDeviceObjectID  = $this->ReadPropertyInteger("channelDeviceObjectID");
		$sendSingleDigits       = $this->ReadPropertyBoolean("sendSingleDigits");
		$sendENTERkey           = $this->ReadPropertyBoolean("sendENTERkey");
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

		$volumeDeviceObjectID   = $this->ReadPropertyInteger("volumeDeviceObjectID");// 0 = same than Receiver (idea for next version)
		$volumeDown             = $this->ReadPropertyString("volumeDown");
		$volumeMute             = $this->ReadPropertyString("volumeMute");
		$volumeUp               = $this->ReadPropertyString("volumeUp");

		$colorButtonBlue        = $this->ReadPropertyString("colorButtonBlue");
		$colorButtonGreen       = $this->ReadPropertyString("colorButtonGreen");
		$colorButtonRed         = $this->ReadPropertyString("colorButtonRed");
		$colorButtonYellow      = $this->ReadPropertyString("colorButtonYellow");

		$backButton             = $this->ReadPropertyString("backButton");
		$okButton               = $this->ReadPropertyString("okButton");

		$harmonyHubObjectID         = $this->ReadPropertyInteger("harmonyHubObjectID");
		$harmonyHubStartActivityOn  = $this->ReadPropertyInteger("harmonyHubStartActivityOn");
		$harmonyHubStartActivityOff = $this->ReadPropertyInteger("harmonyHubStartActivityOff");

		$useBouquet				= $this->ReadPropertyBoolean("useBouquet");

$designButtonBackGroundColor = $this->ReadPropertyInteger("designButtonBackGroundColor");
$designButtonWidth = $this->ReadPropertyInteger("designButtonWidth");
$designButtonHeight = $this->ReadPropertyInteger("designButtonHeight");
$designButtonEdge = $this->ReadPropertyInteger("designButtonEdge");
$designButtonMarginTop = $this->ReadPropertyInteger("designButtonMarginTop");
$designButtonMarginLeft = $this->ReadPropertyInteger("designButtonMarginLeft");
		
		if(substr($pathToImages, -1, 1) != "/") $pathToImages          .= "/"; // to make sure there is a slash at the end
		if(substr($pathToCSV, -1, 1) != "/")    $pathToCSV             .= "/"; // to make sure there is a slash at the end
		if($sendENTERkey == "")                 $sendENTERkey           = 0;
		if($sendSingleDigits == "")             $sendSingleDigits       = 0;
		if($volumeDeviceObjectID == 0)          $volumeDeviceObjectID   = $channelDeviceObjectID;
		$channelListHTML = "";

		/*
		// /var/lib/symcon/scripts
		echo getcwd() . "\n";
		$ipsObject = IPS_GetObject ($kanallisteObjectID);
		print_r($ipsObject);
		*/

		#NAME Favourites (TV)
		#SERVICE 1:0:19:283d:3fb:1:c00000:0:0:0:
		#SERVICE 1:0:19:2b66:3f3:1:c00000:0:0:0:
		#SERVICE 1:0:19:ef10:421:1:c00000:0:0:0:
		#SERVICE 1:0:19:ef74:3f9:1:c00000:0:0:0:
		#SERVICE 1:0:19:ef75:3f9:1:c00000:0:0:0:

		if($useBouquet){
			$channelCount = 0;
			$importer  = new CsvImporter($pathToCSV . $channelListFileName, false, " "); // Space
			$data      = $importer->get();

			foreach($data as $key => $val){
				// Kanal_123_SignleDigits_Enter_EnterCode_channelDeviceObjectID
                if($val[0] != "#SERVICE") continue;
				$channelCount++;
				if(isset($val[2]) && $val[2] != "") $channelCount = $val[2];

				$imageID = 'Kanal_' . $channelCount . '_' . $sendSingleDigits . '_' . $sendENTERkey . '_' . $sendENTERkeyCode . '_' . $channelDeviceObjectID;
                $image = strtoupper(str_replace(":", "_", substr($val[1], 0, -1))) . ".png";
                $image = '<img class="zapimage" src="' . $pathToImages . $image . '" id="' . $imageID . '" alt="Kanal' . $channelCount . '">';

				$channelListHTML .= '<div class="buttonMouseOver zapbutton" id="Kanal' . $channelCount . 'Zap">' . $image . '</div>';
                //echo '<div class="zapbutton buttonMouseOver zaptab" id="Kanal' . $channelCount . 'Zap">' . $image . '</div>' . PHP_EOL;
			}
			echo "Bouquet importiert." . dechex($designButtonBackGroundColor);
		}else{
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

				$channelListHTML .= '<div class="buttonMouseOver zapbutton" id="' . $val['channelName'] . 'Zap">' . $image . '</div>';
			} 
		}

		//print_r($data); 

		$cssInclude = '
		<link type="text/css" href="/hook/waffi/css/waffi.css" rel="stylesheet">

<style>

.zapbuttons {
	float: left;
	/*
	background-image: -webkit-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: -moz-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: -o-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: linear-gradient(145deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	*/
	background-image:none !important;
	width: 505px;
	border-radius: 13px;
	display: block;
	-webkit-box-shadow: 0px 0px 2px 3px;
	box-shadow: 0px 0px 2px 3px;
}


.navigationbuttons {
	clear: left;
	position: relative;
	margin-left: 20px;
	/*
	background-image: -webkit-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: -moz-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: -o-linear-gradient(305deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	background-image: linear-gradient(145deg,rgba(255,255,255,1.00) 0%,rgba(69,57,57,1.00) 100%);
	*/
	background-image:none !important;
	width:  490px;
	height: 515px;
	border-radius: 13px;
	display: block;
	-webkit-box-shadow: 0px 0px 2px 3px;
	box-shadow: 0px 0px 2px 3px;
}


.zapbutton {
	border-radius: '.$designButtonEdge.'px;
	' . ($designButtonBackGroundColor != -1 ? ("background-color: #".dechex($designButtonBackGroundColor).";") : "") . '

	-webkit-box-shadow: 4px 4px 7px 3px #272424;
	-moz-box-shadow:    4px 4px 7px 3px #272424;
	box-shadow:         4px 4px 7px 3px #272424;

	text-align:			center;
	vertical-align:		middle;
	align-items:		center;
	justify-content:	center;

	color:			#D6D8CF;
	font-family:	acme;
	font-style:		normal;
	font-weight:	400;
	font-size:		large;
	text-shadow:	2px 2px 4px #252323;

	width:  '.$designButtonWidth.'px;
	height: '.$designButtonHeight.'px;

	margin-top:  '.$designButtonMarginTop.'px;
	margin-left: '.$designButtonMarginLeft.'px;
}


.zapimage {
	margin:		auto;
	position:	absolute;
	/* yes it makes no sense but it vertically center the image */
	right:		0;
	top:		0;
	bottom:		0;
	left:		0;
}


.zapbuttonR {
	border-radius: 13px;

	/*
	-webkit-box-shadow: 4px 4px 7px #272424;
	box-shadow: 4px 4px 7px #272424;
	*/

background-color: #D3DAD9;
-webkit-box-shadow: 4px 4px 7px #272424;
-moz-box-shadow: 4px 4px 7px #272424;
box-shadow: 4px 4px 7px #272424;

	text-align: center;
	vertical-align: middle;
	align-items:center;
	justify-content: center;
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

	margin-top:    20px;
	margin-left:   20px;
}




.zaptabbottom {
	height: '.$designButtonMarginTop.'px;
}
</style>
		';
		
		$jsInclude = "
		<script>		
		[
		  '/hook/waffi/js/jquery.js',
		  '/hook/waffi/js/jquery.ajax-ips-kanalliste.js'
		].forEach(function(src) {
		  var script = document.createElement('script');
		  script.src = src;
		  script.async = false;
		  document.head.appendChild(script);
		});		
		</script>
		";

		// Button_123_Sender
		// Button_123_' . $volumeDeviceObjectID

		$channelListHTML = $cssInclude . $jsInclude . '
		<div id="wrapper">
			<div class="navleft">
				<section class="zapbuttons">
					' . $channelListHTML . '
					<div class="zaptabbottom"></div>
				</section>
			</div>
			<div class="navright">
				<section class="navigationbuttons">
					<div class="zapbuttonR buttonMouseOver" id="volumeUp"><img class="zapimage"       src="hook/waffi/images/volumeUp.png"     id="Button_'.$volumeUp.'_'.$volumeDeviceObjectID.'"       alt="volumeUp"></div>
					<div class="zapbuttonR buttonMouseOver" id="volumeMute"><img class="zapimage"     src="hook/waffi/images/volumeMute.png"   id="Button_'.$volumeMute.'_'.$volumeDeviceObjectID.'"     alt="volumeMute"></div>
					<div class="zapbuttonR buttonMouseOver" id="volumeDown"><img class="zapimage"     src="hook/waffi/images/volumeDown.png"   id="Button_'.$volumeDown.'_'.$volumeDeviceObjectID.'"     alt="volumeDown"></div>
					<div class="zapbuttonR buttonMouseOver" id="channelUp"><img class="zapimage"      src="hook/waffi/images/channelUp.png"    id="Button_'.$channelUp.'_'.$channelDeviceObjectID.'"     alt="channelUp"></div>
					<div class="zapbuttonR buttonMouseOver" id="channelDown"><img class="zapimage"    src="hook/waffi/images/channelDown.png"  id="Button_'.$channelDown.'_'.$channelDeviceObjectID.'"   alt="channelDown"></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonBack"><img class="zapimage"     src="hook/waffi/images/ButtonBack.png"   id="Button_'.$backButton.'_'.$channelDeviceObjectID.'"    alt="ButtonBack"></div>

					<div class="zapbuttonR buttonMouseOver" id="buttonLeft"><img class="zapimage"     src="hook/waffi/images/ButtonLeft.png"   id="Button_'.$directionLeft.'_'.$channelDeviceObjectID.'"    alt="left"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonUp"><img class="zapimage"       src="hook/waffi/images/ButtonUp.png"     id="Button_'.$directionUp.'_'.$channelDeviceObjectID.'"      alt="up"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonRight"><img class="zapimage"    src="hook/waffi/images/ButtonRight.png"  id="Button_'.$directionRight.'_'.$channelDeviceObjectID.'"   alt="right"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonOK"><img class="zapimage"       src="hook/waffi/images/ButtonOK.png"     id="Button_'.$okButton.'_'.$channelDeviceObjectID.'"         alt="enter"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonDown"><img class="zapimage"     src="hook/waffi/images/ButtonDown.png"   id="Button_'.$directionDown.'_'.$channelDeviceObjectID.'"    alt="down"/></div>

					<div class="zapbuttonR buttonMouseOver" id="buttonRed"><img class="zapimage"      src="hook/waffi/images/ButtonRed.png"    id="Button_'.$colorButtonRed.'_'.$channelDeviceObjectID.'"       alt="red"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonYellow"><img class="zapimage"   src="hook/waffi/images/ButtonYellow.png" id="Button_'.$colorButtonYellow.'_'.$channelDeviceObjectID.'"    alt="yellow"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonGreen"><img class="zapimage"    src="hook/waffi/images/ButtonGreen.png"  id="Button_'.$colorButtonGreen.'_'.$channelDeviceObjectID.'"     alt="green"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonBlue"><img class="zapimage"     src="hook/waffi/images/ButtonBlue.png"   id="Button_'.$colorButtonBlue.'_'.$channelDeviceObjectID.'"      alt="blue"/></div>

					<div class="zapbuttonR buttonMouseOver" id="buttonOn"><img class="zapimage"       src="hook/waffi/images/ButtonOn.png"     id="ButtonOO_'.$harmonyHubStartActivityOn.'_'.$harmonyHubObjectID.'"  alt="On"/></div>
					<div class="zapbuttonR buttonMouseOver" id="buttonOff"><img class="zapimage"      src="hook/waffi/images/ButtonOff.png"    id="ButtonOO_'.$harmonyHubStartActivityOff.'_'.$harmonyHubObjectID.'" alt="Off"/></div>
				</section>
			</div>
		</div>
		';

		SetValue($this->GetIDForIdent("channelListHTML"), $channelListHTML);
		//echo $channelListHTML;
	}

}
?>
