<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
  * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v2.0 11-30-2018 davewest $
 */

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (zen_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          zen_set_testimonials_status($_GET['bID'], $_GET['flag']);
          $messageStack->add_session(SUCCESS_PAGE_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }
        zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']));
        break;
      case 'insert':
      case 'update':
        if (isset($_POST['testimonials_id'])) $testimonials_id = zen_db_prepare_input($_POST['testimonials_id']);
        $testimonials_title = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_title']));
	$testimonials_name = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_name']));
	$testimonials_mail = zen_db_prepare_input($_POST['testimonials_mail']);
	$rating = zen_db_prepare_input($_POST['tm_rating']);
        $feedback = zen_db_prepare_input(zen_sanitize_string($_POST['tm_feedback']));
        $contact_user = zen_db_prepare_input($_POST['tm_contact_user']);
        $contact_phone = zen_db_prepare_input($_POST['tm_contact_phone']);
        $make_public = zen_db_prepare_input($_POST['tm_make_public']);
        $privacy = zen_db_prepare_input($_POST['tm_privacy_conditions']);
        $tmv_yes = zen_db_prepare_input($_POST['helpful_yes']);
        $tmv_no = zen_db_prepare_input($_POST['helpful_no']);
	$testimonials_date = (empty($_POST['date_added']) ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_db_prepare_input($_POST['date_added']));
        $testimonials_html_text = zen_db_prepare_input(zen_sanitize_string($_POST['testimonials_html_text']));
        
        $page_error = false;
        if (empty($testimonials_name)) {
          $messageStack->add(ERROR_PAGE_AUTHOR_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_mail)) {
          $messageStack->add(ERROR_PAGE_EMAIL_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_title)) {
          $messageStack->add(ERROR_PAGE_TITLE_REQUIRED, 'error');
          $page_error = true;
        }
        if (empty($testimonials_html_text)) {
		$messageStack->add(ERROR_PAGE_TEXT_REQUIRED, 'error');
          $page_error = true;
        }
        if ($page_error == false) {
        
		$language_id = (int)$_SESSION['languages_id'];

	$sql_data_array = array(array('fieldName'=>'language_id', 'value'=>$language_id, 'type'=>'integer'), 
	                        array('fieldName'=>'testimonials_title', 'value'=>$testimonials_title, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_name', 'value'=>$testimonials_name, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_html_text', 'value'=>$testimonials_html_text, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'testimonials_mail', 'value'=>$testimonials_mail, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_rating', 'value'=>$rating, 'type'=>'integer'),
                                array('fieldName'=>'tm_feedback', 'value'=>$feedback, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_user', 'value'=>$contact_user, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_contact_phone', 'value'=>$contact_phone, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_make_public', 'value'=>$make_public, 'type'=>'stringIgnoreNull'),
                                array('fieldName'=>'tm_privacy_conditions', 'value'=>$privacy, 'type'=>'integer'),
                                array('fieldName'=>'helpful_yes', 'value'=>$tmv_yes, 'type'=>'integer'),
                                array('fieldName'=>'helpful_no', 'value'=>$tmv_no, 'type'=>'integer'),
                               );  
                               
          if ($action == 'insert') {
          
	     if (empty($_POST['date_added'])) {
		$testimonials_date = 'now()';
	     }else {
		$testimonials_date = zen_date_raw($_POST['date_added']);
	     }
		
            $sql_data_array[] = array('fieldName'=>'status', 'value'=>1, 'type'=>'integer');
            $sql_data_array[] = array('fieldName'=>'date_added', 'value'=>$testimonials_date, 'type'=>'noquotestring');
                                   
            $db->perform(TABLE_TESTIMONIALS_MANAGER, $sql_data_array);           	
            $testimonials_id = zen_db_insert_id();
            $messageStack->add_session(SUCCESS_PAGE_INSERTED, 'success');
            
          } elseif ($action == 'update') {
          
            $sql_data_array[] = array('fieldName'=>'status', 'value'=>1, 'type'=>'integer');
            $sql_data_array[] = array('fieldName'=>'last_update', 'value'=>'now()', 'type'=>'noquotestring');

            $db->perform(TABLE_TESTIMONIALS_MANAGER, $sql_data_array, 'update', "testimonials_id = '" . (int)$testimonials_id . "'");
            
            $messageStack->add_session(SUCCESS_PAGE_UPDATED, 'success');
          }
 
  
       if ($_POST['avatar_image'] != '') {
        // add image manually
        $existing_avatar = TESTIMONIAL_IMAGE_DIRECTORY . $_POST['avatar_image'];
        $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_image = '" . $existing_avatar . "'
                            where testimonials_id = '" . (int)$testimonials_id . "'");
      } else {
        if ($testimonials_image = new upload('testimonials_image')) {
          $testimonials_image->set_extensions(array('jpg','gif','png'));
          $testimonials_image->set_destination(DIR_FS_CATALOG_IMAGES . TESTIMONIAL_IMAGE_DIRECTORY);
          if ($testimonials_image->parse() && $testimonials_image->save()) {
            $testimonials_image_name = zen_db_input(TESTIMONIAL_IMAGE_DIRECTORY . $testimonials_image->filename);
          }
          if ($testimonials_image->filename != 'none' && $testimonials_image->filename != '') {
            // save filename when not set to none and not blank
            $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_image = '" . $testimonials_image_name . "'
                          where testimonials_id = '" . (int)$testimonials_id . "'");
          } 
        }
      }

       if ($_POST['image_upimg'] != '') {
        // add image manually
        $existing_image = TM_UPLOAD_DIRECTORY . $_POST['image_upimg'];
        $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_upimg = '" . $existing_image . "'
                            where testimonials_id = '" . (int)$testimonials_id . "'");
      } else {
        if ($testimonials_upimg = new upload('testimonials_upimg')) {
          $testimonials_upimg->set_extensions(array('jpg','png'));
          $testimonials_upimg->set_destination(DIR_FS_CATALOG_IMAGES . TM_UPLOAD_DIRECTORY);
          if ($testimonials_upimg->parse() && $testimonials_upimg->save()) {
            $testimonials_upimg_name = zen_db_input(TM_UPLOAD_DIRECTORY . $testimonials_upimg->filename);
          }
          if ($testimonials_upimg->filename != 'none' && $testimonials_upimg->filename != '') {
            // save filename when not set to none and not blank
            $db->Execute("update " . TABLE_TESTIMONIALS_MANAGER . "
                            set testimonials_upimg = '" . $testimonials_upimg_name . "'
                          where testimonials_id = '" . (int)$testimonials_id . "'");
          } 
        }
      }
       
          zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'bID=' . $testimonials_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $testimonials_id = zen_db_prepare_input($_GET['bID']);
        $db->Execute("delete from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . (int)$testimonials_id . "'");
        $messageStack->add_session(SUCCESS_PAGE_REMOVED, 'success');
        zen_redirect(zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page']));
        break;
    }
  } 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  if (typeof _editor_url == "string") HTMLArea.replaceAll();
  }
  // -->
</script>
</head>
<body onLoad="init()">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<br />
<div>
 <div class="main" ><a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=mor'); ?>"><?php echo zen_image_button('button_remove.gif', 'Remove Testimonials Manager'); ?></a> <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=ckupdate', 'NONSSL'); ?>"><?php echo zen_image_button('button_check_new_version.gif', 'Update Testimonials Manager'); ?></a> </div>     
  </div> 
  
<?php if ($action == 'mor') { 
  $action = '';
  ?>
<div class="BMalert"><?php echo TEXT_REMOVE_WARRING; ?>
<br /> <br />
<a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER); ?>"> <?php echo zen_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a> 
<a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=remove', 'NONSSL'); ?>"><?php echo zen_image_button('button_remove.gif', 'Remove Testimonials Manager'); ?></a>


</div>
  <?php  
    } elseif ($action == 'ckupdate') { 
  $action = '';
  ?>
<div class="BMalert"><br /> 
<?php echo TEXT_UPDATE_WARRING; ?>
<br />
<?php echo TEXT_UPDATE_DISCLAMER; ?>
<br /> <br /> 
       <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER) ?>"><?php echo zen_image_button('button_cancel.gif', IMAGE_CANCEL) ?></a> <a href="<?php echo zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=ckupd') ?>"><?php echo zen_image_button('button_check_new_version.gif', 'Check for Updated Testimonials Manager') ?></a>
       </div> 
       
<?php  }elseif ($action == 'remove') { 
              $action = '';
      
   $categoryid = array();
	$id_result = $db->Execute("SELECT configuration_group_id FROM ". TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Testimonials Manager'");
	if (!$id_result->EOF) {
			$categoryid = $id_result->fields;
			$isit_installed .= 'Testimonials Manager Configuration_Group ID = ' . $categoryid['configuration_group_id']. '<br>';
			$rm_config_id = $categoryid['configuration_group_id'];
			// kill config
			$db->Execute("DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_group_id = '" . $rm_config_id ."'");
                        $db->Execute("DELETE FROM ". TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = '" . $rm_config_id ."'");
                        $isit_installed .= 'deleted Testimonials Manager Configuration files!<br />';
                        // kill admin pages for ZC1.5.x only
                        if (function_exists('zen_deregister_admin_pages')) {  
                               zen_deregister_admin_pages('toolsTestimonialsManager');
                               zen_deregister_admin_pages('TMConfig');
                        $isit_installed .= 'deleted Testimonials Manager Admin Pages!<br />';
                        }

                     

if ($sniffer->table_exists(TABLE_TESTIMONIALS_MANAGER)) $db->Execute("DROP TABLE " . TABLE_TESTIMONIALS_MANAGER );

          
//check for and remove the auto loader page so it wont install again
  if(file_exists(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'functions/extra_functions/testimonials_manager_functions.php')) {
         if(!unlink(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'functions/extra_functions/testimonials_manager_functions.php')) {
		$isit_installed .= 'Autoloader deleted<br />';
	};
    }

///done 
     echo $isit_installed . '<br /><br />Testimonials Manager SQL and Menues have been deleted! Please delete all files! ' . ' <a href="' . zen_href_link(FILENAME_DEFAULT) .'"> ' . zen_image_button('button_go.gif', 'Exit this installer') . '</a><br />';
    exit;

    } else { 
//not done 
    $messageStack->add_session('Failed Finding Testimonials Manager Configuration_Group ID!<br />No change made.', 'error');
    echo $isit_installed . '<br /><br />Read the help to help figure out what went wrong ' . ' <a href="' . zen_href_link(FILENAME_DEFAULT) .'"> ' . zen_image_button('button_go.gif', 'Exit this installer') . '</a><br />';
    	   
    }	
    


} elseif ($action == 'ckupd') {
               $action = '';
           
        $module_constant = 'TM_VERSION'; // This should be a UNIQUE name followed by _VERSION for convention
	$module_name = "Testimonial Manager"; // This should be a plain English or Other in a user friendly way
	$zencart_com_plugin_id = 299; // from zencart.com plugins - Leave Zero not to check
	$current_version = TM_VERSION; //this should be the current installed version

  $configuration_group_id = '';
  $checklinknote = '';

    $config = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key= '" . $module_name . "'");
    $configuration_group_id = $config->fields['configuration_group_id'];

// Version Checking 
$new_version_details = plugin_version_check_for_updates($zencart_com_plugin_id, $current_version);
    if ($new_version_details != FALSE) {
        echo '<div class="BMalert">Version ' . $new_version_details['latest_plugin_version']. ' of ' . $new_version_details['title'] . ' is available at <a href="' . $new_version_details['link'] . '" target="_blank">[Details]</a>';
    } else {
     echo '<div class="BMalert">No New Version for Testimonials Manager is available or ID is set to 0.</div>';
     
    }
 } //end remove-update  ?>
    
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'new') {  
    $form_action = 'insert';

    $parameters = array('testimonials_title' => '',
	                'language_id' => '',  
	                'tm_rating' => '',  
	                'tm_feedback' => '',  
			'testimonials_name' => '',
	                'testimonials_mail' => '',
			'testimonials_image' => '',
			'testimonials_title' => '',  
                        'testimonials_html_text' => '',
                        'tm_contact_user' => '',
                        'tm_contact_phone' => '',
                        'tm_make_public' => '',
                        'tm_privacy_conditions' => '',
                        'helpful_yes' => '',
                        'helpful_no' => '',
                        'tm_gen_info' => '',
                        'testimonials_upimg' => '',
			'date_added' => '',
                        'status' =>'');

    $bInfo = new objectInfo($parameters);

    if (isset($_GET['bID'])) {
      $form_action = 'update';

      $bID = zen_db_prepare_input($_GET['bID']);

      $page_query = "select * from " . TABLE_TESTIMONIALS_MANAGER . " where testimonials_id = '" . $_GET['bID'] . "'";
      $page = $db->Execute($page_query);
      $bInfo->objectInfo($page->fields);
    } elseif (zen_not_null($_POST)) {
      $bInfo->objectInfo($_POST);
    }
?>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo zen_draw_form('new_page', FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'action=' . $form_action, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo zen_draw_hidden_field('testimonials_id', $bID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo 'Feedback on:'; ?></td>
            <td class="main"><?php echo zen_draw_textarea_field('tm_feedback', 'soft', '60', '2', $bInfo->tm_feedback, '', true) . TEXT_FIELD_REQUIRED; ?></td>
          </tr>           
          <tr>
            <td class="main"><?php echo 'Testimonial Rating:'; ?></td>
            <td class="main"><?php echo zen_draw_input_field('tm_rating', $bInfo->tm_rating, 'min="0" max="5"', true, 'number') . TEXT_FIELD_REQUIRED; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_NAME; ?></td>
            <td class="main"><?php echo zen_draw_input_field('testimonials_name', $bInfo->testimonials_name, '', true) . TEXT_FIELD_REQUIRED; ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_MAIL; ?></td>
            <td class="main"><?php echo zen_draw_input_field('testimonials_mail', $bInfo->testimonials_mail, '', true) . TEXT_FIELD_REQUIRED; ?></td>
          </tr>
		  <?php if ($form_action == 'insert') { ?>
		  <tr>
            <td colspan=2><?php echo TEXT_TESTIMONIALS_DATE_INFO; ?></td>
			</tr>
			<tr>
			<td class="main"><?php echo TEXT_TESTIMONIALS_DATE; ?></td>
            <td class="main"><?php echo zen_draw_input_field('date_added', zen_date_short($bInfo->date_added), '', false) . TEXT_TESTIMONIALS_OPTIONAL . ENTRY_DATE_ADDED_TEXT; ?></td>
          </tr>
		  <?php
		  }
		  ?>
		  <tr>
            <td class="main"><?php echo 'Can we contact User:'; ?></td>
            <td class="main"><?php echo zen_draw_radio_field('tm_contact_user', 'no', ($bInfo->tm_contact_user == 'no' ? true : false),'id="email_format_left"') . '<label for="email_format_left">no</label>&nbsp;&nbsp;' . zen_draw_radio_field('tm_contact_user', 'email', ($bInfo->tm_contact_user == 'email' ? true : false),'id="email_format_left"') . '<label for="email_format_left">email</label>&nbsp;&nbsp;' . zen_draw_radio_field('tm_contact_user', 'phone', ($bInfo->tm_contact_user == 'phone' ? true : false), 'id="email_format_right"') . '<label for="email_format_right">phone</label>&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_FIELD_REQUIRED; ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo 'Contact User phone:'; ?></td>
            <td class="main"><?php echo zen_draw_input_field('tm_contact_phone', $bInfo->tm_contact_phone, '', false) . TEXT_TESTIMONIALS_OPTIONAL; ?></td>
          </tr>
          
          <tr>
            <td class="main"><?php echo 'Make Public:'; ?></td>
            <td class="main"><?php echo zen_draw_radio_field('tm_make_public', 'yes', ($bInfo->tm_make_public == 'yes' ? true : false),'id="tm_make_public_left"') . '<label for="tm_make_public_left">Yes</label>&nbsp;&nbsp;' . zen_draw_radio_field('tm_make_public', 'no', ($bInfo->tm_make_public == 'no' ? true : false), 'id="tm_make_public_right"') . '<label for="tm_make_public_right">No</label>&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_FIELD_REQUIRED; ?> </td>
          </tr>    
		  <tr>
            <td class="main"><?php echo 'Privacy checked:'; ?></td>
            <td class="main"><?php echo zen_draw_radio_field('tm_privacy_conditions', 1, ($bInfo->tm_privacy_conditions == 1 ? true : false),'id="email_format_left"') . '<label for="email_format_left">Yes</label>&nbsp;&nbsp;' . zen_draw_radio_field('tm_privacy_conditions', 0, ($bInfo->tm_privacy_conditions == 0 ? true : false), 'id="email_format_right"') . '<label for="email_format_right">No</label>&nbsp;&nbsp;&nbsp;&nbsp;' . TEXT_FIELD_REQUIRED; ?> </td>
          </tr>    
		   <tr>
            <td class="main"><?php echo TEXT_TESTIMONIALS_TITLE; ?></td>
            <td class="main"><?php echo zen_draw_input_field('testimonials_title', $bInfo->testimonials_title, '', true) . TEXT_FIELD_REQUIRED; ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><?php echo TEXT_TESTIMONIALS_HTML_TEXT; ?></td>
            <td class="main"><?php echo zen_draw_textarea_field('testimonials_html_text', 'soft', '60', '10', $bInfo->testimonials_html_text, '', true) . TEXT_FIELD_REQUIRED; ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><?php echo 'Other fields:'; ?></td>
            <td class="main"><?php echo zen_draw_textarea_field('tm_gen_info', 'soft', '60', '10', $bInfo->tm_gen_info, 'disabled="disabled"', false); ?></td>
          </tr>		  
		  
    <?php
     if (($bInfo->testimonials_image) != ('')) {
   ?>
           <tr>
            <td valign="top" class="main"><?php echo TEXT_AVATAR_CURRENT_IMAGE; ?></td>
			<td class="main"><?php echo $bInfo->testimonials_image; ?></td>
          </tr>
<?php
}
?> 

           <tr>
            <td valign="top" class="main"><?php echo TEXT_AVATAR_PAGE_IMAGE; ?></td>
			<td class="main"><?php echo zen_draw_file_field('testimonials_image') . TEXT_TESTIMONIALS_OPTIONAL; ?></td>
          </tr>

			<tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td colspan="3" class="main" valign="top"><?php echo TEXT_AVATAR_IMAGE_MANUAL . '&nbsp;' . zen_draw_input_field('avatar_image'); ?></td>
              </tr>

      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
          <tr>
            <td class="main"><?php echo '&nbsp;&nbsp;Helpful yes voting! '; ?></td>
            <td class="main"><?php echo zen_draw_input_field('helpful_yes', $bInfo->helpful_yes, '', false) . TEXT_TESTIMONIALS_OPTIONAL; ?></td>
          </tr>	
          <td class="main"><?php echo '&nbsp;&nbsp;Helpful no voting! '; ?></td>
            <td class="main"><?php echo zen_draw_input_field('helpful_no', $bInfo->helpful_no, '', false) . TEXT_TESTIMONIALS_OPTIONAL; ?></td>
          </tr>		  
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
              <?php
     if (($bInfo->testimonials_upimg) != ('')) {
   ?>
           <tr>
            <td valign="top" class="main"><?php echo TEXT_INFO_CURRENT_IMAGE; ?></td>
			<td class="main"><?php echo $bInfo->testimonials_upimg; ?></td>
          </tr>
<?php
}
?> 

           <tr>
            <td valign="top" class="main"><?php echo TEXT_INFO_PAGE_IMAGE; ?></td>
			<td class="main"><?php echo zen_draw_file_field('testimonials_upimg') . TEXT_TESTIMONIALS_OPTIONAL; ?></td>
          </tr>

			<tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td colspan="3" class="main" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_MANUAL . '&nbsp;' . zen_draw_input_field('image_upimg'); ?></td>
              </tr>

      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" class="main" align="left" valign="top" nowrap><?php echo (($form_action == 'insert') ? zen_image_submit('button_insert.gif', IMAGE_INSERT) : zen_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['bID']) ? 'bID=' . $_GET['bID'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else { 
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow" width="100%">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TESTIMONIALS; ?></td>
		<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MAIL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent"></td>
                <td class="dataTableHeadingContent"></td>
              </tr>
                
<?php
    $testimonials_query_raw = "select testimonials_id, language_id, testimonials_image, testimonials_title, testimonials_name, testimonials_mail, testimonials_html_text, tm_rating, tm_feedback, tm_contact_user, tm_contact_phone, tm_privacy_conditions, tm_gen_info, testimonials_upimg, helpful_yes, helpful_no, status, date_added, last_update, tm_make_public from " . TABLE_TESTIMONIALS_MANAGER . " order by date_added DESC, testimonials_title";
    $testimonials_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $testimonials_query_raw, $testimonials_query_numrows);
    $testimonials = $db->Execute($testimonials_query_raw);

while (!$testimonials->EOF) {
     if ((!isset($_GET['bID']) || (isset($_GET['bID']) && ($_GET['bID'] == $testimonials->fields['testimonials_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
        $bInfo_array = array_merge($testimonials->fields);
        $bInfo = new objectInfo($bInfo_array);
      }
      if (isset($bInfo) && is_object($bInfo) && ($testimonials->fields['testimonials_id'] == $bInfo->testimonials_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $testimonials->fields['testimonials_title']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $testimonials->fields['testimonials_name']; ?></td>
		<td class="dataTableContent" align="left"><?php echo $testimonials->fields['testimonials_mail']; ?></td>
                <td class="dataTableContent"><?php echo $testimonials->fields['date_added']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($testimonials->fields['status'] == '1') {
        echo zen_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Approved', 10, 10) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=0') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Pending', 10, 10) . '</a>';
      } else {
        echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $testimonials->fields['testimonials_id'] . '&action=setflag&flag=1') . '">' . zen_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Approved', 10, 10) . '</a>&nbsp;&nbsp;' . zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Pending', 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($bInfo) && is_object($bInfo) && ($testimonials->fields['testimonials_id'] == $bInfo->testimonials_id)) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, zen_get_all_get_params(array('bID')) . 'bID=' . $testimonials->fields['testimonials_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                <td class="dataTableContent" align="right"></td>
              </tr>
<?php

 $testimonials->MoveNext();
    }
?>
                  <tr>
                    <td class="smallText" valign="top"><?php echo $testimonials_split->display_count($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $testimonials_split->display_links($testimonials_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?></td>
                   <td  colspan="5"class="dataTableContent" align="right"></td>
                  </tr>


              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'action=new') . '">' . zen_image_button('button_new_testimonial.gif', IMAGE_NEW_PAGE) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
if ($bInfo->status == 0) {
$teststatus = 'Pending';
} else {
$teststatus = 'Approved';
}
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $bInfo->testimonials_title . '</b>');

      $contents = array('form' => zen_draw_form('testimonials', FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $bInfo->testimonials_title . '</b>');
    //  if ($bInfo->testimonials_image) $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($bInfo)) {
	  
        $heading[] = array('text' => '<b>' . $bInfo->testimonials_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=new') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->testimonials_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br /><br /><br />');

        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_STATUS . ' '  . $teststatus);

		if (zen_not_null($bInfo->testimonials_image)) {
        $contents[] = array('text' => '<br />' . zen_image(DIR_WS_CATALOG_IMAGES . $bInfo->testimonials_image, $bInfo->testimonials_title, TESTIMONIAL_IMAGE_WIDTH, TESTIMONIAL_IMAGE_HEIGHT) . '<br /><br />' . $bInfo->testimonials_title);
		} else {
		$contents[] = array('text' => '<br />' . TEXT_IMAGE_NONEXISTENT);
		}
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_RATING . ' '  . $bInfo->tm_rating . ' Stars');
        $contents[] = array('text' => '<br /><b>' . TEXT_INFO_TESTIMONIALS_PUBLIC  . ' '  . $bInfo->tm_make_public . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_FEEDBACK . ' '  . $bInfo->tm_feedback);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_NAME . ' '  . $bInfo->testimonials_name);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_EMAIL . ' ' . $bInfo->testimonials_mail);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_TITLE . ' ' . $bInfo->testimonials_title);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_DESCRIPTION . '<br /> ' . $bInfo->testimonials_html_text);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT . ' '  . $bInfo->tm_contact_user);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_CONTACT_PHONE . ' '  . $bInfo->tm_contact_phone);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_PRIVACY . ' '  . $bInfo->tm_privacy_conditions);
        $contents[] = array('text' => '<br />' . 'Helpful yes voting' . ' '  . $bInfo->helpful_yes);
        $contents[] = array('text' => '<br />' . 'Helpful no voting' . ' '  . $bInfo->helpful_no);
        $contents[] = array('text' => '<br />' . TEXT_INFO_TESTIMONIALS_GEN_INFO . '<br />'  . $bInfo->tm_gen_info);
        $contents[] = array('text' => '<br />' . 'Submited image:' . '<br />'  . $bInfo->testimonials_upimg);
        
        $contents[] = array('text' => '<br />' . TEXT_DATE_TESTIMONIALS_CREATED . ' ' . zen_date_short($bInfo->date_added));

        if (zen_not_null($bInfo->last_update)) {
          $contents[] = array('text' => TEXT_DATE_TESTIMONIALS_LAST_MODIFIED . ' ' . zen_date_short($bInfo->last_update));
        } else {		
          $contents[] = array('text' => TEXT_DATE_TESTIMONIALS_LAST_MODIFIED);
		}
		
        if ($bInfo->date_scheduled) $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_SCHEDULED_AT_DATE, zen_date_short($bInfo->date_scheduled)));

        if ($bInfo->expires_date) {
          $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_EXPIRES_AT_DATE, zen_date_short($bInfo->expires_date)));
        } elseif ($bInfo->expires_impressions) {
          $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_EXPIRES_AT_IMPRESSIONS, $bInfo->expires_impressions));
        }

        if ($bInfo->date_status_change) $contents[] = array('text' => '<br />' . sprintf(TEXT_TESTIMONIALS_STATUS_CHANGE, zen_date_short($bInfo->date_status_change)));
      }
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  } 
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
