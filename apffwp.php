<?php
/**
 **Plugin Name: WP Posts Filter Fields
 **Plugin URI: https://www.david.ug/
 **Description: Helps developers get started customizing WordPress posts filter fields.
 **Version: 0.0.1
 **Author: David of UG
 **Author URI: https://www.david.ug/
**/

Namespace APFFWP; /* The namespace will help me avoid name collisions with other plugins. I prefer this to prefixing filenames and variable identifies.*/

class FilterFields {
   
    public function __construct( ) {
        
        add_action( 'restrict_manage_posts', [ $this, 'theFields' ], 10 ); //Attach your fields
        add_filter( 'parse_query', [ $this, 'queryManager' ] ); //Attach your query to the parser
        
    }

    public function theFields( $post_type ) { //Accepts post type 
      //Show/Apply the field to only posts, post_type
      //Change for your need
        if( 'post' !== $post_type ) return;
        
        //Below I create an html text field with options hard coded but with a few tweaks you can use dynamic options.
       ?>
        
        <select id="victim-gender" name="gender">
            <!-- Hard coded options, but can be dynamic from the database or REST API -->
            <option value=""> <?php echo __( 'All Genders', 'apffwp' ); ?> </option>
            <option value="female" <?php if ( isset( $_GET[ 'gender' ] ) AND $_GET[ 'gender' ] == 'female') echo 'selected="selected"'; ?> >Female</option>
            <option value="male" <?php if( isset( $_GET[ 'gender' ] ) AND $_GET[ 'gender' ] == 'male' )  echo 'selected="selected"'; ?> >Male</option>

        </select>
        
    <?php
        
    }
    
    public function queryManager( $query ) { //queryManager accepts $query param to be able to modify the WP query in the behind the scenes
         
         if( is_admin( ) AND $query->is_main_query( ) ) : //Make sure the query works only in the admin dashboard
         
            if( 'post' === $query->query[ 'post_type' ] ) : //Make sure the query happens only for post post_type
            
                if ( isset( $_GET[ 'gender' ] ) AND $_GET[ 'gender' ] != '' ) : //Check if field has a value
                
                    $query->query_vars[ 'meta_key' ] = 'gender'; //Using a Custom Field, of key gender.  
                    $query->query_vars[ 'meta_value' ] = $_GET[ 'gender' ];
                    
                endif;
                
            endif;
            
        endif;
    }

}

new APFFWP\Inc\FilterFields( ); //Instantiating anonymous object from the FilterFields class to execute the fields. Use anonymous objects only if you know what you're doing. I used it here because I'm not going to refer to this object in the future.
