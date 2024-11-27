<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */


get_header(); 

$columns = 2;

?>
<div class="section-main category-layout category-layout-news clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="site-content">
                    <?php /*?><h3><?php echo get_cat_name( $cat_ID ); ?></h3><?php */?>
                    <?php
                    if ( have_posts() ) : 
                        // neu co hon 1 cot thi dung cai nay
                        echo '<div class="list-posts container-fluid">';
                        if( $columns>1 ):
                            global $posts;
                            $count  = count($posts);
                            $i      = 0;
                            $row    = 1;
                            
                            echo '<div class="list-posts-row list-posts-row-0 row clearfix">';
                            // $i%$columns+1
                            // Start the Loop.
                            while ( have_posts() ) : the_post();
                                $j = $i+1;
                                
                                echo '<div class="col-md-4 list-posts-item list-posts-item-'.($j).' clearfix">';
                                    //get_template_part( 'content', $tp_name );
                                    get_template_part( 'parts/post/content', 'news' );
                                echo '</div>';
                                
                                //if( $i%$columns==0 && $j<$count )
                                    //echo '</div><div class="list-posts-row list-posts-row-'.($row++).' row clearfix">';
                                
                                $i++;
                            endwhile;
                            
                            echo '</div>';
                        
                        else:
                            // Start the Loop.
                            while ( have_posts() ) : the_post();
                                //get_template_part( 'content', $tp_name );
                                get_template_part( 'parts/post/content', 'news' );
                            endwhile;
                            
                        endif;
                        
                        echo '</div>';
                        
                        //
                        //twentytwentytwo_paging_nav();
                        
                    else :
                        // If no content, include the "No posts found" template.
                        get_template_part( 'content', 'none' );

                    endif;
                    ?>
                </div>          
            </div>      
        </div>  
    </div>  
</div>
<?php

get_footer();