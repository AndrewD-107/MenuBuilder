<?php

namespace MenuBuilder\menubuilder;
	
class MenuBuilder
{
	private $top_points;
	private $pages;
	private $graph_list;
	
	public function __construct($pages)
	{
		$this->pages = $pages;
		$this->determinateTopPoints();
	}
	
	public function buildGraphAsList()
	{
		foreach ($this->pages as $point) {
			$this->graph_list[$point['id']][0] = null;
			if ($point['parent'] !== 0)
				$this->graph_list[$point['parent']][] = $point['id'];
		}
	}
	
	public function getGraphAsList()
	{
		return $this->graph_list;
	}
	
	public function getMenuArray($title='title', $id=false)
	{
		if ($this->graph_list === null) $this->buildGraphAsList();
		$opened_items = $id ? $this->getOpenMenuPoints($id) : false;
		return $this->buildMenu($this->top_points, $title, $opened_items);
	}
	
	public function getTopPoints()
	{
		return $this->top_points;
	}
	
	public function getOpenMenuPoints($id, &$points = array())
	{
		foreach ($this->graph_list as $parent => $point) {
			foreach ($point as $p) {
				if ($p == $id) {
					if ($parent !== 0) {
						$points[] = (int)$parent;
						$this->getOpenMenuPoints($parent, $points);
					} else break;
				}
			}
		}
		$result = $points;
		$result[] = (int)$id;
		return $result;
	}
	
	private function buildMenu($points, $title, $opened_items = false)
	{
		//print_r($opened_items);
		$menu = [];
		$p = [];
		foreach ($points as $point) {
			$id = $this->getPageById($point, $this->pages)['id'];
			if (count($this->graph_list[$point]) > 1) {
				$menu[] = [
					'id' => $id,
					'title' => $this->getPageById($point, $this->pages)[$title],
					'opened' => $opened_items && array_search($id, $opened_items) !== false ? true : false,
					'items' => $this->buildMenu($this->graph_list[$point], $title, $opened_items)
				];
			} else {
				if ($point !== null)
					$menu[] = [
						'id' => $id,
						'opened' => $opened_items && array_search($id, $opened_items) !== false ? true : false,
						'title' => $this->getPageById($point, $this->pages)[$title]
					];
			}
		}
		return $menu;
	}
	
	private function getPageById($id)
	{
		foreach ($this->pages as $page)
			if ($page['id'] === $id) return $page;
	}
	
	private function determinateTopPoints()
	{
		foreach ($this->pages as $page) {
			if ($page['parent'] == 0) $this->top_points[] = $page['id'];
		}
	}
}
	
?>