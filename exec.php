<?
include("class.php");

$code = "ymbdwke1muvhpeobql2n5jh30iasbnvn";
$method = "crm.deal.list";
$params = array(
	"order" => array("STAGE_ID" => "ASC"),
	//"filter" => array(">PROBABILITY" => 50),
	"select" => array("ID", "TITLE", "STAGE_ID", "PROBABILITY", "OPPORTUNITY", "CURRENCY_ID")
);

$oauth = new COauth;

$access = $oauth->getAccessToken($code);
echo "<pre>";
print_r($access);
echo "</pre>";
$lead = $oauth->query($method, $params);
echo "<pre>";
print_r($lead);
echo "</pre>";

sleep(2);
$refresh = $oauth->getRefreshToken($oauth->refresh_token);
echo "<pre>";
print_r($refresh);
echo "</pre>";
$leadRefresh = $oauth->query($method, $params);
echo "<pre>";
print_r($leadRefresh);
echo "</pre>";
?>