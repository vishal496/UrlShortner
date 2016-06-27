<?php

namespace Url;


Class Common
{
	
	function __construct()
	{
		
	}

   /**
    * Generates the hash code for the string
    *
    * @param string $string
    *
    * @return string
	*/
	public function generateHash($string)
	{
		$hash = md5($string);
		return $hash;
	}

   /**
    * Sets the id and name in session
    *
    * @return void
    */	
	public function setSession($id, $name)
	{
		session(
			[
				'id' => $id,
				'name' => $name
			]
		);
	}

   /**
	* Paginates the view.
	*
	* @param integer $total
	* @param integer $limit
	* @param integer $page
	*
	* @return string
    */	
	public function paginate($total, $limit, $page)
	{
		$adjacents = 3;
		/* Setup page vars for display. */
		if ($page == 0) $page = 1; //if no page var is given, default to 1.
		$prev = $page - 1; //previous page is current page - 1
		$next = $page + 1; //next page is current page + 1
		$lastpage = ceil($total/$limit); //lastpage.
		$lpm1 = $lastpage - 1; //last page minus 1

		/* CREATE THE PAGINATION */
		$pagination = "";
		if($lastpage > 1)
		{ 
			$pagination .= "<div class='pagination1'> <ul>";
			$counter = 0;
			if ($page > $counter+1) {
				$pagination.= "<li><a href=\"/page?page=$prev\">prev</a></li>"; 
			}

			if ($lastpage < 7 + ($adjacents * 2)) 
			{ 
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
					else
					$pagination.= "<li><a href=\"/page?page=$counter\">$counter</a></li>"; 
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) //enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2)) 
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
						else
						$pagination.= "<li><a href=\"/page?page=$counter\">$counter</a></li>"; 
					}
					$pagination.= "<li>...</li>";
					$pagination.= "<li><a href=\"/page?page=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"/page?page=$lastpage\">$lastpage</a></li>"; 
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href=\"/page?page=1\">1</a></li>";
					$pagination.= "<li><a href=\"/page?page=2\">2</a></li>";
					$pagination.= "<li>...</li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
						else
						$pagination.= "<li><a href=\"/page?page=$counter\">$counter</a></li>"; 
					}
					$pagination.= "<li>...</li>";
					$pagination.= "<li><a href=\"/page?page=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"/page?page=$lastpage\">$lastpage</a></li>"; 
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= "<li><a href=\"/page?page=1\">1</a></li>";
					$pagination.= "<li><a href=\"/page?page=2\">2</a></li>";
					$pagination.= "<li>...</li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
						$pagination.= "<li><a href='#' class='active'>$counter</a></li>";
						else
						$pagination.= "<li><a href=\"/page?page=$counter\">$counter</a></li>"; 
					}
				}
			}

			//next button
			if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"/page?page=$next\">next</a></li>";
			else
			$pagination.= "";
			$pagination.= "</ul></div>\n"; 
		}
		return $pagination;
	}
}