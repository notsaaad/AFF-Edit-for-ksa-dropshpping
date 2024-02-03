<?php
/*
Plugin Name: Hatem Aff Edit
Description: Affilates can Change Price
Version: 1.6
Author: Hatem Amir
Author URI: https://github.com/notsaaad
Email: amirhatem549@gmail.com
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ){
  die;
}



//===================================== Start Gloable Methods =============================

/******************************* Start Add_actions ******************************/

add_action( 'wp_footer', 'Hatem_is_affi' );        //Argun: wp_user_id               ,Return bool

add_action( 'wp_footer', 'Hatem_get_affi' );        //Argun:wp_user_id               ,Return Aff_id


add_action( "wp_footer","Hatem_FI_own_ref_value" ); // For Own ref value
// add_action( "wp_footer","Hatem_Download_product_images" ); // Download Images
/******************************* End Add_actions ******************************/

function Hatem_is_affi($user_ID){
  global $wpdb;

  $table_name =  'wp_uap_affiliates'; //$wpdb->prefix .
  $results 	= $wpdb->get_results("SELECT * FROM $table_name" );
  $check = false;
  $values = json_decode(json_encode($results), true);
  $check = false;
  foreach($values as $value){
    if ($value['uid'] == $user_ID){
      $check = true;

    }
  }

  return $check;
}



/* >>>>>>>>>>>>>>*/

function Hatem_get_affi($user_ID){
  global $wpdb;

  $table_name =  'wp_uap_affiliates'; //$wpdb->prefix .
  $results 	= $wpdb->get_results("SELECT * FROM $table_name" );

  $values = json_decode(json_encode($results), true);
  $check = false;
  foreach($values as $value){
    if ($value['uid'] == $user_ID){
      return $value['id'];
    }
  }

}









/* >>>>>>>>>>>>>>*/



/* >>>>>>>>>>>>>>*/


function Hatem_FI_own_ref_value(){
  $userID =  get_current_user_id();

  global $wpdb;
  $isAff = Hatem_is_affi($userID);

  if ($isAff){
    $AffID =  Hatem_get_affi($userID);

    // echo 'userID=> '. $userID .'<br>';
    // echo 'AffID => '. $AffID .'<br>';


    global $wpdb;

    $results = $wpdb ->get_results("SELECT * FROM `wp_uap_affiliate_referral_users_relations` WHERE (affiliate_id = $AffID AND referral_wp_uid = $userID)");

    // print_r($results[0]->affiliate_id);
    // echo '<br>';

    if ($results[0]->affiliate_id !== $AffID){
      $table_name = "wp_uap_affiliate_referral_users_relations";
      $wpdb ->insert($table_name,array(
        "affiliate_id"=> $AffID,
        "referral_wp_uid"=>$userID,
      ));
      // echo"WORK";
    }


  }


}







//===================================== End Gloable Methods =============================






// add_action( 'wp_footer' 'Hatem_check_aff_value_database' )

// function Hatem_check_aff_value_database($ID){
//   global $wpdb;
//   $table_name;

//   $table_name ='wp_AffProducts';
// 	$results 	= $wpdb->get_results("SELECT * FROM $table_name where(user_ID=$ID)" );
//   foreach ($results as $result) {
//     $date1 = $result->created_date;
//     $date2 =  date("Y/m/d");


//     $diff = abs(strtotime($date2) - strtotime($date1));

//     $years = floor($diff / (365*60*60*24));
//     $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//     $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

//       if ($dayes == 1){

//         $wpdb-> delete( $table_name, array( 'ID' => $result->ID ) );

//       }
//   }

//   return 0;


// }



// add_action( 'wp_footer' , 'Hatem_change_product_price' );


// function Hatem_change_product_price(){

// }

add_action( 'wp_footer' , 'Hatem_add_change_price_button' );


function Hatem_add_change_price_button(){
  if (is_user_logged_in( )  ){
    $userID =  get_current_user_id();
    if (Hatem_is_affi($userID)){
      ?>
      <style>
        .price del{
          display:none !important;
        }
      </style>
      <?php
    }

    if(Hatem_is_affi($userID)  && is_product()){
      if( is_cart() || is_checkout()){
        return 0;
      }
      global $product;

      $user_ID = get_current_user_id();
      $DownloadImagesrc =  array();
      $regular_price = $product->get_regular_price();
      $attachment_ids = $product->get_gallery_attachment_ids();

      foreach( $attachment_ids as $attachment_id ) 
      {
        array_push($DownloadImagesrc,wp_get_attachment_url( $attachment_id ) );
      }
      $AllImagesSrcs = "";

      foreach ($DownloadImagesrc as $e) {
        $AllImagesSrcs .= $e . ',';
      }
      

        ?>

    <script>


  
        (function($){



          var link =  location.href;
          console.log(link);

          let SpiltLink = link.split('/');



          if (SpiltLink[3]== 'en'){
            $('.single-product .ast-woocommerce-container  .ast-stock-avail').after(`<input id="Hatem-input-price" style="display:block;" type="text" placeholder="Enter your new price"> <button id="Hatem-btn-product-price" class="Hatem-Update-product" style="cursor: pointer;">Update</button><button id="Hatem-btn-delete-product-price" class="Hatem-Delete-product" style="cursor: pointer;">Delete Old Value</button><br> `);
            $('.single-product .ast-woocommerce-container  .ast-stock-avail').before(`<div class="Hatem-aff-recommend-price"><p><?php echo $regular_price . ' SAR ' ?>is your recommended Price</p></div><button id="Hatem-btn-donwload-photo" class="AFF-download-image">Download Image</button>`);

          }else{

            $('.single-product .ast-woocommerce-container  .ast-stock-avail').after(`<div class="Hatem-AFF-DIV"><input id="Hatem-input-price" style="display:block;" type="text" placeholder="اضف سعر البيع الجديد"> <button id="Hatem-btn-product-price" class="Hatem-Update-product" style="cursor: pointer;">اضافة</button><button id="Hatem-btn-delete-product-price" class="Hatem-Delete-product" style="cursor: pointer;">حذف القيم القديمة</button><br></div> `);
            $('.single-product .ast-woocommerce-container  .ast-stock-avail').before(`<div class="Hatem-aff-recommend-price"><p><?php echo $regular_price . ' ر.س ' ?>هو السعر المقترح للبيع</p></div><button id="Hatem-btn-donwload-photo" class="AFF-download-image"> تحميل الصورة</button>`);

          }

          let AllImagesSrcs = `<?php echo $AllImagesSrcs; ?>`;

          let ArrayImagesSrcs = AllImagesSrcs.split(',');



          $('#Hatem-btn-donwload-photo').on("click",function(){

            for(let i=0; i<ArrayImagesSrcs.length-1; i++){
              var a = $("<a>")
              .attr("href", ArrayImagesSrcs[i])
              .attr("download", "img.png")
              .appendTo("body");

              a[0].click();

              a.remove();
            }

          });





          $('#Hatem-btn-product-price').on( "click", function() {
            var New_price = $('#Hatem-input-price').val();

            var ProductID =  <?php echo $product->get_id(); ?>

            var oldPrice = <?php echo $product->get_regular_price(); ?>

            var user_ID = <?php echo $user_ID;  ?>;

            jQuery.ajax({
            url: '<?php  echo admin_url( 'admin-ajax.php' ); ?>',
            data: {
              'action' : 'aff_update_database',
              'php_product_ID' : ProductID,
              'php_new_price': New_price,
              'php_old_price': oldPrice,
              'php_user_ID': user_ID,

            },
            success: function(){
              alert("done");
            },
            error: function(){
              alert("SomeThing Went Wrong , Contact with your IT Team");
            }
          });
        });


        /*Delete Ajax Button*/

        $('#Hatem-btn-delete-product-price').on( "click", function() {


            var ProductID =  <?php echo $product->get_id(); ?>



            var user_ID = <?php echo $user_ID;  ?>;

            jQuery.ajax({
            url: '<?php  echo admin_url( 'admin-ajax.php' ); ?>',
            data: {
              'action' : 'aff_Delete_database',
              'php_product_ID' : ProductID,
              'php_user_ID': user_ID,

            },
            success: function(){
              alert("done");
            },
            error: function(){
              alert("SomeThing Went Wrong , Contact with your IT Team");
            }
          });
        });


        })(jQuery);
      </script>
      <?php
    }

  }
}

/*========================== Start Delete Ajax ==========================*/
add_action( 'wp_ajax_aff_Delete_database','aff_Delete_database' );
add_action('wp_ajax_nopriv_aff_Delete_database', 'aff_Delete_database');


function aff_Delete_database(){



  if(isset($_REQUEST)){
    $product_ID   =    $_REQUEST['php_product_ID'];
    $userID       =    $_REQUEST['php_user_ID'];




    global $wpdb;
    $table_name ="wp_AffProducts";
    $wpdb->delete($table_name,array(
      "user_ID"=>$userID,
      "product_ID"=>$product_ID,

    ));
  }
}

/*========================== End Delete Ajax ==========================*/

add_action( 'wp_ajax_aff_update_database','aff_update_database' );
add_action('wp_ajax_nopriv_aff_update_database', 'aff_update_database');


function aff_update_database(){



  if(isset($_REQUEST)){
    $product_ID   =    $_REQUEST['php_product_ID'];
    $old_price    =    $_REQUEST['php_old_price'];
    $new_price    =    $_REQUEST['php_new_price'];
    $userID       =    $_REQUEST['php_user_ID'];




  global $wpdb;
  $table_name ="wp_AffProducts";
  $wpdb->insert($table_name,array(
    "user_ID"=>$userID,
    "product_ID"=>$product_ID,
    "new_price"=>$new_price,
    ));
  }
}



add_action( 'woocommerce_before_calculate_totals' , 'Hatem_aff_change_price_before_checkout' , 9999 , 1 );

function Hatem_aff_change_price_before_checkout($cart){
          // This is necessary for WC 3.0+
          if ( is_admin() && ! defined( 'DOING_AJAX' ) )
          return;

      // Avoiding hook repetition (when using price calculations for example)
      if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
          return;

      // Loop through cart items
      foreach ( $cart->get_cart() as $key => $item ) {
        // echo '<pre>';
        // print_r($item);
        // echo '</pre>';

        $product_ID   =  $item['product_id'];
        $productID    = (int) $product_ID;
        $product      = $item['data'];
        // print_r($product->get_meta('uap-woo-wsr-value') );
        // echo '=====================================<br>';
        $old_price = $product->get_regular_price();
        // echo $old_price;
        $userID =     $user_ID = get_current_user_id();

        $product->update_meta_data('uap-woo-wsr-value', 0);
        $product-> save();
        global $wpdb;

        $table_name ='wp_AffProducts';

      	$results 	= $wpdb->get_results("SELECT * FROM $table_name where (user_ID = $userID and product_ID=$productID) " );
        // print_r($results);
        if (isset($results[0]->ID)){
          $price =  $results[0]->new_price;
          $intPrice = (int) $price;
          // echo "work";
          // echo"old Price => " . $old_price . '<br>';
          // echo"new Price => " . $intPrice . '<br>';
          // $product ->set_price($intPrice);
          // $product ->set_regular_price($intPrice);
          $ref_value = $intPrice - $old_price;
          // echo"ref Value=> " . $ref_value . '<br>';
          $product->update_meta_data('uap-woo-wsr-value', $ref_value, false ,'');
          // $product->get_meta('uap-woo-wsr-value');

          $cart->add_fee($product_ID , $ref_value);
          $product->save();

        }


      }
}

  add_action( "wp_footer", "Hatem_edit_aff_en_to_ar" );

  function Hatem_edit_aff_en_to_ar(){
  ?>

    <script>
      (function($){

        /*start stroe*/

        /*end stroe*/

        /*====================== start Page become an affilate page-id-1935 ===========================*/
     

        $('.page-id-1935 .entry-title').text("كن مسوق");
        $('.page-id-1935 .entry-content p').text("انضم إلى برنامج الشركاء التابعين لدينا واكسب عمولة على كل عملية بيع ناجحة تقوم بإحالتها أو تسجيل عميل جديد. تقدم بطلبك اليوم وابدأ في الربح!");
        $('.page-id-1935 .uap-form-line-register:nth-child(1) input').attr("placeholder", "* اسم المستخدم" );
        $('.page-id-1935 .uap-form-line-register:nth-child(2) input').attr("placeholder", "* البريد الاكتروني" );
        $('.page-id-1935 .uap-form-line-register:nth-child(3) input').attr("placeholder", "* الاسم الاول" );
        $('.page-id-1935 .uap-form-line-register:nth-child(4) input').attr("placeholder", "* الاسم الاخير" );
        $('.page-id-1935 .uap-form-line-register:nth-child(5) input').attr("placeholder", "* كلمة السر" );
        $('.page-id-1935 .uap-form-line-register:nth-child(6) input').attr("placeholder", "* تأكيد كلمة السر" );
        $('.page-id-1935  .uap-form-number label').text("رقم الهاتف");
        $('.page-id-1935  .uap-form-uap_country label').text("الدولة");
        $('.page-id-1935  .uap-tos-wrap a').text("الموافقة علي الشروط و الاحكام");
        $('.page-id-1935  .uap-form-upload_image label').text("الصورة");
        $('.page-id-1935  #uap-avatar-button').text("رفع");
        $('.page-id-1935  .uap-submit-form input').attr("value", "تسجيل");
        $('.page-id-1935  .uap-register-notice').text("من فضلك ادخل هذا الحقل");





        /*====================== End Page become an affilate page-id-1935 ===========================*/



        /*====================== start Page Sign in aff page-id-1936 ===========================*/

        $('.page-id-1936 .entry-title').text('تسجيل دخول مسوق');
        $('.page-id-1936 #uap_login_username').attr("placeholder", "اسم المستخدم");
        $('.page-id-1936 #uap_login_password').attr("placeholder", "كلمة السر");
        $('.page-id-1936 .uap-form-links-pass a').text("نسيت كلمة السر");
        $('.page-id-1936 .uap-form-submit input').attr("value", "تسجيل");
        // $('.page-id-1936 .uap-form-links-reg').html('');

        /*====================== end Page Sign in aff page-id-1936 ===========================*/



        /* ============== Start  AFF menu  ================= */

        $('.page-id-1937 .uap-ap-menu li:nth-child(1)  a').text('لوحة التحكم');
        $('.page-id-1937 .uap-ap-menu li:nth-child(2)  a').text('حسابي ');
        $('.page-id-1937 .uap-ap-menu li:nth-child(3)  a').text('التسويق ');
        $('.page-id-1937 .uap-ap-menu li:nth-child(4)  a').text('العمولات');
        $('.page-id-1937 .uap-ap-menu li:nth-child(5)  a').text('الدفع');
        $('.page-id-1937 .uap-ap-menu li:nth-child(6)  a').text('التقارير');
        $('.page-id-1937 .uap-ap-menu li:nth-child(7)  a').text('مساعدة');
        $('.page-id-1937 .uap-ap-menu li:nth-child(8)  a').text('تسجيل الخروج');






        /*=========== start submenu ======*/

        $('.uap-ap-menu li:nth-child(3)').remove();
        $('.uap-ap-menu li:nth-child(6)').remove();
        $('.uap-ap-menu li:nth-child(7)').remove();

        $('.page-id-1937 #uap_public_ap_profile li:nth-child(1) a').text('تعديل حسابك');
        $('.page-id-1937 #uap_public_ap_profile li:nth-child(2) a').text('تغير كلمة المرور');
        $('.page-id-1937 #uap_public_ap_profile li:nth-child(3) a').text('تفاصيل الدفع');




        $('.page-id-1937 #uap_public_ap_marketing li:nth-child(1) a').text('روابط المسوقين');
        $('.page-id-1937 #uap_public_ap_marketing li:nth-child(2) a').text('الحملات');
        $('.page-id-1937 #uap_public_ap_marketing li:nth-child(3) a').text('المبدعين');



        $('.page-id-1937 #uap_public_ap_reports li:nth-child(1) a').text('التقارير');
        $('.page-id-1937 #uap_public_ap_reports li:nth-child(2) a').text('سجل المرور');
        $('.page-id-1937 #uap_public_ap_reports li:nth-child(3) a').text('تقارير الحمالات');
        $('.page-id-1937 #uap_public_ap_reports li:nth-child(4) a').text('تاريخ العمولات');
        $('.page-id-1937 #uap_public_ap_reports li:nth-child(5) a').text('تسويق متعدد المهمات');



        /*=========== end submenu ======*/


                /* ============== End  AFF menu  ================= */


        $('.page-id-1937 .uap-top-earnings .uap-stats-label').text('الربح');
        $('.page-id-1937 .uap-top-referrals .uap-stats-label').text('الاحالات');
        $('.page-id-1937 .uap-warning-box').remove();

        $('.page-id-1937 .uap-ap-wrap h3:nth-child(1)').text('لوحة التحكم');

        $('.page-id-1937 .uap-account-overview-tab1 .uap-detail').text('إجمالي الإحالات');
        $('.page-id-1937 .uap-account-overview-tab1 .uap-subnote').text('المكافآت والعمولات المستلمة حتى الآن');

        $('.page-id-1937 .uap-account-overview-tab2 .uap-detail').text('الإحالات المدفوعة');
        $('.page-id-1937 .uap-account-overview-tab2 .uap-subnote').text('سحب عدد من الإحالات حتى الآن');

        $('.page-id-1937 .uap-account-overview-tab3 .uap-detail').text('الإحالات غير المدفوعة');
        $('.page-id-1937 .uap-account-overview-tab3 .uap-subnote').text('والتي لم يتم سحبها بعد');

        $('.page-id-1937 .uap-account-overview-tab4 .uap-detail').text('إجمالي معاملات الدفع');
        $('.page-id-1937 .uap-account-overview-tab4 .uap-subnote').text('');

        $('.page-id-1937 .uap-account-overview-tab5 .uap-detail').text('رصيد حسابك الحالي');
        $('.page-id-1937 .uap-account-overview-tab5 .uap-subnote').text('');

        $('.page-id-1937 .uap-account-overview-tab6 .uap-detail').text('الأرباح المسحوبة حتى الآن (إجمالي المعاملات)');
        $('.page-id-1937 .uap-account-overview-tab6 .uap-subnote').text('');

        $('.page-id-1937 .uap-account-help-link').remove();

        $('.page-id-1937 .uap-account-summary-graph-title').text('نظرة عامة علي الارباج');
        // $('.page-id-1937 .uap-account-summary-graph-content').text('نظرة عامة علي الارباج');
        $('.page-id-1937 .uap-account-summary-summary-content-first-col .uap-account-summary-summary-data-title').text('الربح');



        if (location.href =="https://dropshipping-ksa.com/my-account-2/?uap_aff_subtab=edit_account"){
          $('.uap-user-page-content h3:nth-child(1)').text('تعديل حسابك');

          $('#uap-avatar-button').text('رفع');

          $('.uap-submit-form input').attr('value','حفظ التغيرات');
        }


        if (location.href == "https://dropshipping-ksa.com/my-account-2/?uap_aff_subtab=change_pass"){




          $('.uap-change-password-field-wrap:nth-child(1) .uap-change-password-label').text('كلمة المرور الحالية');
          $('.uap-change-password-field-wrap:nth-child(1) .uap-change-password-field-details').text('نحن بحاجة إلى كلمة المرور الحالية الخاصة بك لتأكيد التغييرات');


          $('.uap-change-password-field-wrap:nth-child(2) .uap-change-password-label').text('كلمة السر الجديدة');
          $('.uap-change-password-field-wrap:nth-child(3) .uap-change-password-label').text('تكرار كلمة السر الجديدة');
          $('.uap-change-password-field-wrap:nth-child(4) .uap-change-password-label input').attr('value','حفظ التغيرات');

        }


        /* new */

        if (location.href == "https://dropshipping-ksa.com/my-account-2/?uap_aff_subtab=payments_settings"){
          $('.uap-profile-box-wrapper  .uap-profile-box-title').text('تفاصيل وسيلة الدفع');
          $('.uap-account-notes').text('قبل أن نتمكن من الدفع لك، يجب أن نحصل على معلومات الدفع الخاصة بك. تأكد من تقديمها بشكل صحيح.');
          $('.uap-account-title-label').text('تفاصيل البنك الخاصة بك');
          $('.uap-account-notes').remove();
          $('.uap-change-password-field-wrap input').attr('value', 'حفظ التغيرات');
        }




        if (location.href == "https://dropshipping-ksa.com/my-account-2/?uap_aff_subtab=reports"){
          $('.uapcol-md-3:nth-child(1) .uap-detail').text('إجمالي الإحالات');
          $('.uapcol-md-3:nth-child(1) .uap-subnote').text('المكافآت والعمولات المستلمة حتى الآن');



          $('.uapcol-md-3:nth-child(2) .uap-detail').text('رصيدك الحالي');

          $('.uap-profile-box-title span').text('إنجازاتك');
        }


        if (location.href == "https://dropshipping-ksa.com/my-account-2/?uap_aff_subtab=help"){
          $('.uap-ap-wrap h4').text('')
        }








        /*====================== start my aff dashbord page-id-1937 ===========================*/







        /*====================== end my aff dashbord page-id-1937 ===========================*/









      })(jQuery);


          //Will Back
      (function($){
        /*====================== start my aff dashbord page-id-1937 ===========================*/

        $('.page-id-1937 .uap-col-xs-4.uap-account-summary-month-data').remove();





        /*====================== end my aff dashbord page-id-1937 ===========================*/
      })(jQuery);

      /*Function to change ar to en*/

  (function ($){

      $('.page-id-3348 #wpforms-1444-field_4-container label').text('Email *');
      $('.page-id-3348  #wpforms-1444-field_0').attr('placeholder' , 'Name');
      $('.page-id-3348  #wpforms-1444-field_2').attr('placeholder' , 'Subject');
      $('.page-id-3348  #wpforms-submit-1444').text('Submit');

      $('.woocommerce-MyAccount-navigation-link--uap').remove();
      

  })(jQuery);


    


    </script>



  <?php


  }





/**
 * Duplicate product to another language (Not variable)
 *
 * @param int $post_id The ID of the product being duplicated.
 */
add_action('pmxi_saved_post', 'dokkaner_duplicate_wpml_products_simple', 10, 1);
function dokkaner_duplicate_wpml_products_simple($post_id) {
    if ($post_id) {
        $PostType = get_post_type($post_id);
        if ($PostType == 'product') {
            $product = wc_get_product($post_id);
            if (!$product->is_type('variable')) {
                do_action('wpml_make_post_duplicates', $post_id);
                dokkaner_translate_the_buplicated_product_after_import($post_id);
            }
        }
    }
}

/**
 * Duplicate product to another language (variable products only)
 *
 * @param int $post_id The ID of the product being duplicated.
 */
add_action('wp_all_import_variable_product_imported', 'dokkaner_duplicate_wpml_products_variable', 10, 1);
function dokkaner_duplicate_wpml_products_variable($post_id) {
    if ($post_id) {
        do_action('wpml_make_post_duplicates', $post_id);
        dokkaner_translate_the_buplicated_product_after_import($post_id);
    }
}

/**
 * On update post, ensure that the translated text is added.
 *
 * @param int $post_id The ID of the product being updated.
 */
add_action('post_updated', 'dokkaner_on_update_post', 10, 999);
function dokkaner_on_update_post($post_id) {
    if ($post_id) {
        $PostType = get_post_type($post_id);
        if ($PostType == 'product') {
            // $product = wc_get_product($post_id);
            // if (!$product->is_type('variable')){
            dokkaner_translate_the_buplicated_product_after_import($post_id);
            // }
        }
    }
}

/**
 * Translates the duplicated product after import.
 *
 * @param int $original_product_id The original product ID.
 */
function dokkaner_translate_the_buplicated_product_after_import($original_product_id) {
    $my_duplications = apply_filters('wpml_post_duplicates', $original_product_id);
    $new_id = $my_duplications['ar'];

    if (get_field('dokkaner_title_ar', $original_product_id)) {
        $dokkaner_title_ar = get_field('dokkaner_title_ar', $original_product_id);
        $dokkaner_long_description_ar = get_field('dokkaner_long_description_ar', $original_product_id);
        $dokkaner_short_description_ar = get_field('dokkaner_short_description_ar', $original_product_id);

        $post_update = array(
            'ID' => $new_id,
            'post_title' => $dokkaner_title_ar,
            'post_content' => $dokkaner_long_description_ar
        );

        wp_update_post($post_update);
        wp_update_post(array('ID' => $new_id, 'post_excerpt' => $dokkaner_short_description_ar));
    }

    $PostType = get_post_type($original_product_id);
    if ($PostType == 'product') {
        delete_post_meta($original_product_id, '_icl_lang_duplicate_of');
        if ($new_id) {
            delete_post_meta($new_id, '_icl_lang_duplicate_of');
        }
    }
}
