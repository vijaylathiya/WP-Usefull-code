<?php
include('wp-load.php');

function delete_empty_terms(){
	$taxonomy_name = 'category';
	$terms = get_terms( array(
		'taxonomy' => $taxonomy_name,
		'hide_empty' => false
	) );
	$i=0;
	foreach ( $terms as $term ) 
	{
		$term_count = $term->count;
		if ($term_count <= 3){ 
			wp_delete_term($term->term_id, $taxonomy_name); 
			echo $term->term_id;
			echo "<br/>";
			$i++;
		}	
		if($i==100)
			die("over");
	} 
}
delete_empty_terms();

function delete_empty_tags(){
	$tags = get_tags( array('number' => 0,'hide_empty' => false));
	$i=0;
	foreach ( $tags as $tag ) {		
		$tag_count = $tag->count;		
		if ($tag_count <= 3 )
		{ 
			wp_delete_term( $tag->term_id, 'post_tag' );
			echo $tag->term_id;
			echo "<br/>";
			$i++;
				  
		} 
		if($i==120)
			die("over");
	}
}
delete_empty_tags();

?>