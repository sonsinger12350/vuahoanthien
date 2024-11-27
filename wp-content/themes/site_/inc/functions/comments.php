<?php

function site_comment_product( $comment_id, $comment_approved, $commentdata )
{
    if( $comment_id == 0 ) return ;

    $fields = array(
        'quality',
        'value',
        'suggest',
    );

    foreach( $fields as $key ) {
        $value = sanitize_text_field( isset($_POST[$key]) ? $_POST[$key] : '' );
        if( $value!='' ) {
            add_comment_meta( $comment_id, $key, $value );
        }
    }

    // images;
    site_comment_images_product( $comment_id );
}
add_action( 'comment_post', 'site_comment_product', 5, 3 );

function site_comment_images_product( $comment_id = 0 )
{
    if( isset($_FILES['reviewfile']) ) {
        $extension = array('image/png', 'image/gif', 'image/jpeg');

        $files = $_FILES['reviewfile'];

        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $images = [];

        foreach( $files["tmp_name"] as $key => $tmp_name ) 
        {
            $type = $files["type"][$key];            
            if( in_array($type,$extension) )
            {
                $filename = $wp_upload_dir['path'] . '/' . basename( $files["name"][$key] );

                copy( $tmp_name, $filename);
                
                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                    'post_mime_type' => $type,
                    'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                
                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id = 0 );

                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );

                $images[] = $attach_id;

                if( count($images)>5 ) {
                    break;
                }
            }
        }

        if( count($images)>0 ) {
            add_comment_meta( $comment_id, 'images', implode(',', $images) );
        }
    }
}

function site_delete_comment( $comment_id, $comment )
{
    if( $comment_id == 0 ) return ;

    $images = explode( ',', get_comment_meta( $comment_id, 'images' ) );

    foreach( $images as $id ) {
        $id = (int) $id;
        if( $id>0 ) {
            wp_delete_attachment( $id );
        }
    }

    // file_put_contents( ABSPATH .'/a-delete-comment-'.$comment_id.'.txt', json_encode($comment) );
}
add_action( 'delete_comment', 'site_delete_comment', 10, 2 );

function site_comment_submiting()
{
    if ( isset( $_POST['comtoken'] ) && wp_verify_nonce( $_POST['comtoken'], 'commentoken' ) ) 
    {
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {

            $message = '';

            $data = (int) $comment->get_error_data();
            if ( ! empty( $data ) ) {
                // wp_die(
                //     '<p>' . $comment->get_error_message() . '</p>',
                //     __( 'Comment Submission Failure' ),
                //     array(
                //         'response'  => $data,
                //         'back_link' => true,
                //     )
                // );

                $message = $comment->get_error_message();
            }
            
            $json = array( 'code' => 403, 'message' => $message . __( 'Comment Submission Failure' ) );
        } else {
            $json = array( 'code' => 200, 'message' => 'Đánh giá thành công!' );
        }

        $ajax_i = intval( isset( $_POST['ajax_i'] ) ? $_POST['ajax_i'] : 0 );
        
        if( $ajax_i>0 ) {
            die( json_encode( $json ) );
            exit;
        }
        
        $comment_post_ID = intval( isset( $_POST['comment_post_ID'] ) ? $_POST['comment_post_ID'] : 0 );

        $location = apply_filters( 'comment_post_redirect', get_permalink($comment_post_ID), $comment );

        wp_safe_redirect( $location );
        exit;
    }
    else if ( isset( $_POST['detoken'] ) && wp_verify_nonce( $_POST['detoken'], 'dealtoken' ) ) 
    {
        $code = 500;
        $comment_id = 0;

        $post_id = intval( isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0 );
        $price = sanitize_text_field( isset( $_POST['price'] ) ? $_POST['price'] : '' );
        $phone = sanitize_text_field( isset( $_POST['phone'] ) ? $_POST['phone'] : '' );

        if( $post_id>0 && comments_open( $post_id ) && $price!='' && $phone!='' ) {

            $data = array(
                'comment_post_ID'       => $post_id,
                'comment_content'       => "Giá mong muốn là {$price}!",
                'comment_author'        => $phone,
                'comment_parent'        => 0,
                'comment_type'          => 'deal-price',
                'comment_author_IP'     => $_SERVER['REMOTE_ADDR'],
            );

            $comment_id = wp_insert_comment( $data );
            if ( ! is_wp_error( $comment_id ) ) {
                $code = 200;
            }            
        }

        die( json_encode( array( 'code' => $code, 'id' => $comment_id ) ) );
        exit();
    }
}
add_action( 'wp', 'site_comment_submiting' );

function site_deal_admin_comment_types_dropdown( $comment_types = array() )
{
    $comment_types['deal-price'] = __( 'Deal Price' );

    return $comment_types;
}
add_filter( 'admin_comment_types_dropdown', 'site_deal_admin_comment_types_dropdown', 20, 1 );