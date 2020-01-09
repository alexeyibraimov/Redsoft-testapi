<?php

namespace BlackWolf;

class Api
{
	var $db_Conn;
	var $debug;
	var $dbHost;
	var $dbLogin;
	var $dbPassword;
	var $dbName;
	var $_DIR;
	var $_doc_root;
	var $_path;
	var $_s404;

	function __construct($_debag){
		$this->debag		= $_debag;		
		$this->dbHost  		= "localhost";
		$this->dbLogin		= "admin_test";
		$this->dbPassword       = "w7Iue1vwpH";
		$this->dbName		= "admin_test";
		$this->_DIR		= __DIR__."/..";
		$this->_doc_root	= $_SERVER["DOCUMENT_ROOT"];
		$this->_path		= str_replace($this->_doc_root,"",$this->_DIR);
		$this->_s404		= false;		
		
		$this->Connect();
		if ($this->db_Conn)
		{
			$this->Worker();
			$this->Disconnect();
		}
	}

	private function Connect()
	{

		$this->db_Conn = mysqli_connect($this->dbHost, $this->dbLogin, $this->dbPassword, $this->dbName);

		if(!$this->db_Conn)
		{
			$error = "[".mysqli_connect_errno()."] ".mysqli_connect_error();
			if($this->debug)
				echo "<br><font color=#ff0000>Error! mysqli_connect()</font><br>".$error."<br>";
			return false;
		}
		$this->QueryInternal("SET NAMES 'utf8'");
		$this->QueryInternal('SET collation_connection = "utf8_unicode_ci"');

		return true;
	}

	private function Disconnect()
	{
		mysqli_close($this->db_Conn);
	}

	protected function QueryInternal($strSql)
	{
		return mysqli_query($this->db_Conn, $strSql, MYSQLI_STORE_RESULT);
	}

	protected function GetError()
	{
		return "[".mysqli_errno($this->db_Conn)."] ".mysqli_error($this->db_Conn);
	}


	function LastID()
	{
		return mysqli_insert_id($this->db_Conn);
	}

	function ForSql($strValue, $iMaxLength = 0)
	{
		if ($iMaxLength > 0)
			$strValue = substr($strValue, 0, $iMaxLength);

		return mysqli_real_escape_string($this->db_Conn, $strValue);
	}

	private function CreateTemplate($_body, $_params)
	{
		global $_mainMenu;
		ob_end_clean();
		require($this->_DIR."/template/header.php");
		echo $_body;
		require($this->_DIR."/template/footer.php");
	}

	function Worker()
	{
		$_act = (isset($_REQUEST["act"])) ? current(explode("/",trim($_REQUEST["act"]))) : "api";
		switch($_act){
			case "api":
				# Мануал и примеры запросов к API
				require_once($this->_DIR."/template/api.php");
				$this->CreateTemplate(ob_get_contents(), array());		
				break;
			case "bd":
				# Работа с БД
				$_result = false;	
				if(isset($_POST["create"])) $_result = $this->createTable();
				require_once($this->_DIR."/template/bd.php");
				$this->CreateTemplate(ob_get_contents(), array());		
				break;
			case "import":
				# Импорт данных
				$_result = false;	
				if(isset($_FILES['fl']['tmp_name'][0])) $_result = $this->importElemCSV($_FILES['fl']['tmp_name']);
				require_once($this->_DIR."/template/import.php");
				$this->CreateTemplate(ob_get_contents(), array());		
				break;
			case "export":
				# Экспорт данных
				if(isset($_POST["save"])) $_link = $this->exportElemCSV();
				require_once($this->_DIR."/template/export.php");
				$this->CreateTemplate(ob_get_contents(), array());		
				break;
			case "gi":
				# Отработка выборки - по id	
				switch(true){
					case (isset($_REQUEST["id"]) && intval($_REQUEST["id"])):
						$this->getElemByID(intval($_REQUEST["id"]));
						break;
					default:
						# отсутсвтвие параметров
						$this->showJsonErr(array("name"=>"error params","s"=>10));
				}
				break;
			case "gq":
				# Отработка выборки - поиск по подстроке в названии	
				switch(true){
					case (isset($_POST["q"]) && trim($_POST["q"])!=""):
						$this->getElemBySUBName(trim($_POST["q"]));
						break;
					default:
						# отсутсвтвие параметров
						$this->showJsonErr(array("name"=>"error params","s"=>10));
				}
				break;
			case "gb":
				# Отработка выборки - по производителю	
				switch(true){
					case (isset($_POST["q"]) && trim($_POST["q"])!=""):
						$this->getElemByManufactureList(trim($_POST["q"]));
						break;
					case (isset($_POST["id"])):
						$this->getElemByManufactureID($_POST["id"]);
						break;
					default:
						# отсутсвтвие параметров
						$this->showJsonErr(array("name"=>"error params","s"=>10));
				}
				break;
			case "gs":
				# Отработка выборки - по разделу	
				switch(true){
					case (isset($_POST["id"]) && intval($_POST["id"])):
						$this->getElemBySectionID(intval($_POST["id"]));
						break;
					default:
						# отсутсвтвие параметров
						$this->showJsonErr(array("name"=>"error params","s"=>10));
				}
				break;
			case "gst":
				# Отработка выборки - по разделу	
				switch(true){
					case (isset($_POST["id"]) && intval($_POST["id"])):
						$this->getElemBySectionIDTree(intval($_POST["id"]));
						break;
					default:
						# отсутсвтвие параметров
						$this->showJsonErr(array("name"=>"error params","s"=>10));
				}
				break;
			default:
				# Левые запросы к API
				$this->show404();
			
		}	
	}

	function show404()
	{
		# Левые запросы к API
		header('HTTP/1.1 404 Not Found');
	        $this->_s404 = true;
		$this->CreateTemplate(file_get_contents($this->_DIR."/template/404.php"), array());		
	}

	function showJson($arrData){
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(array("status"=>"ok","product"=>$arrData), JSON_UNESCAPED_UNICODE); 
	}

	function showJsonErr($arrData){
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(array("status"=>"error","error"=>$arrData), JSON_UNESCAPED_UNICODE); 
	}

	function getElemByID($_id)
	{
		$_res = $this->QueryInternal("SELECT * FROM `element` WHERE `ID` = ".$_id);
		if($item = $_res->fetch_assoc()){
			$this->showJson($item);
			return;
		}
		$this->showJsonErr(array("name"=>"element not found","s"=>100));
		return;
	}

	function getElemBySUBName($_q)
	{
		$_q = $this->ForSql(htmlspecialchars($_q));
		$_items = array();
		$_res = $this->QueryInternal("SELECT * FROM element WHERE NAME LIKE '%".$_q."%'");
		while($_item = $_res->fetch_assoc()){
			$_items[] = $_item;
		}
		if(sizeof($_items)){
			$this->showJson($_items);
		}else{
			$this->showJsonErr(array("name"=>"element not found","s"=>100));
		}
		return;
	}

	function getManufactureList()
	{
		$_items = array();
		$_res = $this->QueryInternal("SELECT * FROM manufacture ORDER BY ID ASC ");
		while($_item = $_res->fetch_assoc()){
			$_items[$_item["ID"]] = $_item["NAME"];
		}
		return $_items;
		
	}

	function getElemByManufactureList($_q)
	{
		$_q = $this->ForSql(htmlspecialchars($_q));
		$_items = array();
		$_res = $this->QueryInternal("SELECT E.* FROM manufacture as M LEFT JOIN element as E ON M.ID=E.MANUFACTURE WHERE M.NAME LIKE '%".$_q."%'");
		while($_item = $_res->fetch_assoc()){
			$_items[] = $_item;
		}
		if(sizeof($_items)){
			$this->showJson($_items);
		}else{
			$this->showJsonErr(array("name"=>"element not found","s"=>100));
		}
		return;
	}

	function getElemByManufactureID($_id)
	{
		$_items = array();
		if(is_array($_id)){
			foreach($_id as $i => $d) $_id[$i] = intval($d); 
			$_res = $this->QueryInternal("SELECT * FROM element WHERE MANUFACTURE IN (".implode(",",$_id).")");
		}elseif(intval($_id)){
			$_res = $this->QueryInternal("SELECT * FROM element WHERE MANUFACTURE = ".$_id);
		}
		if($_res)
		while($_item = $_res->fetch_assoc()){
			$_items[] = $_item;
		}
		if(sizeof($_items)){
			$this->showJson($_items);
		}else{
			$this->showJsonErr(array("name"=>"element not found","s"=>100));
		}
		return;
	}

	function setSectionTree($_arSection,$l)
	{
		$arSubSec = array();
		foreach($_arSection as $_id => $_arr){
			$arSubSec[$_arr["ID"]] = str_pad("", $l, "-").$_arr["NAME"];
			$arSubSec += $this->setSectionTree($_arr["SUBSECT"],$l+1);
		}	
		return $arSubSec;
	}
	
	function getSectionTree($_sect_id,$_arSection)
	{
		$arSubSec = array(); 
		if(array_key_exists($_sect_id,$_arSection)) unset($_arSection[$_sect_id]);
		foreach($_arSection as $_id => $_arr){
			if($_sect_id == intval($_arr["PARENT_SECTION"])){
				$_arr["SUBSECT"] = $this->getSectionTree($_arr["ID"],$_arSection);
				$arSubSec[] = $_arr;
			}
		}	
		return $arSubSec;
	}

	function getSectionList()
	{
		$_res = $this->QueryInternal("SELECT * FROM section ORDER BY PARENT_SECTION ASC, ID ASC");
		while($_item = $_res->fetch_assoc()){
			$_items[$_item["ID"]] = $_item;
		}
 		$_items = $this->setSectionTree($this->getSectionTree(0,$_items),0);
		return $_items;
		
	}

	function getElemBySectionID($_id)
	{
		$_items = array();
		if(intval($_id)){
			$_res = $this->QueryInternal("SELECT E.* FROM elem_sect as ES LEFT JOIN element as E ON E.ID = ES.ELEMENT WHERE ES.SECTION = ".$_id);
		}
		if($_res)
		while($_item = $_res->fetch_assoc()){
			$_items[] = $_item;
		}
		if(sizeof($_items)){
			$this->showJson($_items);
		}else{
			$this->showJsonErr(array("name"=>"element not found","s"=>100));
		}
		return;
	}

	function setSectionTreeID($_arSection,$l)
	{
		$arSubSec = array();
		foreach($_arSection as $_id => $_arr){
			$arSubSec[] = $_arr["ID"];
			$arSubSec = array_merge($arSubSec,$this->setSectionTreeID($_arr["SUBSECT"],$l+1));
		}	
		return $arSubSec;
	}

	function getSectionSubTreeByID($_sect_id)
	{
		$_res = $this->QueryInternal("SELECT * FROM section ORDER BY PARENT_SECTION ASC, ID ASC");
		while($_item = $_res->fetch_assoc()){
			$_items[$_item["ID"]] = $_item;
		}
		if(array_key_exists($_sect_id,$_items)){
	 		$_items = array_merge(array(intval($_sect_id)), $this->setSectionTreeID($this->getSectionTree(intval($_sect_id),$_items),0));
		}
		return $_items;
		
	}

	function getElemBySectionIDTree($_id)
	{

		$_items = array();
		if(intval($_id)){
			$_id = $this->getSectionSubTreeByID($_id);
			$_res = $this->QueryInternal("SELECT E.* FROM elem_sect as ES LEFT JOIN element as E ON E.ID = ES.ELEMENT WHERE ES.SECTION IN (".implode(",",$_id).")");
		}
		if($_res)
		while($_item = $_res->fetch_assoc()){
			$_items[] = $_item;
		}
		if(sizeof($_items)){
			$this->showJson($_items);
		}else{
			$this->showJsonErr(array("name"=>"element not found","s"=>100));
		}
		return;
	}

	function setSectionLineTree($_arSection,$_parent_name)
	{
		$arSubSec = array();
		foreach($_arSection as $_id => $_arr){
			$arSubSec[$_arr["ID"]] = (($_parent_name!="")?$_parent_name.":":"").$_arr["NAME"];
			$arSubSec += $this->setSectionLineTree($_arr["SUBSECT"],$_arr["NAME"]);
		}	
		return $arSubSec;
	}

	function getSectionLineList()
	{
		$_items = array();
		$_res = $this->QueryInternal("SELECT * FROM section ORDER BY PARENT_SECTION ASC, ID ASC");
		while($_item = $_res->fetch_assoc()){
			$_items[$_item["ID"]] = $_item;
		}
 		$_items = $this->setSectionLineTree($this->getSectionTree(0,$_items),"");
		return $_items;
		
	}

	function convertEncodingArrayUTF(&$_data)
	{
	        foreach($_data as $_i => $_val) $_data[$_i] = mb_convert_encoding($_val,"UTF-8","cp1251");
	}	

	function setSectionLineList($_sname, $_parent_id)
	{
		# проверка существования и создание иерархической сруктуры разделов
		if(sizeof($_sname) && trim($_sname[0])!=""){
			$_res = $this->QueryInternal("SELECT * FROM section WHERE NAME='".trim($_sname[0])."'");
			if($_item = $_res->fetch_assoc()){
				array_shift($_sname);
				return $this->setSectionLineList($_sname, $_item["ID"]);
			}else{
				$_res = $this->QueryInternal("INSERT INTO `section` (`ID`, `NAME`, `PARENT_SECTION`) VALUES (NULL, '".$this->ForSql(trim($_sname[0]))."',".$_parent_id.")");
				if($_res){
					$_id = $this->LastID();
					array_shift($_sname);
					return $this->setSectionLineList($_sname, $_id);
				}
			}
		}else{
			return $_parent_id;
		}
	}

	function importElemCSV($_filecsv)
	{
		$_arItems = $_arHead = array();	
		$_arManufacture = array(); 
		foreach($this->getManufactureList() as $_id => $_name) $_arManufacture[$_id] = mb_strtolower($_name);
		$_arSections = array();
		foreach($this->getSectionLineList() as $_id => $_name) $_arSections[$_id] = mb_strtolower($_name);

		$_f = fopen($_filecsv, "r");
		if($_f){
			$i = 0;
			while (($_data = fgetcsv($_f, 1000, ";")) !== FALSE) {
				$this->convertEncodingArrayUTF($_data);
				if(!$i){
					$_arHead = $_data;
					$i ++;
					continue;
				}
				$i ++;
				$_data = array_combine($_arHead,$_data);
				if($_data["NAME"]!=""){
					$_mname = mb_strtolower(trim($_data["MANUFACTURE"]));
					if($_mname!="")
					if(in_array($_mname,$_arManufacture)){
						$_data["MANUFACTURE"] = array_search($_mname,$_arManufacture);
					}else{
						$_res = $this->QueryInternal("INSERT INTO `manufacture` (`ID`, `NAME`) VALUES (NULL, '".$this->ForSql(trim($_data["MANUFACTURE"]))."')");
						if($_res){
							$_id = $this->LastID();
							$_data["MANUFACTURE"] = $_id;
							$_arManufacture[$_id] = $_mname;
						}
					}
					$_sname = mb_strtolower(trim($_data["SECTION"]));
					if($_sname!="")
					if(in_array($_sname,$_arSections)){
						$_data["SECTION"] = array_search($_sname,$_arSections);
					}else{
						$_data["SECTION"] = $this->setSectionLineList(explode(":",trim($_data["SECTION"])),0);
					}
					# добавляем/обновляем товары
					$_res = $this->QueryInternal("SELECT * FROM element WHERE NAME='".$this->ForSql(trim($_data["NAME"]))."'");
					if($_item = $_res->fetch_assoc()){
			       			$_data["ID"] = $_item["ID"];
						$_res = $this->QueryInternal("UPDATE element SET `NAME` = '".$this->ForSql(trim($_data["NAME"]))."'".((isset($_data["AVAILABLE"]))?", `AVAILABLE` = '".intval($_data["AVAILABLE"])."'":"").((isset($_data["PRICE"]) && isset($_data["PRICE"])!="")?", `PRICE` = '".intval($_data["PRICE"])."'":"").((isset($_data["MANUFACTURE"]) && $_data["MANUFACTURE"]!="")?", `MANUFACTURE` = '".intval($_data["MANUFACTURE"])."'":"")." WHERE ID = ".$_item["ID"]);
					}else{
						$_res = $this->QueryInternal("INSERT INTO `element` (`ID`, `NAME`, `AVAILABLE`, `PRICE`, `MANUFACTURE`) VALUES (NULL, '".$this->ForSql(trim($_data["NAME"]))."', '".intval($_data["AVAILABLE"])."', '".intval($_data["PRICE"])."', '".intval($_data["MANUFACTURE"])."')");
						$_data["ID"] = $this->LastID();
					}
					# добавляем, если нет, привязку к разделам
					$_res = $this->QueryInternal("SELECT * FROM elem_sect WHERE ELEMENT=".$_data["ID"]." AND SECTION=".$_data["SECTION"]);
					if(!($_item = $_res->fetch_assoc())){
						$_res = $this->QueryInternal("INSERT INTO `elem_sect` (`ELEMENT`, `SECTION`) VALUES (".intval($_data["ID"]).", ".intval($_data["SECTION"]).")");
					}
					$_arItems[] = $_data;
				}
			}
		}
		return true;
	}

	function exportElemCSV()
	{
		$_link = $this->_path."/export.csv";
		$_sections = $this->getSectionLineList();
#		print_r($_sections);
		echo $this->_doc_root.$_link;
		$_f = fopen($this->_doc_root.$_link,"w+");
		fwrite($_f,"NAME;AVAILABLE;PRICE;MANUFACTURE;SECTION\n");
		$_res = $this->QueryInternal("SELECT E.*,ES.SECTION as SECTION, M.NAME as MANUFACTURE FROM elem_sect as ES LEFT JOIN element as E ON E.ID = ES.ELEMENT LEFT JOIN manufacture as M ON E.MANUFACTURE=M.ID");
		if($_res)
		while($_item = $_res->fetch_assoc()){
			fwrite($_f,mb_convert_encoding($_item["NAME"].";".$_item["AVAILABLE"].";".$_item["PRICE"].";".$_item["MANUFACTURE"].";".((array_key_exists($_item["SECTION"],$_sections))?$_sections[$_item["SECTION"]]:""),"cp1251")."\n");
		}
		fclose($_f);
		if(file_exists($this->_doc_root.$_link)){		
			header("Location: ".$_link);
		}
		return false;
	}
	function createTable()
	{
		$strSQL = file_get_contents($this->_DIR."/lib/mysql_db.sql");
		if(strlen($strSQL)>0){
			foreach(explode(";",$strSQL) as $_sql)
				$_res = $this->QueryInternal($_sql);
		}
		return true;
	}
}