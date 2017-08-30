<?php

namespace menubuilder;
	
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
	
	public function getMenuArray()
	{
		if ($this->graph_list === null) $this->buildGraphAsList();
		return $this->buildMenu($this->top_points);
	}
	
	public function getTopPoints()
	{
		return $this->top_points;
	}
	
	private function buildMenu($points)
	{
		$menu = [];
		foreach ($points as $point) {
			if (count($this->graph_list[$point]) > 1) {
				$menu[] = [
					'title' => $this->getPageById($point, $this->pages)['title'],
					'items' => $this->buildMenu($this->graph_list[$point])
				];
			} else {
				if ($point !== null)
					$menu[] = ['title' => $this->getPageById($point, $this->pages)['title']];
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
		foreach ($this->pages as $page)
			if ($page['parent'] === 0) $this->top_points[] = $page['id'];
	}
}
	
?>