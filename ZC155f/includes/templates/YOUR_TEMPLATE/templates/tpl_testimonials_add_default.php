<?php
/**
 * Testimonials Manager
 *
 * @package Template System
 * @copyright 2007 Clyde Jones
  * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Testimonials_Manager.php v2.0 11-14-2018 davewest $
 */
?>


<div class="centerColumn" id="testimonialDefault">
<?php echo HEADING_ADD_TITLE; ?>

<div class="center">
<?php
/** display shop total reviews */
 include($template->get_template_dir('/tpl_shop_total_reviews_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_shop_total_reviews_default.php'); ?>
</div>

<?php echo zen_draw_form('new_testimonial', zen_href_link(FILENAME_TESTIMONIALS_ADD, 'action=send', $request_type),'post','enctype="multipart/form-data" '); ?>

<?php if (TESTIMONIAL_STORE_NAME_ADDRESS == 'true') { ?>
<address><?php echo nl2br(STORE_NAME_ADDRESS); ?></address>
<?php } ?>

<?php
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

<br class="clearBoth" />
<div class="mainContent success"><?php echo TESTIMONIAL_SUCCESS; ?></div>
<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) .'</a>'; ?></div>

<?php
  } else {
?>

<?php if (DEFINE_TESTIMONIAL_STATUS >= '1' and DEFINE_TESTIMONIAL_STATUS <= '2') { ?>
<div id="pageThreeMainContent">
<?php
require($define_page);
?>
</div>
<?php } ?>
<br class="clearBoth" />

<?php if ($messageStack->size('new_testimonial') > 0) echo $messageStack->output('new_testimonial'); ?>
<div class="pseudolink back">Required <i class="fa fa-exclamation-triangle fa-fw"></i></div>
<br class="clearBoth" />

<div class="tm-wrapper"> 

<div class="main_box">
<form name="site_review" method="post" action="#">
<div class="logo2">
<h2>We <i class="fa fa-heart fa-fw"></i> Feedback!</h2>
</div>

<p class="questionarea">Please select regarding which experience you want to provide feedback on.<br />Your name well appeir on reviews. Email Address well <strong>NOT</strong> be displayed on reviews.</p>
  
<div class="boxcontainer">
<div id="reviewsWriteReviewsRate"><?php echo TESTIMONIAL_GIVE_RATING; ?></div>
<div class="masterdog">
<div class="rating">
<input name="rating" value="5" id="tooltip-5" type="radio"><label for="tooltip-5"> Excellent</label>
<input name="rating" value="4" id="tooltip-4" type="radio"><label for="tooltip-4"> Very Good</label>
<input name="rating" value="3" id="tooltip-3" type="radio"><label for="tooltip-3"> Good</label>
<input name="rating" value="2" id="tooltip-2" type="radio"><label for="tooltip-2"> Fair</label>
<input name="rating" value="1" id="tooltip-1" type="radio"><label for="tooltip-1"> Poor</label>
<span id="show_me-5">Excellent</span><span id="show_me-4">Very Good</span><span id="show_me-3">Good</span><span id="show_me-2">Fair</span><span id="show_me-1">Poor</span><span id="remove_me"></span> 
</div></div>
<div class="starspace"></div>
</div>

<!-- INPUT LAYOUT START -->
<div class="answersection">

    <div class="switch-field">
    
<!-- online shopping experience //-->      
      <div>
      <?php echo zen_draw_radio_field('feedback', LABEL_FEEDBACK_1, '', 'id="switch_1"') . '<label for="switch_1">' . LABEL_FEEDBACK_1 . '</label>'; ?>
       <div class="reveal-if-active go-up"> 
                 
       <div>
       <?php echo zen_draw_input_field('testimonials_name', $testimonials_name, ' id="testimonials_name_1" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Your name (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_1"'); ?>
       <div class="label"><?php echo TEXT_TESTIMONIALS_NAME; ?> </div>
       </div>

      <div >
      <?php echo zen_draw_input_field('testimonials_mail', $testimonials_mail, ' id="testimonials_mail" spellcheck="false" title="Please enter a E-Mail address (dave@addme.com)" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" class="require-if-active resizeField" data-require-pair="#switch_1"', 'email') ; ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_MAIL; ?> </div>
      </div>
       <div >
      <?php echo zen_draw_input_field('testimonials_title', 'online shopping experience', ' id="testimonials_title" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Give us a Title (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_1"'); ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_TITLE; ?> </div>
      </div>

      <div>
      <div>Was you able to find what you wanted?<br />
      <?php echo zen_draw_radio_field('find-1', 'yes', '', 'id="find_yes"') . '<label for="find_yes" class="inputLabel">Yes</label>' . zen_draw_radio_field('find-1', 'no', '', 'id="find_no"') . '<label for="find_no" class="inputLabel">No</label>'; ?>
      </div></div>
      
      <p>Tell us about your experience. What did you like? What can we do better?</p>
     <div >
     <?php echo zen_draw_textarea_field('testimonials_html_text', '70', '4', $testimonials_html_text, 'id="testimonials_html_text" pattern="^([- \w\d\u00c0-\u024f]+)$" class="require-if-active resizeField" data-require-pair="#switch_1"');  ?>
     <div class="label"><?php echo TEXT_TESTIMONIALS_DESCRIPTION; ?></div>
     </div>
      
       </div>
       </div>
       
<!-- online order experience //-->       
      <div>       
      <?php echo zen_draw_radio_field('feedback', LABEL_FEEDBACK_2, '', 'id="switch_2"') . '<label for="switch_2">' . LABEL_FEEDBACK_2 . '</label>'; ?>
      <div class="reveal-if-active go-up"> 
                 
       <div>
       <?php echo zen_draw_input_field('testimonials_name', $testimonials_name, ' id="testimonials_name_2" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Your name (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_2"'); ?>
       <div class="label"><?php echo TEXT_TESTIMONIALS_NAME; ?> </div>
       </div>
      <div >
      <?php echo zen_draw_input_field('testimonials_mail', $testimonials_mail, ' id="testimonials_mail" spellcheck="false" title="Please enter a E-Mail address (dave@addme.com)" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" class="require-if-active resizeField" data-require-pair="#switch_2"', 'email') ; ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_MAIL; ?> </div>
      </div>
       <div >
      <?php echo zen_draw_input_field('testimonials_title', 'online order experience', ' id="testimonials_title" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Give us a Title (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_2"'); ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_TITLE; ?> </div>
      </div>
      <div>
      <div>Have you already placed an order?<br />
      <?php echo zen_draw_radio_field('order1', 'yes', '', 'id="order_yes"') . '<label for="order_yes" class="inputLabel">Yes</label>' . zen_draw_radio_field('order1', 'no', '', 'id="order_no"') . '<label for="order_no" class="inputLabel">No</label>'; ?>
      </div></div>        
        
      <p>Tell us about your experience. What did you like? What can we do better?</p>
      <div >
     <?php echo zen_draw_textarea_field('testimonials_html_text', '70', '4', $testimonials_html_text, 'id="testimonials_html_text" pattern="^([- \w\d\u00c0-\u024f]+)$" class="require-if-active resizeField" data-require-pair="#switch_2"');  ?>
     <div class="label"><?php echo TEXT_TESTIMONIALS_DESCRIPTION; ?></div>
     </div>
           
       </div>
       </div>
<!-- Mobile shopping experience //-->       
      <div>       
      <?php echo zen_draw_radio_field('feedback', LABEL_FEEDBACK_3, '', 'id="switch_3"') . '<label for="switch_3">' . LABEL_FEEDBACK_3 . '</label>'; ?>
       <div class="reveal-if-active go-up"> 
                 
       <div>
       <?php echo zen_draw_input_field('testimonials_name', $testimonials_name, ' id="testimonials_name_3" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Your name (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_3"'); ?>
       <div class="label"><?php echo TEXT_TESTIMONIALS_NAME; ?> </div>
       </div>
      <div >
      <?php echo zen_draw_input_field('testimonials_mail', $testimonials_mail, ' id="testimonials_mail" spellcheck="false" title="Please enter a E-Mail address (dave@addme.com)" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" class="require-if-active resizeField" data-require-pair="#switch_3"', 'email') ; ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_MAIL; ?> </div>
      </div>
       <div >
      <?php echo zen_draw_input_field('testimonials_title', 'Mobile shopping experience', ' id="testimonials_title" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Give us a Title (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_3"'); ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_TITLE; ?> </div>
      </div>
      
      <div>
      <?php echo zen_draw_hidden_field('mobile_device', 'none'); ?>
      <?php echo zen_draw_checkbox_field('mobile_device', 'yes', false, ' id="mobile_device" ') . '<label for="mobile_device" >Did you use a mobile device?</label>'; ?>
      <div class="reveal-if-active">
       <div for="mobile_device_name">Type of Mobile device!: </div>
       <?php echo zen_draw_input_field('mobile_device_name', $mobile_device_name, ' id="mobile_device_name" class="resizeField" placeholder="iPhone x" title="Your mobile device type?" data-require-pair="mobile_device"'); ?>    
      <br />  
       <div for="papermap_qa">Screen size or display size? </div>
       <?php echo zen_draw_input_field('screen_size', $screen_size, ' id="screen_size" class="resizeField" placeholder="1 x 3 inch" title="Screen size or display size" data-require-pair="mobile_device"'); ?>
      </div></div>      
      
      <p>Tell us about your experience. What did you like? What can we do better?</p>
      <div >
     <?php echo zen_draw_textarea_field('testimonials_html_text', '70', '4', $testimonials_html_text, 'id="testimonials_html_text" pattern="^([- \w\d\u00c0-\u024f]+)$" class="require-if-active resizeField" data-require-pair="#switch_3"');  ?>
     <div class="label"><?php echo TEXT_TESTIMONIALS_DESCRIPTION; ?></div>
     </div>
              
       </div>
       </div>
<!-- Store Experience //-->       
      <div>      
      <?php echo zen_draw_radio_field('feedback', LABEL_FEEDBACK_4, '', 'id="switch_4"') . '<label for="switch_4">' . LABEL_FEEDBACK_4 . '</label>'; ?>
       <div class="reveal-if-active go-up"> 
                 
       <div>
       <?php echo zen_draw_input_field('testimonials_name', $testimonials_name, ' id="testimonials_name_4" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Your name (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_4"'); ?>
       <div class="label"><?php echo TEXT_TESTIMONIALS_NAME; ?> </div>
       </div>
      <div >
      <?php echo zen_draw_input_field('testimonials_mail', $testimonials_mail, ' id="testimonials_mail" spellcheck="false" title="Please enter a E-Mail address (dave@addme.com)" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" class="require-if-active resizeField" data-require-pair="#switch_4"', 'email') ; ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_MAIL; ?> </div>
      </div>
       <div >
      <?php echo zen_draw_input_field('testimonials_title', 'Store Experience', ' id="testimonials_title" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Give us a Title (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_4"'); ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_TITLE; ?> </div>
      </div>
      
      <div>
      <div class="switch-title">Selct category you want to provide feedback about.</div>
      <?php echo zen_draw_radio_field('feedback_about', 'Associate feedback', '', 'id="store_feedback_1"') . '<label for="store_feedback_1" class="feedbackLabel" title="Associate feedback">Option 1</label> <div> Associate feedback</div>';  ?>
<br class="clearBoth" />       
       <?php echo zen_draw_radio_field('feedback_about', 'In-Store experience', '', 'id="store_feedback_2"') . '<label for="store_feedback_2" class="feedbackLabel" title="In-Store experience">Option 2</label> <div> In-Store experience</div>'; ?>
<br class="clearBoth" />       
       <?php  echo zen_draw_radio_field('feedback_about', 'Purchase experience', '', 'id="store_feedback_3"') . '<label for="store_feedback_3" class="feedbackLabel" title="Purchase experience">Option 3</label> <div> Purchase experience</div>'; ?>
      </div>
      
      <p>Tell us about your experience below.</p>
      <div >
     <?php echo zen_draw_textarea_field('testimonials_html_text', '70', '4', $testimonials_html_text, 'id="testimonials_html_text" pattern="^([- \w\d\u00c0-\u024f]+)$" class="require-if-active resizeField" data-require-pair="#switch_4"');  ?>
     <div class="label"><?php echo TEXT_TESTIMONIALS_DESCRIPTION; ?></div>
     </div>
              
       </div>
       </div>
 <!-- Other experience //-->      
      <div>      
      <?php echo zen_draw_radio_field('feedback', LABEL_FEEDBACK_6, '', 'id="switch_5"') . '<label for="switch_5">' . LABEL_FEEDBACK_6 . '</label>'; ?>
      <div class="reveal-if-active go-up"> 
                 
       <div>
       <?php echo zen_draw_input_field('testimonials_name', $testimonials_name, ' id="testimonials_name_6" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Your name (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_5"'); ?>
       <div class="label"><?php echo TEXT_TESTIMONIALS_NAME; ?> </div>
       </div>
      <div >
      <?php echo zen_draw_input_field('testimonials_mail', $testimonials_mail, ' id="testimonials_mail" spellcheck="false" title="Please enter a E-Mail address (dave@addme.com)" pattern="^(([-\w\d]+)(\.[-\w\d]+)*@([-\w\d]+)(\.[-\w\d]+)*(\.([a-zA-Z]{2,5}|[\d]{1,3})){1,2})$" class="require-if-active resizeField" data-require-pair="#switch_5"', 'email') ; ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_MAIL; ?> </div>
      </div>
       <div >
      <?php echo zen_draw_input_field('testimonials_title', 'Other experience', ' id="testimonials_title" pattern="^([- \w\d\u00c0-\u024f]+)$" title="Give us a Title (no special characters)" class="require-if-active resizeField" data-require-pair="#switch_5"'); ?>
      <div class="label"><?php echo TEXT_TESTIMONIALS_TITLE; ?> </div>
      </div>         
      
      <p>Tell us about your experience. What did you like? What can we do better?</p>
      <div >
     <?php echo zen_draw_textarea_field('testimonials_html_text', '70', '4', $testimonials_html_text, 'id="testimonials_html_text" pattern="^([- \w\d\u00c0-\u024f]+)$" class="require-if-active resizeField" data-require-pair="#switch_5"');  ?>
     <div class="label"><?php echo TEXT_TESTIMONIALS_DESCRIPTION; ?></div>
     </div>
     
       </div>
       </div>
      
<div class="reveal">

    <div class="switch-footer">
      <div class="switch-title">If we have additional questions, may we contact you?</div>
      <input type="radio" id="switch_email" name="contact_3" value="email" /><label for="switch_email" class="inputLabel">email</label> 
      <input type="radio" id="switch_no" name="contact_3" value="no" checked /><label for="switch_no" class="inputLabel">No</label>
      <br class="clearBoth" />
      <div>
      <input type="radio" id="switch_phone" name="contact_3" value="phone" /><label for="switch_phone" class="inputLabel">phone</label>
      <div class="reveal-if-active">
       <div for="testimonials_title">Phone Number: </div>
      <input type="tel" name="telephone" id="telephone" class="require-if-active resizeField" placeholder="123-123-1234" title="Your phone number (888-123-1234)" pattern="^\d{3}-\d{3}-\d{4}$" data-require-pair="#switch_phone" >
      </div></div>
     
    </div>
<br class="clearBoth" /> 
<br />
      <div class="switch-footer">
      <div class="switch-title">(Yes) - I grant you the right and permission to publicly disclose my testimonial.<br />(No) - Make this testimonial Non-Public please.</div>
      <?php echo zen_draw_radio_field('make_public', 'yes', '', 'id="make_public_yes" checked ') . '<label for="make_public_yes" class="inputLabel">Yes</label> ' . zen_draw_radio_field('make_public', 'no', '', 'id="make_public_no"') . '<label for="make_public_no" class="inputLabel">No</label>'; ?>
      </div> 
<br class="clearBoth" /> 
<br />      
  <p class="guidelines">If you have an image for your avatar or other image you can upload it here. You can also upload more then one file by uploading a ZIP file. All images scaned, inspected, sized, or edited as necessary before displayed.  We reserve the right to delete any image we find offensive to us or others.</p>
 <div class="box center">
<input type="file" name="tm_file" id="tm_file" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" />
<label for="tm_file"><figure><svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Upload 1 File (Optional)</span></label>
</div>
         
    <div class="email-pot">
<label for="email-us"></label>
<?php echo zen_draw_input_field(SPAM_TEST_TEXT, '', ' id="email-us" title="do not fill in!" placeholder="do not fill in!" autocomplete="off"', 'email'); ?>
</div>

<div class="email-pot">
<p><?php echo HUMAN_TEXT_NOT_DISPLAYED; ?></p>
<?php echo zen_draw_radio_field(SPAM_TEST_USER, 'H1', '', 'id="user-1"') . '<span class="input-group-addon"><i class="fa fa-male fa-2x"></i></span>' . zen_draw_radio_field(SPAM_TEST_USER, 'C2', '', 'id="user-2"') . '<span class="input-group-addon"><i class="fa fa-laptop fa-2x"></i></span>'; ?>
</div>

<?php  if (SPAM_USE_SLIDER == 'true') { ?>
<div class="slidecontainer">
<p><?php echo HUMAN_TEXT_DISPLAYED; ?></p>
  <?php echo zen_draw_input_field(SPAM_TEST_IQ, '', ' min="0" max="50" value="25" class="slider" id="id1"', 'range'); ?>
<br /><br />
<span>Value:</span> <span id="f" style="font-weight:bold;color:red"></span>
 </div>

 <?php }  /*comment out to not use on this page*/ ?>
<br /><br />
<?php
  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
?>
<div class="switch-footer">
<div class="switch-title"><?php echo TEXT_PRIVACY_CONFIRM;?></div>
<?php echo zen_draw_checkbox_field('privacy_conditions', '1',  $privacy, ' class="checky" id="privacy_left" ') . '<label for="privacy_left" class="inputLabel">Agree</label>'; ?> 
</div>
<br class="clearBoth" /> 
<br />  
<?php
  $postme = 'postme';
  }else{
  $postme = '';
  }
?>
   
</div>
 </div>

<br class="clearBoth" />
<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) .'</a>'; ?></div>
<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SUBMIT_TESTIMONIALS, BUTTON_TESTIMONIALS_SUBMIT_ALT, ' id="' . $postme . '"'); ?></div>   
<br /><br />
</form>
</div>
</div>

<script >

        
var FormStuff = {

  init: function () {
  // kick it off once, in case the radio is already checked when the page loads
    this.applyConditionalRequired();
    this.bindUIActions();
    $("#postme").attr("disabled","disabled");
    $("#postme").css({opacity:0.3,cursor:'default'});
  },

  bindUIActions: function () {
  // when a radio or checkbox changes value, click or otherwise
    $("input[type='radio'], input[type='checkbox']").on("change", this.applyConditionalRequired);
  },

  applyConditionalRequired: function () {
     // find each input that may be hidden or not
    $(".require-if-active").each(function () {
      var el = $(this);
      // find the pairing radio or checkbox
      if ($(el.data("require-pair")).is(":checked")) {
         // if its checked, the field should be required
        el.prop("required", true);
        el.prop("disabled", false);
      } else {
        // otherwise it should not
        el.prop("required", false);
        el.prop("disabled", true);
      }
    });

  } };

FormStuff.init();    
                          
$("input[name='rating']").change(function(){
    var radVal = $("input[name='rating']:checked").val();
  switch (radVal) {
    case '1':
        $('#remove_me').html('Poor');
        break;
    case '2':
        $('#remove_me').html('Fair');
        break;
    case '3':
        $('#remove_me').html('Good');
        break
    case '4':
        $('#remove_me').html('Very Good');
        break
    case '5':
        $('#remove_me').html('Excellent');
        break
    default:
        $('#remove_me').html('');
} 
  });

$(".checky").click(function(){
                        if($(".checky").is(":checked")){
                         $("#postme").removeAttr("disabled"); 
                         $("#postme").css({opacity:1,cursor:'pointer'}); 
                         }
                        else{
                            $("#postme").attr("disabled","disabled");
                            $("#postme").css({opacity:0.3,cursor:'default'});
                            }
                    }); 

</script>
<script>
'use strict';

;( function( $, window, document, undefined )
{
	$( '.inputfile' ).each( function()
	{
		var $input	 = $( this ),
			$label	 = $input.next( 'label' ),
			labelVal = $label.html();

		$input.on( 'change', function( e )
		{
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();  

			if( fileName )
				$label.find( 'span' ).html( fileName );
			else
				$label.html( labelVal );
		});

		// Firefox bug fix
		$input
		.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
		.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
	});
})( jQuery, window, document );

</script>
</div>
<?php
  }
?>

</form>
</div>
