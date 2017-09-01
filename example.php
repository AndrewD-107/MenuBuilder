<?php
	
require_once 'MenuBuilder.php';
	
$pages = json_decode(file_get_contents('data.json'), true);
$menu_builder = new \MenuBuilder\menubuilder\MenuBuilder($pages);
$menu = $menu_builder->getMenuArray();

print_r($menu_builder->getOpenMenuPoints(14));

echo display($menu);

function display($array)
{
	$html_code = null;
		foreach ($array as $key => $item) {
			if (!array_key_exists('items', $item))
				$html_code .= '<li>'.$item['title'].'</li>';
			else {
				$html_code .= '<li>'.$item['title'].'</a>';
				$html_code .= '<ul>'.display($item['items']).'</ul></li>';
			}
		}
		return $html_code;
}
	
?>