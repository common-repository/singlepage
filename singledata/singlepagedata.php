  <html lang="en">

  <head>
    <title> Singlepage Website </title>
  </head>

  <body>
      <div class="onepage">
          <h1 class="sec-title">Singlepage Website</h1>
          <div class="bg-grey">
              <strong class="red">Read below Information</strong>
              <p>From here you can add sections.</p>
          </div>
      </div>
      <!-- add form -->
      <?php
        if (isset($_POST['add_submit'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . "onepagedata";
            $title = sanitize_textarea_field(sanitize_text_field($_POST['title_new'])); 
            $desc_editor = sanitize_textarea_field(htmlentities($_POST['desc_editor_new']));
            if (isset($_POST['checkmenu'])) {
                $value = 1;
            } else {
                $value = 0;
            }
            if ($title != '') {
                $insert = $wpdb->insert($table_name, array('title' => $title, 'desc_editor' => $desc_editor, 'checkmenu' =>$value));
                $lastid = $wpdb->insert_id;
                $update = $wpdb->query($wpdb->prepare("UPDATE " . $table_name . "  SET   `ordering`='" . $lastid . "' WHERE id='" . $lastid . "'"));
            }
            if ($insert) {
                echo wp_kses_post('<div class="alert alert-success mt-5" id="flash" role="alert">Success! Section added successfully!</div>');
            } else {
                echo wp_kses_post('<div class="alert alert-danger mt-5" id="flash" role="alert">Sorry! Adding section is failed</div>');
            }
        }
        // update form
        if (isset($_POST['submit'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . "onepagedata";
            $res = $wpdb->get_results('SELECT * FROM ' . $table_name . ' ORDER BY ordering ASC ');
            for ($i = 0; $i < count($_POST['title']); $i++) {   
                $title= sanitize_text_field( $_POST['title'][$i]);
                $desc_editor = sanitize_textarea_field(htmlentities($_POST['desc_editor'. $i.'']));
                $ordering = sanitize_text_field($_POST['ordering'][$i]);
                $id = intval($_POST['id'][$i]);
                if ($_POST['checkmenu' . $id] == 1) {
                    $chekmenu_value = intval($_POST['checkmenu' . $id]);
                } else {
                    $chekmenu_value = 0;
                }
                $updated = $wpdb->query($wpdb->prepare("UPDATE ".$table_name."  SET  `title`= '%s',`checkmenu`= '%d', `ordering`= '%d' ,`desc_editor`= '%s' WHERE id= %d", $title,$chekmenu_value,$ordering,$desc_editor,$id));
            }
            
            if (false === $updated) {
                echo wp_kses_post('<div class="alert alert-danger mt-5" id="flash-msg" role="alert">Sorry! Updation is failed</div>');
            } else {
                echo wp_kses_post('<div class="alert alert-success mt-5" id="flash-msg" role="alert">Success! Section updated successfully!</div>');
            }
        }
      ?>
      <section class="ps-form-section" id="ps-form-outer-section">
          <div class="row">
              <div class="container">
                  <form method="post" action="#" id="file_form0" enctype="multipart/form-data">
                      <div id="file_div">
                          <div class="inner-section" id="div_1">
                              <label>Title</label><br>
                              <input type="text" id="title" name="title_new">
                              <input type="checkbox" name="checkmenu" value="1" checked>
                              <a  data-title = "Show in navigation bar."><i  class="fa fa-info-circle" aria-hidden="true"></a></i>
                          </div>
                          <div class="inner-section" id="div_2">
                              <?php wp_editor("", 'desc_editor_new', array());  ?>
                          </div>
                      </div>
                      <input type="submit" id="submit" class="btn btn-primary submitt" name="add_submit" value="Submit">
                  </form>
              </div>
          </div>
      </section>
   <section class="ps-form-section">
          <div class="row">
              <div class="container">
                  <div class="ps-edit-section">
                      <h2 class="sec-title">From here you can edit sections:</h2>
                       <form method="post" action="#" id="file_form" enctype="multipart/form-data">
                          <div id="file_div">
                              <?php
                                global $wpdb;
                                $table = $wpdb->prefix . 'onepagedata';
                                $result = $wpdb->get_results('SELECT * FROM ' . $table . ' ORDER BY ordering ASC');
                                if (count($result) > 0) {
                                    for ($i = 0; $i < count($result); $i++) {
                                        $checked = '';
                                        if ($result[$i]->checkmenu == 1) {
                                            $checked = 'checked';
                                        }
                                ?>
                                <div class="inner-section-wrapper" id="section_wrapper_<?php echo esc_html_e($result[$i]->id);?>">
                                      <div class="inner-section" id="div_1">
                                          <label>Title</label><br>
                                          <input type="text" name="title[]" value="<?php echo esc_html_e($result[$i]->title);?>" required>&nbsp;
                                          <input type="checkbox" name="checkmenu<?php echo esc_html_e($result[$i]->id); ?>" value="1" <?php echo esc_html($checked); ?>>
                                          <a data-title = "Show in navigation bar."><i  class="fa fa-info-circle" aria-hidden="true"></a></i>
                                          <input type="hidden" name="id[]" value="<?php echo esc_html_e($result[$i]->id); ?>">
                                      </div>
                                      <div class="inner-section" id="div_2">
                                          <?php
                                            $content =  stripslashes(html_entity_decode($result[$i]->desc_editor));
                                            wp_editor($content, 'desc_editor' . $i . '', array()); ?>
                                      </div>

                                      <div class="inner-section" id="div_1">
                                          <label>Ordering</label><br>
                                          <input type="number" name="ordering[]" value="<?php echo esc_html_e($result[$i]->ordering); ?>">
                                      </div>

                                      <button type="button" name="remove" value="remove" class="btn btn-danger delbtn remove" id="<?php echo esc_html_e($result[$i]->id); ?>">Remove</button>
                                    </div>
                                  <?php } ?>
                                  <br><input type="submit" id="save" onclick="return" class="btn btn-primary submitt" id="submit" name="submit" value="Update">
                              <?php  } ?>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </section>
      <!-- delete functionality -->
      <script>
           jQuery(document).ready(function() {
              let adminAjaxpath = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
              jQuery(".delbtn").click(function() {
                  var mid =  jQuery(this).attr('id');
                  var x = confirm("Are you sure to delete this section?");
                  if (x) {
                       jQuery.ajax({
                          type: "POST",
                          url: adminAjaxpath,
                          data: {
                              id: mid,
                              action: 'deletedata'
                          },
                          success: function(response) {
                               jQuery('#section_wrapper_'+mid).remove();
                              window.scrollTo({
                                  top: 0,
                                  left: 100,
                                  behavior: 'smooth'
                              });
                               jQuery('#ps-form-outer-section').prepend('<div class="alert alert-success mt-5" id="removedata" role="alert">Success! Section removed successfully!</div>');
                               jQuery("#removedata").delay(4000).fadeOut("fast");
                          },
                          error: function() {
                              alert("failure");
                          }
                      });   
                  }
              });
               jQuery("#file_form0").validate({
                  rules: {
                      'title_new': {
                          required: true,
                      }
                  },
                  messages: {
                      'title_new': "<br>Please enter a title.",
                  }
              });
               jQuery("#flash").delay(4000).fadeOut("fast");
               jQuery("#flash-msg").delay(4000).fadeOut("fast"); 
          });
      </script>
  </body>
</html>