

<?php
   /*
   Plugin Name: Get Data Plugin
   Description: Stores and Fetches Data From the Database
   Version: 1
   Author: Pragyanand Tiwari
   Author URI: https://github.com/Pragyanand
   */

//$path = preg_replace('/wp-content.*$/','',__DIR__);
//require_once($path."wp-load.php");

//require_once(ABSPATH . 'wp-config.php'); 
//require_once(ABSPATH . 'wp-admin/includes/taxonomy.php'); 



function redirect($link)
{
   echo 

    "<script type='text/javascript'>

            
            window.location.href='$link';

    </script>";   
}



function block_resend()
{
echo
"<script type = 'text/javascript'>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>";
}



function create_table()
{      
  global $wpdb; 
  $db_table_name = $wpdb->prefix . 'form_submission';  // table name
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $db_table_name (
                id int(11) NOT NULL auto_increment,
                ip varchar(15) NOT NULL,
                name varchar(60) NOT NULL,
                emailid varchar(200) NOT NULL,
                mobileno varchar(10) NOT NULL,
                message varchar(1000) NOT NULL,
                UNIQUE KEY id (id)
        ) $charset_collate;";



$createTable =     "CREATE TABLE IF NOT EXISTS $db_table_name ( 
                            id int AUTO_INCREMENT PRIMARY KEY, 
                            contact_name varchar(50),
                            contact_email varchar(50),
                            contact_phone int (50),
                            contact_comments varchar (1000))";
         


   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $createTable );
   //add_option( 'test_db_version', $test_db_version );
} 

register_activation_hook( __FILE__, 'create_table' );









function my_form()

   {

      $action_path = plugin_dir_url(__FILE__)."process/";
     
      $content = '';

      $content .= '<form method="post" action="">';

         $content .= '<input type="text" name="full_name" placeholder="Your Full Name" />';
         $content .= '<br />';

         $content .= '<input type="email" name="email_address" placeholder="Email Address"/>';
         $content .= '<br />';

         $content .= '<input type="number" name="phone_number" placeholder="Phone Number" maxlength="10"  />';
         $content .= '<br />';

         $content .= '<textarea name="comments" placeholder="Give us your comments"></textarea>';
         $content .= '<br />';

         $content .= '<input type="submit" name="info_submit" value="SUBMIT INFORMATION" />';
         $content .= '<br /> <br />';


      $content .= '</form>';


     // $content .= '<form method="post" action="http://localhost/wordpress/results">';

     // $content .= '<input type="submit" name="my_receive_form" value="RETRIEVE INFORMATION" />';

     // $content .= '</form>';

      return $content;
   }
   

   add_shortcode('my_contact_form','my_form');




// Code Below is used to insert data into the Table.

if(!empty($_POST['info_submit']) && !empty($_POST['full_name']) && !empty($_POST['email_address']))
      {

         global $wpdb;

         $name = sanitize_text_field($_POST['full_name']);
         $email = sanitize_email($_POST['email_address']);
         $phone = $_POST['phone_number'];
         $comments = sanitize_text_field($_POST['comments']);

         
          $sql = $wpdb->prepare(" INSERT INTO ".$wpdb->prefix."form_submission (contact_name, contact_email, contact_phone, contact_comments) VALUES 
            (%s, %s, %d, %s )", $name, $email, $phone, $comments);


         $wpdb->query($sql);

         
        //wp_safe_redirect('http://localhost/wordpress/thank-you');
         redirect('http://localhost/wordpress/thank-you');
         exit;

      }





// This code is adding plugin options into the menu.


function basicplugin_register_options_page() {

  add_menu_page('Basic Plugin Title', 'Basic Plugin', 'manage_options', 'basic-plugin', 'receive_data');
}



add_action('admin_menu', 'basicplugin_register_options_page');




//This function displays data submitted by the user on the  plugin menu page.


 function receive_data(){

   global $wpdb;

   $results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."form_submission"); 
         if(!empty($results))                        
         {   
             echo "<div style=''>"; 
             echo "<table style='width:50%; border:3px red solid; margin:auto'>";
             echo "<tbody>";

             echo"<tr>";
             echo"<th>ID</th>";
             echo "<th>Name</th>";
             echo "<th>Email</th>";
             echo "<th>Phone Number</th>";
             echo "<th>Comments</th>";
             echo "</tr>";

             foreach($results as $row){ 
             echo "<tr>";
             echo "<td>" . $row->id. "</td>";
             echo "<td>" . $row->contact_name. "</td>";
             echo "<td>" . $row->contact_email. "</td>";
             echo "<td>" . $row->contact_phone. "</td>";
             echo "<td>" . $row->contact_comments. "</td>";
             echo "</tr>";
             }
             echo "</tbody>";
             echo "</table>"; 
             echo "</div>";

         } else {

            echo "<div style='margin:auto;'><strong><H1>NO DATA TO BE DISPLAYED!</H1></strong></div>";
         }
 
} 







?>



