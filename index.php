<?php
require_once 'Parsedown.php';
require_once 'class.pagination.php';

function total_posts() {
    return count( glob( "posts/*.md" ) );
}

function redirect_to( $site ) {
    header("Location: {$site}");
    exit;
}

$POSTS_PER_PAGE = 10;
$post_array     = [];
$pagination     = new Pagination();

if ( isset( $_GET['post_id'] ) && !empty( $_GET['post_id'] ) )  { 
    // Request for a single page e.g. about or a single post

    $POSTS_PER_PAGE = 1;
    $requested_page = filter_var( $_GET['post_id'], FILTER_SANITIZE_STRING, 
                                    array( 'flags' => FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW )
                                );
    $requested_page .= ".md";
    if ( file_exists( $requested_page ) ) {
        $post_array[] = $requested_page;
    } else { // if page/post was not found, redirect back to index.php
        unset( $_GET['post_id'] );
        redirect_to("/");
    }
    
} else if ( isset( $_GET['page'] ) && !empty( $_GET['page'] ) ) { 
    // Note: index.php?page=0 case is routed to the else clause ( ^^ ! empty() )
    $current_page = filter_var( $_GET['page'], FILTER_VALIDATE_INT,
                                    array( "options" => array( "min_range" => 1) ) 
                              );
    if ( !$current_page || $current_page - 1 > total_posts() / $POSTS_PER_PAGE ) {
        unset( $_GET['page'] );
        redirect_to("/");
    }
    
    // start = page * OFFSET + 1
    $req_post_start = ( ( $current_page - 1 ) * $POSTS_PER_PAGE ) + 1;
    $req_post_end   = $req_post_start + ( $POSTS_PER_PAGE - 1 );

    for ( $i = $req_post_start; $i <= $req_post_end; $i++ ) {
        $post_array[] = "posts/{$i}.md";
    }

    $pagination->set_vars( $current_page, $POSTS_PER_PAGE, total_posts() );

} else { 

    foreach( glob( "posts/*.md" ) as $filename ) {
        $post_array[] = $filename;
    }  
    // Take care of sorting [ 1.md, 11.md, 112.md, 2.md ] ... correctly
    // --> [ 1.md, 2.md, 11.md, 112.md ]
    //usort( $post_array, "anonymous function: blog_natural_sort" );
    usort( $post_array, function ($str1, $str2) { return strnatcmp( $str1, $str2 ); } );
    
    $current_page = ceil( total_posts() / $POSTS_PER_PAGE );
    $pagination->set_vars( $current_page, $POSTS_PER_PAGE, total_posts() );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <title>ADD TITLE</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/style.css">
  
  <link rel="icon" type="image/png" href="/images/favicon.png">
  
</head>
<body>

    <a href="/">
        <header class="hero">
            <h1>Welcome</h1>
        </header>
    </a>
  
  <div class="container">             
    <div class="row">
      <div class="col-sm-9 main-posts">
        
        <?php
         
            $index = count( $post_array );
            
            for ( $i = 0; $i < $POSTS_PER_PAGE; $i++ ) {
                
                if ( isset( $post_array[ $index - 1 ] ) ) {
                    
                    $filename = $post_array[ $index - 1 ];
                    
                    if ( file_exists( $filename ) ) {
                        
                        echo "<article>";
                        echo Parsedown::instance()->text( file_get_contents( $filename ) );
                        
                        //Extract the post number from string : "posts/40.md" --> "40"
                        $post_num = preg_replace( "/[^0-9]+/", "", $filename );
                        if ( $POSTS_PER_PAGE > 1 ) { 
                            echo '<div class="article-num"><a href="/posts/' . $post_num . '">Post: ' . $post_num . '</a></div>'; 
                        }
                        
                        echo "</article>";
                        echo $POSTS_PER_PAGE > 1 ? "<hr>" : "";
                    }
                    
                    $index--;
                }
            }
        ?>

        <?php if ( $pagination->has_pages_to_paginate() ) : ?>
            <div class="row">
                <div class="col-sm-12">
                   <h4 class="centered-text">All posts</h4>
                    <?php echo $pagination->paginate_pages(); ?>
                </div>
            </div>
          <?php endif; ?>
        
      </div>
    
      <div class="col-sm-3 sidebar">
        <h4>Contact</h4>
          <p><strong>Email:</strong><br>name@email.com
          <br><br>
          <strong>Phone:</strong><br>123 456 789
          </p>
          <hr>
        <h4>Links</h4>
        <ul class="contact-list">
            <li><a href="/" alt="" target="_blank">Link 1</a></li>
            <li><a href="/" alt="" target="_blank">Link 2</a></li>
        </ul>
        <hr>
        <h4>Site Navigation</h4>
        <ul class="contact-list">
            <li><a href="/pages/about-us" alt="about us">About Us</a></li>
            <li><a href="/" alt="home">Home</a></li>
        </ul>
      </div>
    </div>
  </div>
  
  <footer>
    <div class="row">
        <div class="col-sm-12">
           <p class="slogan">SLOGAN</p>
           <ul class="footer-list">
               <li>name@email.com</li>
               <li>123 456 789</li>
           </ul>
           <p class="copywrite">&copy; <?php echo date("Y"); ?></p>
        </div>  
    </div>
  </footer>

</body>
</html>
