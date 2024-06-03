<?php

class PHProofReading{
	public $languages_cache_file = '.languages';

	private function request($url, $post_data = []) {
		$context = null;

		if (count($post_data) > 0) {
			$options = [
				'http' => [
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($post_data)
				],
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false
				]
			];

			$context  = stream_context_create($options);
		}

		return file_get_contents($url, false, $context);
	}

	public function languages() {
		if (file_exists($this->languages_cache_file))
			$data = file_get_contents($this->languages_cache_file);
		else {
			$data = $this->request('https://languagetool.org/api/v2/languages');

			file_put_contents($this->languages_cache_file, $data);
		}

		$languages = json_decode($data, true);

		return $languages;
	}

	public function check($text, $language = 'en-US') {
		$data = $this->request('https://languagetool.org/api/v2/check', ['text' => $text, 'language' => $language]);
		$check = json_decode($data, true);

		return $check;
	}
}

?>