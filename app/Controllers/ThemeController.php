<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class ThemeController extends Controller
{
	/**
	 * @param Request $request
	 * @param Response $response
	 * @return Response
	 */
	public function getThemes(Request $request, Response $response): Response
	{
		$apiJson = json_decode(file_get_contents('https://bootswatch.com/api/4.json'));

		$out = [];

		$out['Default - Bootstrap 4 default theme'] = 'https://bootswatch.com/_vendor/bootstrap/dist/css/bootstrap.min.css';
		foreach ($apiJson->themes as $theme) {
			$out["{$theme->name} - {$theme->description}"] = $theme->cssMin;
		}

		return $response->withJson($out);
	}


	public function applyTheme(Request $request, Response $response): Response
	{
		if (!is_writable(BASE_DIR . 'static/bootstrap/css/bootstrap.min.css')) {
			$this->session->alert(lang('cannot_write_file'), 'danger');
			return redirect($response, 'system');
		}

		file_put_contents(BASE_DIR . 'static/bootstrap/css/bootstrap.min.css', file_get_contents($request->getParam('css')));
		return redirect($response, 'system');
	}

}