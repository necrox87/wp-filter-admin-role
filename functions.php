<?php

/*
 * Add this snippet to wp-content/themes/your-themes/functions.php
 * 
 * $filter_admin_role = array(
 *     'role-name'
 * );
 * 
 * Add into $filter_admin_role array the role name to filter
 * 
 * This snippet filter posts, pages and attchments list for showing only entry created by current user
 * 
 */

$filter_admin_role = array(
    'role-name'
);

add_filter( 'posts_where', 'filter_admin_role_lists' );

function filter_admin_role_base_user_comune( $where )
{
    global $current_user, $filter_admin_role;

    if( is_user_logged_in() && 
            is_admin() && 
            isset($current_user->roles) && 
            in_array($current_user->roles[count($current_user->roles) - 1], $filter_admin_role) ) {
        
        $cs = get_current_screen();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            if( isset( $_POST['action'] ) ) {
                if( $_POST['action'] == 'query-attachments' ) {
                    $where .= ' AND post_author = '.$current_user->data->ID;
                }
            }
        } else if ( !is_null($cs) && isset($cs->id) ) {
            switch($cs->id) {
                case 'edit-post':
                case 'edit-page':
                case 'upload':
                    $where .= ' AND post_author = '.$current_user->data->ID;
                    break;
            }
        } 
    }

    return $where;
}
