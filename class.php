<?
/**
 * Класс для описания обработки интеграции с Битрикс24 через webhook	
*/

class COauth
{
	private $bitrix24 = "https://b24-n593547d544ed3.bitrix24.ru";
	private $client_id = "app.**************.********";
	private $client_secret = "********************************";

	private $scope = "crm,user";

	protected $access_token;
	protected $refresh_token;
	protected $result;
	protected $resultRefresh;
	//private $method = "crm.deal.list";

	// get access and refresh
	//"https://мой_портал.bitrix24.ru/oauth/token/?client_id=код_приложения&grant_type=authorization_code&client_secret=секретный_ключ_приложения&redirect_uri=http%3A%2F%2Flocalhost%3A70005&code=код_получения_авторизации&scope=требуемый_набор_разрешений";

	// refresh query
	//"https://my.bitrix24.ru/oauth/token/?client_id=xxxxx&grant_type=refresh_token&client_secret=xxxxx&redirect_uri=http%3A%2F%2Ftest.com%2Fbitrix%2Foauth%2Foauth_test.php&refresh_token=zzzzzzzzzzz"

	public function __get($params)
	{
		switch ($params)
        {
            case 'result':
                return $this->result;
            case 'access_token':
                return $this->access_token;
            case 'refresh_token':
                return $this->refresh_token;
        }
	}

	public function getAccessToken($code)
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->bitrix24 . "/oauth/token/?client_id=" . $this->client_id . "&grant_type=authorization_code&client_secret=" . $this->client_secret . "&redirect_uri=http%3A%2F%2Flocalhost%3A70005&code=" . $code . "&scope=" . $this->scope
		));

		$result = curl_exec($curl);
		curl_close($curl);

		$result = json_decode($result, true);

		$this->access_token = $result["access_token"];
		$this->refresh_token = $result["refresh_token"];
		$this->result = $result;

		return $this->result;
	}

	public function getRefreshToken($refresh_token)
	{
		$curlRefresh = curl_init();
		curl_setopt_array($curlRefresh, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->bitrix24 . "/oauth/token/?client_id=" . $this->client_id . "&grant_type=refresh_token&client_secret=" . $this->client_secret . "&redirect_uri=http%3A%2F%2Ftest.com%2Fbitrix%2Foauth%2Foauth_test.php&refresh_token=" . $refresh_token
		));

		$resultRefresh = curl_exec($curlRefresh);
		curl_close($curlRefresh);

		$resultRefresh = json_decode($resultRefresh, true);

		$this->access_token = $resultRefresh["access_token"];
		$this->refresh_token = $resultRefresh["refresh_token"];
		$this->resultRefresh = $resultRefresh;

		return $this->resultRefresh;
	}

	public function query($method, $params)
	{
		$params["auth"] = $this->access_token;
		$curlQuery = curl_init();
		$queryData = http_build_query($params);
		curl_setopt_array($curlQuery, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => "https://b24-n593547d544ed3.bitrix24.ru/rest/" . $method . ".json", // crm.deal.list
			CURLOPT_POSTFIELDS => $queryData,
		));

		$resultQuery = curl_exec($curlQuery);
		curl_close($curlQuery);

		$resultQuery = json_decode($resultQuery, true);
		$this->resultQuery = $resultQuery;

		return $this->resultQuery;
	}
}
?>
