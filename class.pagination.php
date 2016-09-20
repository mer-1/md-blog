<?php
/**
 * Class for paginating blog posts
 * 
 * @see paginate_links(): https://core.trac.wordpress.org/browser/trunk/src/wp-includes/general-template.php
 * 
 */
class Pagination {
    
    private $current_page   = 0;
    private $posts_per_page = 1;
    private $total_num      = 0;
    
    public function set_vars( $current_page = 0, $posts_per_page = 10, $total_num = 0 ) {
        $this->current_page     = (int) $current_page;
        $this->posts_per_page   = (int) $posts_per_page;
        $this->total_num        = (int) $total_num;
    }
    
    private function total_pages() {
        return ceil( $this->total_num / $this->posts_per_page );
    }
    
    public function has_pages_to_paginate() {
        return $this->total_pages() > 1; 
    }
    
    public function paginate_pages() {
        $end_size = 1;
        $mid_size = 1;
        $total = $this->total_pages();
        $current = $this->current_page;
        $dots = false;
        $page_links = [];
        
        if ( 1 < $current ) {
            $page = $current - 1;
            $page_links[] = "<a href='/page/" . $page  . "'>&laquo; Older</a>";
        }
       
        for ( $i = 1; $i <= $total; $i++ ) :
            if ( $i == $current ) :
                $page_links[] = "<span class='current'>" . $i . "</span>";
                $dots = true;
            else :
                if (  $i <= $end_size || $i >= $current - $mid_size && $i <= $current + $mid_size || $i > $total - $end_size  ) :
                    $page_links[] = "<a href='/page/" . $i . "'>" . $i . "</a>";
                    $dots = true;
                elseif ( $dots ) :
                    $page_links[] = "<span class='dots'>&hellip;</span>";
                    $dots = false;
                endif;
            endif;
        endfor;
        
        if ( $current < $total ) {
            $page = $current + 1;
            $page_links[] = '<a href="/page/' . $page . '">Newer &raquo;</a>';
        }
       
        $r  = "<ul class='pagination'>\n\t\t\t\t\t\t<li>";
        $r .= join("</li>\n\t\t\t\t\t\t<li>", $page_links);
        $r .= "</li>\n\t\t\t\t\t</ul>\n";
        
        return $r;
    }
}

