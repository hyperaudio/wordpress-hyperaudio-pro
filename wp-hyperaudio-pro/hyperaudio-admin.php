<?php

function hook_ajax( ){
  wp_enqueue_script( 'script-checker', plugin_dir_url( __FILE__ ) . 'js/script-checker.js' );
  wp_localize_script( 'script-checker', 'account_script_checker', array(
          'ajaxurl' => admin_url( 'admin-ajax.php' ),
          'fail_message' => __('Connection to server failed.', 'script-checker'),
          'success_message' => __('Connection successful. ', 'script-checker')
      )
  );
}
add_action( 'enqueue_scripts', 'hook_ajax' );
add_action( 'admin_enqueue_scripts', 'hook_ajax' );

function check_ajax( ) {
  // entry here your function for ajax request


  $post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;
	$slug = 'example-post';
	$title = isset($_POST['titleText']) ? sanitize_text_field($_POST['titleText']) : '';
  $transcript = isset($_POST['transcript']) ? $_POST['transcript'] : '';
  $transcript = str_replace(' class=\"unread\"', '', $transcript);
  $media = isset($_POST['mediaUrl']) ? $_POST['mediaUrl'] : '';
  $player = isset($_POST['playerType']) ? $_POST['playerType'] : '';

  $optional_params = '';
  if (isset($_POST['width']) ) $optional_params .= ' width="'.$_POST['width'].'"';
  if (isset($_POST['height']) ) $optional_params .= ' height="'.$_POST['height'].'"';
  if (isset($_POST['mediaHeight']) ) $optional_params .= ' media-height="'.$_POST['mediaHeight'].'"';
  if (isset($_POST['transcriptHeight']) ) $optional_params .= ' transcript-height="'.$_POST['transcriptHeight'].'"';
  if (isset($_POST['fontFamily']) ) $optional_params .= ' font-family="'.$_POST['fontFamily'].'"';
  if (isset($_POST['showActive']) ) $optional_params .= ' show-active="'.$_POST['showActive'].'"';
 
	// If the page doesn't already exist, then create it
	if( null == get_page_by_title( $title ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	$slug,
				'post_title'		=>	$title,
				'post_status'		=>	'publish',
				'post_type'		=>	'post',
        'post_content'  => '<!-- wp:shortcode -->[hyperaudio src="'.$media.'" player="'.$player.'"'.$optional_params.']'.$transcript.'[/hyperaudio]<!-- /wp:shortcode -->'
			)
		);

    

	// Otherwise, we'll stop
	} else {

    // Arbitrarily use -2 to indicate that the page with the title already exists
    $post_id = -2;

	} // end if
}

add_action( 'wp_ajax_check_ajax', 'check_ajax' );

add_action('admin_menu', 'hyperaudio_add_option_page');

function hyperaudio_add_option_page()
{
	// hook in the options page function
	add_options_page('Official Hyperaudio Plugin', 'hyperaudio', 'manage_options',  __FILE__, 'hyperaudio_options_page');
}


function hyperaudio_load_admin_script($hook)
{
	if ($hook != 'settings_page_wp-hyperaudio-pro/hyperaudio-admin') {
		return;
	}

  /*wp_register_style('daisyui', 'https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css');
  wp_enqueue_style('daisyui');*/

  wp_register_style('hyperaudio-lite-player-css', plugins_url('/css/hyperaudio-lite-player.css', __FILE__), false, '1.0.0', false);
  wp_enqueue_style('hyperaudio-lite-player-css');

  /*wp_register_style('hyperaudio-lite-editor-css', plugins_url('/css/hyperaudio-lite-editor.css', __FILE__), false, '1.0.0', false);
  wp_enqueue_style('hyperaudio-lite-editor-css');*/

  wp_enqueue_script('hyperaudio-lite', plugins_url('/js/hyperaudio-lite.js', __FILE__), false, '1.0.0', false);
  wp_enqueue_script('hyperaudio-lite-extension', plugins_url('/js/hyperaudio-lite-extension.js', __FILE__), false, '1.0.0', false);

  wp_enqueue_script('deepgram', plugins_url('/js/hyperaudio-lite-editor-deepgram.js', __FILE__), false, '1.0.0', false);
  /*wp_enqueue_script('export', plugins_url('/js/hyperaudio-lite-editor-export.js', __FILE__), false, '1.0.0', false);*/
  /*wp_enqueue_script('storage', plugins_url('/js/hyperaudio-lite-editor-storage.js', __FILE__), false, '1.0.0', false);*/
  wp_enqueue_script('push-notification', plugins_url('/js/hyperaudio-push-notification.js', __FILE__), false, '1.0.0', false);

  wp_enqueue_script('caption', plugins_url('/js/caption.js', __FILE__), false, '1.0.0', false);

  /*wp_enqueue_script('tailwind', 'https://cdn.tailwindcss.com', false, '1.0.0', false);*/

}

add_action('admin_enqueue_scripts', 'hyperaudio_load_admin_script', 20);

function hyperaudio_options_page() 
{// Output the options page
  ?>

  <!--<link rel="stylesheet" href="css/hyperaudio-lite-player.css">-->
 
  <!--<script src="js/hyperaudio-lite-editor-deepgram.js"></script>-->
  <!--<script src="js/hyperaudio-lite-editor-captions.js"></script>-->

  <!-- DaisyUI / Tailwind -->
  <!--<link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.5/dist/full.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>-->



  <style>

html,
      body {
        height: 100%;
        display: flex;
        flex-direction: column;
      }
      body > * {
        flex-shrink: 0;
      }
      .div1 {
        background-color: #5c88ed;
      }
      .div2 {
        background-color: #90de90;
        flex-grow: 1;
      }

    

    /* Ensure the body and HTML fill the entire viewport height */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }

    body > * {
      flex-shrink: 0;
    }
     
    /* Create a flex container for the two panels */
    .container {
      display: flex;
      flex-grow: 1;
    }

    /* Style for the fixed-width side panel */
    .side-panel {
      flex: 0 0 400px; /* Fixed width of 400px */
      padding-right: 20px;
    }

    /* Style for the scrollable main panel */
    .main-panel {
      flex-grow: 1;
      /* Expand to fill the remaining space */
      background-color: #ffffff; /* Set your background color */
      overflow: auto; /* Enable vertical scrolling when content overflows */
    }

    .transcript-holder {
      display: flex; 
      justify-content: center; 
      background-color: #ffffff; 
      overflow-y:scroll;
      flex-grow: 1;
      border: 4px black;
    }

    .hyperaudio-transcript {
      background-color: #ffffff; 
      padding-top: 20px;
      padding-left: 20px;
      padding-right: 20px;
      overflow: auto;
      max-height: 100vh;
    }

    /*[contenteditable]:focus {
      outline: 4px solid blue;
    }*/

    .hyperaudio-transcript p {
      font-size: 120%;
    }

    .panel-button {
      padding:12px; 
      margin:10px; 
      font-size:150%; 
      width:360px;
    }
    
  </style>
   <!--<link rel="stylesheet" href="css/hyperaudio-lite-editor.css">-->
</head>
<body data-theme="light" style="height:100%">
  <div class="container">
    <div class="side-panel">
      <div>
        <div class="top-bar-item">
          <!-- example src -->
          <div>
            <video id="hyperplayer" class="hyperaudio-player" src="https://lab.hyperaud.io/video/HALiteIntro.mp4" type="video/mp4" controls poster="images/poster.png" style="width:400px;padding-top:20px">
              <track id="hyperplayer-vtt" label="preview" kind="subtitles" src="">
            </video>
          </div>
        </div>

        <div class="top-bar-item">
          <div id="pbr-holder" style="display: grid; grid-template-columns: 0.65fr 1.05fr 0.3fr; grid-gap: 10px;">
            <div>
              <label for="pbr">Playback Rate</label>
            </div>
            <div>
              <input id="pbr" type="range" min="0.4" max="2.4" value="1" step="0.2" class="range range-md range-primary" />
              <!--<div class="w-full flex justify-between text-xs px-2">-->
            </div>

            <div style="padding-right: 12px;">
              <div class="hidden-label-holder">
                <label style="width:0px;position:absolute;top:-100px" for="currentPbr">Playback Rate Value</label>
              </div>
              <input id="currentPbr" class="input input-bordered input-xs w-full max-w-xs" style="width:60px" type="number" id="pbr" name="pbr" value="1" step="0.2" min="0.4" max="2.4">
            </div>
          </div>
        </div>

        <div class="form-control">
          <span style="text-align: right; padding-right: 12px; padding-top: 12px;">
            <label for="show-speakers">Display speakers </label>
            <input type="checkbox" id="show-speakers" name="show-speakers" checked="checked" class="checkbox checkbox-primary"/>
          </span>
        </div>


        <dialog id="deepgram-dialog">
          <deepgram-service></deepgram-service>
        </dialog>

        <dialog id="captions-modal" style="position: absolute; top: 58px; left:calc(100% - 390px);" >
          <div style="position:fixed; top:32px; padding-top:10px; padding-bottom:20px;background-color:#fff; width: 360px; border-bottom: 1px solid #000;">
            <div>
              <button id="close-captions-button" style="float:right; text-decoration:none; border: 0; background-color: #fff; margin-bottom:16px; margin-bottom:16px">&#x2715;</button>
            </div>
            <button id="regenerate-btn" style="margin-left:20px; margin-top:4px">Re-generate from Transcript <svg id="regenerate-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-restart"><path d="M21 6H3"></path><path d="M7 12H3"></path><path d="M7 18H3"></path><path d="M12 18a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L11 14"></path><path d="M11 10v4h4"></path></svg></button>
          </div>
          
          
          <div id="caption-editor" style="margin-top:60px">
            <center>Preparing captions....</center>
          </div>
        </dialog>

        <dialog id="publish-details" style="width:400px">
          <button id="close-publish-button" style="float:right; text-decoration:none; border: 0; background-color: #fff" onclick="document.querySelector('#publish-details').close()">&#x2715;</button>
          <form>
            <h3>Post Details</h3>
            <label for="publish-post-title">Post Title</label><br>
            <input type="text" id="publish-post-title" value="" size="42">
            <hr>
            <label for="publish-media-url">Link to Audio/Video File</label><br>
            <input type="text" id="publish-media-url" value="" size="42">
            <hr>
            <label for="publish-player-type">Player Type</label><br>
            <select id="publish-player-type">
              <option value="native">Web Native (mp3, mp4 etc)</option>
              <option value="youtube">YouTube Embed</option>
              <option value="soundcloud">Soundcloud Embed</option>
              <option value="vimeo">Vimeo Embed</option>
              <option value="videojs">VideoJS Player</option>
            </select>
            <hr>
            <h3>Optional Details <span style="font-size:80%; float:right;"><a href id="publish-show-options" onclick="this.style.display = 'none'; document.querySelector('#publish-optional-details').style.display = 'block'; document.querySelector('#publish-hide-options').style.display = 'block'; return false;";>show</a><a href id="publish-hide-options" style="display:none" onclick="this.style.display = 'none'; document.querySelector('#publish-optional-details').style.display = 'none'; document.querySelector('#publish-show-options').style.display = 'block'; return false;";>hide</a></span></h3>
            <div id="publish-optional-details" style="display:none">
              <label for="publish-width">Transcript + Media Holder Width</label><br>
              <input type="text" id="publish-width" value="" size="8">
              <hr>
              <label for="publish-height">Media Holder Height</label><br>
              <input type="text" id="publish-height" value="" size="8">
              <hr>
              <label for="publish-media-height">Media Height</label><br>
              <input type="text" id="publish-media-height" value="" size="8">
              <hr>
              <label for="publish-transcript-height">Transcript Height</label><br>
              <input type="text" id="publish-transcript-height" value="" size="8">
              <hr>
              <label for="publish-font-family">Font Family</label><br>
              <input type="text" id="publish-font-family" value="" size="42">
              <hr>
              <label for="publish-show-active">Highlight Active Word</label>
              <input type="checkbox" id="publish-show-active" value="true">
            </div>
            <hr>
            
          </form>
          <button onclick="publishPost()" style="float:right; padding:10px; font-weight:bold">Publish</button> 
          
        </dialog>

        <button id="transcribe-media" class="panel-button">transcribe media</button>

        <button id="edit-transcript" class="panel-button">edit transcript</button>

        <button id="edit-captions" class="panel-button">edit captions</button>

        <button id="publish-transcript" class="panel-button" onclick="createPost()">publish transcript & media</button>

        <script>
        const transcribeButton = document.querySelector('#transcribe-media');
        const transcribeDialog = document.querySelector('#deepgram-dialog');
        const transcribeCloseDialog = document.querySelector('#close-transcribe-button');
        transcribeButton.addEventListener("click", () => {
          transcribeDialog.showModal();
        });

        transcribeCloseDialog.addEventListener("click", () => {
          transcribeDialog.close();
        });

        const editTranscriptButton = document.querySelector('#edit-transcript');
        //const transcriptEditor = document.querySelector('#hypertranscript');

        editTranscriptButton.addEventListener("click", () => {
          document.querySelector('#hypertranscript').focus();
        });

        const editCaptionsButton = document.querySelector('#edit-captions');
        const editCaptionsDialog = document.querySelector('#captions-modal');
        const captionsCloseDialog = document.querySelector('#close-captions-button');

        editCaptionsButton.addEventListener("click", () => {
          editCaptionsDialog.showModal();
        });

        captionsCloseDialog.addEventListener("click", () => {
          editCaptionsDialog.close();
        });


        </script>

        <!--<div class="tabs">
          <a class="tab tab-lifted tab-active"><strong>Local Storage</strong></a>
        </div>
        <div style="display: flex; overflow-y:scroll; ">
          <div style="height:360px">
            <ul id="file-picker" class="menu menu-compact bg-base-100 w-full" style="width:360px; height:360px">
            </ul>
          </div>
        </div>-->
        <div style="position: absolute; bottom: 16px; ">
          <a href="https://deepgram.com">Powered by
            <svg width="113" height="22" viewBox="0 0 226 44" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M25.8791 5.04737C29.0844 8.30338 30.8081 12.6072 30.7405 17.1645V17.1588C30.5997 26.3353 22.8878 33.7994 13.5536 33.7994H0.439392C0.19153 33.7994 0.0675988 33.5008 0.242229 33.3206L6.62468 26.9043C6.73171 26.7973 6.87254 26.7353 7.02464 26.7353H13.6775C19.0291 26.7353 23.5131 22.4541 23.6708 17.1983C23.7497 14.5168 22.7639 11.9819 20.8937 10.0553C19.0234 8.12875 16.5166 7.0697 13.8408 7.0697H7.0697V18.0094C7.0697 18.1052 6.99647 18.1784 6.90071 18.1784H0.168997C0.073232 18.1784 0 18.1052 0 18.0094V0.16899C0 0.0732251 0.073232 0 0.168997 0H13.8408C18.3981 0 22.6737 1.79136 25.8791 5.04737ZM45.1 9.06367C36.853 9.06367 33.09 15.3842 33.09 21.5582L33.0844 21.5638C33.0844 27.6928 37.3318 34.1541 45.5789 34.1541C51.4825 34.1541 56.0679 30.8643 57.1157 25.7268C57.1383 25.6197 57.0538 25.5183 56.9467 25.5183H50.722C50.6488 25.5183 50.5868 25.569 50.5643 25.6423C49.9953 27.6364 48.2265 28.656 45.5789 28.656C42.2046 28.656 40.0358 26.5323 39.5513 23.1073H56.8566C56.9467 23.1073 57.02 23.0454 57.0256 22.9552C57.0707 22.437 57.1101 21.7666 57.1101 20.9329C57.1101 15.3842 53.3471 9.06367 45.1 9.06367ZM45.1 13.9815C48.3335 13.9815 50.215 16.1503 50.4065 18.8542H39.6471C40.2273 15.4743 42.0637 13.9815 45.1 13.9815ZM60.0534 21.5582C60.0534 15.3842 63.8164 9.06367 72.0635 9.06367C80.3105 9.06367 84.0735 15.3842 84.0735 20.9329C84.0735 21.7666 84.0341 22.437 83.989 22.9552C83.9834 23.0454 83.9102 23.1073 83.82 23.1073H66.5148C66.9992 26.5323 69.168 28.656 72.5423 28.656C75.1899 28.656 76.9588 27.6364 77.5277 25.6423C77.5502 25.569 77.6122 25.5183 77.6854 25.5183H83.9102C84.0172 25.5183 84.1017 25.6197 84.0792 25.7268C83.0314 30.8643 78.4459 34.1541 72.5423 34.1541C64.2953 34.1541 60.0478 27.6928 60.0478 21.5638L60.0534 21.5582ZM77.37 18.8542C77.1785 16.1503 75.297 13.9815 72.0635 13.9815C69.0272 13.9815 67.1907 15.4743 66.6105 18.8542H77.37ZM94.3462 9.45236H87.9807V9.44109C87.8849 9.44109 87.8117 9.51433 87.8117 9.6101V43.4264C87.8117 43.5221 87.8849 43.5954 87.9807 43.5954H94.3462C94.442 43.5954 94.5152 43.5221 94.5152 43.4264V31.5853C95.8165 33.2246 98.1825 34.1428 100.931 34.1428C107.928 34.1428 112.654 29.1743 112.654 21.6032C112.654 14.0322 108.311 9.06367 101.461 9.06367C98.3233 9.06367 95.963 10.2241 94.5152 12.1056V9.62136C94.5152 9.52559 94.442 9.45236 94.3462 9.45236ZM105.996 21.6089C105.996 25.6592 103.635 28.3631 100.064 28.3631C96.4981 28.3631 94.1322 25.7042 94.1322 21.6089C94.1322 17.5135 96.4981 14.8096 100.064 14.8096C103.63 14.8096 105.996 17.5586 105.996 21.6089ZM122.445 38.5088C122.552 38.4018 122.693 38.3455 122.845 38.3455H129.684C131.757 38.3455 133.204 36.7062 133.204 34.4867V31.3039C131.948 33.0897 129.391 34.1487 126.546 34.1487C119.358 34.1487 115.065 29.1802 115.065 21.6091C115.065 14.0381 119.358 9.06956 126.45 9.06956C129.441 9.06956 131.802 10.1793 133.204 11.8693V9.62724C133.204 9.53148 133.278 9.45823 133.373 9.45823H139.739C139.835 9.45823 139.908 9.53148 139.908 9.62724V35.219C139.908 40.2382 136.291 43.6125 130.793 43.6125H118.045C117.792 43.6125 117.668 43.3083 117.848 43.1337L122.445 38.5201V38.5088ZM127.611 28.3577C131.278 28.3577 133.593 25.7045 133.593 21.6035C133.593 17.5025 131.278 14.8042 127.611 14.8042C123.943 14.8042 121.679 17.5081 121.679 21.6035C121.679 25.6989 124.045 28.3577 127.611 28.3577ZM151.557 33.5912V21.9417H151.563C151.563 17.79 153.056 15.041 156.385 15.041H160.221C160.317 15.041 160.39 14.9678 160.39 14.872V9.61622C160.39 9.52045 160.317 9.44721 160.221 9.44721H157.686C154.695 9.44721 152.859 10.4556 151.557 13.4975V9.61622C151.557 9.52045 151.484 9.44721 151.388 9.44721H145.023C144.927 9.44721 144.854 9.52045 144.854 9.61622V33.5912C144.854 33.687 144.927 33.7602 145.023 33.7602H151.388C151.484 33.7602 151.557 33.687 151.557 33.5912ZM162.532 26.8654C162.532 22.0884 166.103 19.193 171.455 19.193H175.505C176.711 19.193 177.336 18.4212 177.336 17.4072C177.336 15.3793 175.792 13.9822 173.043 13.9822C170.294 13.9822 168.638 15.7229 168.514 17.6832C168.514 17.7734 168.441 17.841 168.351 17.841H162.712C162.611 17.841 162.532 17.7565 162.537 17.6551C162.87 12.8668 166.807 9.05877 173.381 9.05877C179.555 9.05877 184.039 12.6302 184.039 18.1283V33.5859C184.039 33.6816 183.966 33.7548 183.87 33.7548H177.505C177.409 33.7548 177.336 33.6816 177.336 33.5859V30.3805C176.513 32.6451 173.815 34.1435 170.486 34.1435C165.805 34.1435 162.526 31.0058 162.526 26.8598L162.532 26.8654ZM172.227 29.2764C175.46 29.2764 177.342 26.8654 177.342 23.7784V23.4404H172.469C170.396 23.4404 168.993 24.6966 168.993 26.5781C168.993 28.1723 170.345 29.2821 172.227 29.2821V29.2764ZM194.671 9.45236H188.305L188.317 9.44673C188.221 9.44673 188.148 9.51997 188.148 9.61573V33.5908C188.148 33.6865 188.221 33.7598 188.317 33.7598H194.682C194.778 33.7598 194.851 33.6865 194.851 33.5908V19.964C194.851 16.8263 196.631 14.7532 199.431 14.7532C202.034 14.7532 203.724 16.8263 203.724 19.964V33.5908C203.724 33.6865 203.797 33.7598 203.893 33.7598H210.258C210.354 33.7598 210.427 33.6865 210.427 33.5908V19.964C210.427 16.8263 212.207 14.7532 215.007 14.7532C217.609 14.7532 219.299 16.8263 219.299 19.964V33.5908C219.299 33.6865 219.373 33.7598 219.468 33.7598H225.834C225.93 33.7598 226.003 33.6865 226.003 33.5908V17.8402C226.003 12.9731 222.578 9.06367 217.322 9.06367C213.418 9.06367 210.376 11.1367 209.171 13.5027C208.01 10.6072 205.549 9.06367 201.741 9.06367C198.457 9.06367 196.141 10.6579 194.84 12.8774V9.62136C194.84 9.52559 194.767 9.45236 194.671 9.45236Z" fill="black"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
    <div class="main-panel" style="background-color:#f0f0f1" >
      <!--<div class="navbar bg-base-100" style="background-color: #ffffff; opacity:0.95;">
        <div class="navbar-start">
          <button id="sidebar-toggle" class="btn btn-square btn-outline" aria-label="Toggle Sidebar">
            <svg id="sidebar-close-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sidebar-close"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><path d="M9 3v18"></path><path d="m16 15-3-3 3-3"></path></svg>
            <svg id="sidebar-open-icon" style="display: none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sidebar-open"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect><path d="M9 3v18"></path><path d="m14 9 3 3-3 3"></path></svg>
          </button>
          <div class="dropdown menu-compact">
            <label tabindex="0" class="btn m-1 btn-outline gap-2">
              <span id="file-dropdown-text">File</span>
              <svg id="file-dropdown-symbol" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
              <svg id="file-dropdown-symbol-mobile" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </label>
            <ul id="file-dropdown" tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
              <li class="menu-title">
                <span>
                  Download as
                </span>
              </li>
              <li><a id="download-vtt" href="" download="hyperaudio.vtt">WebVTT (Captions)</a></li>
              <li><a id="download-srt" href="" download="hyperaudio.srt">SRT (Captions)</a></li>
              <li><a id="download-html" href="" download="hypertranscript.html">HTML</a></li>
              <li><a id="download-hypertranscript" href="" download="hyperaudio.html">Interactive Transcript</a></li>
              <hr
              class="my-2 h-0 border border-t-0 border-solid border-neutral-700 opacity-25 dark:border-neutral-200" />
              <li class="menu-title">
                <span>
                  Export / Import
                </span>
              </li>
              <li><export-json></export-json></li>
              <li><import-json></import-json></li>
              <li><label for="file-import-deepgram-json-dialog">Import Deepgram JSON</label></li>
              <li><import-srt></import-srt></li>
            </ul>
          </div>
          <label for="info-modal" class="btn btn-ghost btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="16" y2="12"></line><line x1="12" x2="12.01" y1="8" y2="8"></line></svg>
          </label>
          <input type="checkbox" id="info-modal" class="modal-toggle" />
          <div class="modal">
            <div class="modal-box relative">
              <label for="info-modal" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
              <h3 class="text-lg font-bold">Summary</h3>
              <p id="summary" class="py-4"></p>
              <h3 class="text-lg font-bold">Topics</h3>
              <p id="topics" class="py-4"></p>
            </div>
          </div>
        </div>
        <div class="navbar-center">
          <div class="form-control">
            <input id="search-box" type="text" placeholder="Search" class="input input-bordered" />
          </div>
        </div>
        <div class="navbar-end" style="padding-right: 8px;">
          <label for="captions-modal" class="btn btn-outline gap-2" style="margin-right:4px">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-subtitles"><path d="M7 13h4"></path><path d="M15 13h2"></path><path d="M7 9h2"></path><path d="M13 9h4"></path><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10Z"></path></svg>
          </label>
          <input type="checkbox" id="captions-modal" class="modal-toggle" />
          <div class="modal">
            <div class="modal-box relative">
              <label for="captions-modal" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
              <button id="regenerate-btn" class="btn btn-disabled btn-outline btn-primary">Re-generate Captions from Transcript <svg id="regenerate-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-restart"><path d="M21 6H3"></path><path d="M7 12H3"></path><path d="M7 18H3"></path><path d="M12 18a5 5 0 0 0 9-3 4.5 4.5 0 0 0-4.5-4.5c-1.33 0-2.54.54-3.41 1.41L11 14"></path><path d="M11 10v4h4"></path></svg></button>
              <div id="caption-editor">
                <center>Preparing captions....</center>
              </div>
            </div>
          </div>
          <label for="transcribe-modal" class="btn btn-primary gap-2">
            <span id="transcribe-label">Transcribe</span>
            <span id="transcribe-label-mobile">New</span>
            <svg id="transcribe-logo" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticker"><path d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z"></path><path d="M15 3v6h6"></path><path d="M10 16s.8 1 2 1c1.3 0 2-1 2-1"></path><path d="M8 13h0"></path><path d="M16 13h0"></path></svg>
          </label>
          <input type="checkbox" id="transcribe-modal" class="modal-toggle" />
          <div class="modal">
            <div class="modal-box">
              <deepgram-service></deepgram-service>
            </div>
          </div>
        </div>
      </div>-->
      <div class="transcript-holder" style="width: 420px">

        <div id="hypertranscript" class="hyperaudio-transcript" contenteditable="true">
          <article>
            <section>
              <p>
                <span data-m="560" data-d="0" class="speaker">[Angela] </span>
                <span data-m="560" data-d="240">The </span>
                <span data-m="800" data-d="640">Hyperaudio </span>
                <span data-m="1440" data-d="240">Lite </span>
                <span data-m="1680" data-d="400">Editor </span>
                <span data-m="2080" data-d="320">makes </span>
                <span data-m="2400" data-d="400">audio </span>
                <span data-m="2800" data-d="240">and </span>
                <span data-m="3040" data-d="400">video </span>
                <span data-m="3440" data-d="240">more </span>
                <span data-m="3680" data-d="480">accessible </span>
                <span data-m="4160" data-d="240">through </span>
                <span data-m="4400" data-d="160">the </span>
                <span data-m="4560" data-d="480">creation </span>
                <span data-m="5040" data-d="240">of </span>
                <span data-m="5280" data-d="400">captions </span>
                <span data-m="5680" data-d="240">and </span>
                <span data-m="5920" data-d="500">interactive </span>
                <span data-m="6640" data-d="500">transcripts. </span>
              </p>
              <p>
                <span data-m="8240" data-d="240">The </span>
                <span data-m="8480" data-d="320">first </span>
                <span data-m="8800" data-d="320">step </span>
                <span data-m="9130" data-d="160">is </span>
                <span data-m="9290" data-d="80">to </span>
                <span data-m="9370" data-d="500">transcribe </span>
                <span data-m="10010" data-d="240">your </span>
                <span data-m="10240" data-d="500">content. </span>
              </p>
              <p>
                <span data-m="11530" data-d="240">We </span>
                <span data-m="11760" data-d="240">use </span>
                <span data-m="12000" data-d="160">the </span>
                <span data-m="12160" data-d="500">Deepgram </span>
                <span data-m="12720" data-d="320">API </span>
                <span data-m="13040" data-d="240">which </span>
                <span data-m="13290" data-d="500">produces </span>
                <span data-m="13920" data-d="240">high </span>
                <span data-m="14160" data-d="400">quality </span>
                <span data-m="14560" data-d="500">transcripts </span>
                <span data-m="15290" data-d="240">in </span>
                <span data-m="15530" data-d="320">double-</span>
                <span data-m="15840" data-d="400">quick </span>
                <span data-m="16240" data-d="460">time. </span>
                <span data-m="17180" data-d="500">Deepgram </span>
                <span data-m="17740" data-d="160">are </span>
                <span data-m="17900" data-d="480">offering </span>
                <span data-m="18380" data-d="500">45,000 </span>
                <span data-m="19420" data-d="320">minutes </span>
                <span data-m="19740" data-d="240">of </span>
                <span data-m="19980" data-d="500">transcription </span>
                <span data-m="20700" data-d="240">for </span>
                <span data-m="20940" data-d="500">free. </span>
                </p>
                <p>
                <span data-m="22060" data-d="160">You </span>
                <span data-m="22220" data-d="240">can </span>
                <span data-m="22460" data-d="240">use </span>
                <span data-m="22700" data-d="160">the </span>
                <span data-m="22860" data-d="500">Deepgram </span>
                <span data-m="23420" data-d="320">token </span>
                <span data-m="23740" data-d="240">with </span>
                <span data-m="23980" data-d="240">this </span>
                <span data-m="24220" data-d="500">application. </span>
              </p>
              <p>
                <span data-d="0" data-m="25770" class="speaker">[Angela] </span>
                <span data-m="25770" data-d="240"> Once </span>
                <span data-m="26020" data-d="500">transcribed, </span>
                <span data-m="26890" data-d="240">you </span>
                <span data-m="27140" data-d="160">can </span>
                <span data-m="27300" data-d="400">correct </span>
                <span data-m="27700" data-d="160">the </span>
                <span data-m="27860" data-d="500">transcript </span>
                <span data-m="28410" data-d="160">and </span>
                <span data-m="28570" data-d="240">add </span>
                <span data-m="28820" data-d="240">speaker </span>
                <span data-m="29050" data-d="320">names </span>
                <span data-m="29380" data-d="400">between </span>
                <span data-m="29770" data-d="480">square </span>
                <span data-m="30260" data-d="500">brackets. </span>
              </p>
              <p>
                <span data-m="31610" data-d="240">Just </span>
                <span data-m="31860" data-d="320">edit </span>
                <span data-m="32170" data-d="240">the </span>
                <span data-m="32410" data-d="480">transcript </span>
                <span data-m="32900" data-d="160">like </span>
                <span data-m="33050" data-d="160">you </span>
                <span data-m="33220" data-d="160">would </span>
                <span data-m="33380" data-d="160">any </span>
                <span data-m="33530" data-d="320">other </span>
                <span data-m="33850" data-d="240">text </span>
                <span data-m="34250" data-d="240">and </span>
                <span data-m="34490" data-d="240">we'll </span>
                <span data-m="34730" data-d="240">look </span>
                <span data-m="34970" data-d="320">after </span>
                <span data-m="35290" data-d="240">the </span>
                <span data-m="35530" data-d="500">timings. </span>
              </p>
              <p>
                <span data-m="36890" data-d="240">When </span>
                <span data-m="37130" data-d="320">happy </span>
                <span data-m="37450" data-d="240">with </span>
                <span data-m="37690" data-d="240">your </span>
                <span data-m="37930" data-d="480">transcript, </span>
                <span data-m="38410" data-d="160">you </span>
                <span data-m="38570" data-d="160">can </span>
                <span data-m="38730" data-d="400">convert </span>
                <span data-m="39130" data-d="160">it </span>
                <span data-m="39290" data-d="160">to </span>
                <span data-m="39450" data-d="480">captions </span>
                <span data-m="39930" data-d="160">and </span>
                <span data-m="40090" data-d="400">tweak </span>
                <span data-m="40490" data-d="240">those </span>
                <span data-m="40730" data-d="400">captions </span>
                <span data-m="41130" data-d="320">within </span>
                <span data-m="41450" data-d="160">the </span>
                <span data-m="41610" data-d="500">editor. </span>
              </p>
              <p>
                <span data-m="43090" data-d="500">Finally, </span>
                <span data-m="43730" data-d="240">you </span>
                <span data-m="43960" data-d="240">should </span>
                <span data-m="44200" data-d="160">be </span>
                <span data-m="44360" data-d="240">ready </span>
                <span data-m="44600" data-d="160">to </span>
                <span data-m="44770" data-d="400">export </span>
                <span data-m="45160" data-d="240">your </span>
                <span data-m="45410" data-d="480">transcript </span>
                <span data-m="45880" data-d="160">in </span>
                <span data-m="46050" data-d="160">a </span>
                <span data-m="46200" data-d="320">number </span>
                <span data-m="46520" data-d="80">of </span>
                <span data-m="46600" data-d="480">formats </span>
                <span data-m="47090" data-d="160">that </span>
                <span data-m="47240" data-d="80">you </span>
                <span data-m="47320" data-d="240">can </span>
                <span data-m="47560" data-d="500">associate </span>
                <span data-m="48120" data-d="160">with </span>
                <span data-m="48280" data-d="240">your </span>
                <span data-m="48520" data-d="320">audio</span>
                <span data-m="48840" data-d="400">visual </span>
                <span data-m="49240" data-d="500">media. </span>
              </p>
              <p>
                <span data-m="50540" data-d="400">Use </span>
                <span data-m="50940" data-d="400">formats </span>
                <span data-m="51340" data-d="160">like </span>
                <span data-m="51500" data-d="500">interactive </span>
                <span data-m="52140" data-d="500">transcripts </span>
                <span data-m="52780" data-d="160">with </span>
                <span data-m="52940" data-d="240">the </span>
                <span data-m="53180" data-d="560">Hyperaudio </span>
                <span data-m="53740" data-d="320">Lite </span>
                <span data-m="54060" data-d="400">Library </span>
                <span data-m="54460" data-d="240">or </span>
                <span data-m="54700" data-d="480">WordPress </span>
                <span data-m="55180" data-d="480">module </span>
                <span data-m="55660" data-d="160">to </span>
                <span data-m="55820" data-d="480">integrate </span>
                <span data-m="56300" data-d="320">into </span>
                <span data-m="56620" data-d="240">your </span>
                <span data-m="56860" data-d="500">website. </span>
              </p> 
            </section>
          </article>        
        </div>
    </div>
    </div>
    

    <div id="captionsource-alert" class="alert alert-info shadow-lg" style="visibility:hidden; z-index:2; position:absolute; top:50%; left:50%; width:480px; height:140px; margin: -240px 0 0 -240px;">
      <div>
        <div style="margin-top:-60px; width:400px">
          <h3 class="font-bold">Captions have been edited.</h3>
          <div class="text-xs">These captions may differ from the transcript. You can re-generate captions from the transcript in the Caption Editor.</div>
        </div>
      </div>
      <div class="modal-action" style="margin-top:80px; margin-left:-120px">
        <button id="captionsource-alert-cancel" class="btn btn-sm btn-secondary">don't tell me again</button>
        <button id="captionsource-alert-ok" class="btn btn-sm btn-primary">ok</button>
      </div>
    </div>
  </div>

  <!--<script src="js/hyperaudio-push-notification.js"></script>
  <script src="js/hyperaudio-lite.js"></script>
  <script src="js/hyperaudio-lite-extension.js"></script>
  <script src="js/caption.js"></script>-->
  <script>


  let updateCaptionsFromTranscript = true;

  let alertOkBtn = document.querySelector('#captionsource-alert-ok');

  alertOkBtn.addEventListener('click', function() {
    document.querySelector('#captionsource-alert').style.visibility = "hidden";
  });

  let alertCancelBtn = document.querySelector('#captionsource-alert-cancel');

  alertCancelBtn.addEventListener('click', function() {
    document.querySelector('#captionsource-alert').style.visibility = "hidden";
    localStorage.setItem("noCaptionAlert", "true");
  });

  let editableDiv = document.querySelector('#hypertranscript');

  editableDiv.addEventListener("paste", function(e) {
    e.preventDefault();
    var text = e.clipboardData.getData("text/plain");
    text.replaceAll("&nbsp;", " ");
    document.execCommand("insertHTML", false, text);
  });

  window.document.addEventListener('hyperaudioInit', hyperaudio, false);
  window.document.addEventListener('hyperaudioGenerateCaptionsFromTranscript', hyperaudioGenerateCaptionsFromTranscript, false);
  window.document.addEventListener('hyperaudioUpdateInteractiveTranscriptDownloadLink', updateInteractiveTranscriptDownloadLink, false);

  

  function hyperaudio() {
    const minimizedMode = false;
    const autoScroll = false;
    const doubleClick = true;
    const webMonetization = false;
    const playOnClick = false;

    const hyperaudioInstance = new HyperaudioLite("hypertranscript", "hyperplayer", minimizedMode, autoScroll, doubleClick, webMonetization, playOnClick);

    const sanitisationCheck = function () {

      let time = 0;
      resetTimer();
      window.onload = resetTimer;
      document.onkeyup = resetTimer;
      document.ontouchend = resetTimer;

      let rootnode = document.querySelector("#hypertranscript");
      let sourceMedia = document.querySelector("#hyperplayer").src;
      let track = document.querySelector('#hyperplayer-vtt');

      function sanitise() {
        let d = new Date();
        let starttime = d.getTime();

        // check that transcript has the focus

        // check for focus
        let isTranscriptFocused = false;
        let isCaptionEditorFocused = false;

        
        if (document.activeElement === rootnode) {
          isTranscriptFocused = true;
        }


        let walker = document.createTreeWalker(rootnode, NodeFilter.SHOW_TEXT, null, false);

        while (walker.nextNode()) {

          if (walker.currentNode.textContent.replaceAll('\n', '').trim().length > 0
              && walker.currentNode.parentElement.tagName !== "SPAN") {

            // if previousSibling is a span, add the textContent of currentNode to it
            if (walker.currentNode.previousSibling.tagName === "SPAN") {
              walker.currentNode.previousSibling.textContent += walker.currentNode.textContent;
            } else {
              // assume nextSibling is a span for now and add textContent of currentNode to that
              walker.currentNode.nextSibling.textContent += walker.currentNode.textContent;
            }

            // remove currentNode as we've merged its contents
            //walker.currentNode.parentNode.removeChild(walker.currentNode);
            walker.currentNode.textContent = "";
          }
        }

        // look for speakers and break them out into their own spans

        walker = document.createTreeWalker(rootnode, NodeFilter.SHOW_TEXT, null, false);

        while (walker.nextNode()) {
          if (walker.currentNode.textContent.replaceAll('\n', '').replaceAll('  ', ' ').trim().length > 0
              && walker.currentNode.parentElement.tagName === "SPAN" && walker.currentNode.textContent.includes('[') && walker.currentNode.textContent.includes(']')) {

            // if previousSibling is a span, add the textContent of currentNode to it
            if (walker.currentNode.textContent.trim().startsWith('[') === false || walker.currentNode.textContent.trim().endsWith(']') === false) {
             

              //look for text in square brackets
              const regex = / *\[[^\]]*]/g;
              const found = walker.currentNode.textContent.match(regex);

              let startsWithSpeaker = false;
              if (walker.currentNode.textContent.trim().startsWith('[') === true){
                startsWithSpeaker = true;
              }

              walker.currentNode.textContent = walker.currentNode.textContent.replace(regex, '');

              let span = document.createElement("span");
              span.textContent = found + ' ';

              if (span.textContent.includes('[') && span.textContent.includes(']')) {
                span.classList.add("speaker");
                closedSpeaker = false;
              }

              // add the classes of the current node
              span.classList.add(...walker.currentNode.parentNode.classList);
              //DOMTokenList.prototype.add.apply(span.classList, walker.currentNode.parentNode.classList);

              span.setAttribute("data-d","0");

              if (startsWithSpeaker === true) {
                span.setAttribute("data-m",walker.currentNode.parentNode.getAttribute("data-m"));
                walker.currentNode.parentNode.before(span);
              } else {
                let nextStart = walker.currentNode.parentNode.nextElementSibling.getAttribute("data-m");
                span.setAttribute("data-m",nextStart);
                let newSpan = document.createElement("span");
                newSpan.setAttribute("data-m",nextStart);

                newSpan.innerHTML = "&nbsp;";
                walker.currentNode.parentNode.after(span);
                span.after(newSpan);

                // set the cursor
                const range = document.createRange();
                const sel = window.getSelection();
                range.setStartBefore(newSpan.nextElementSibling);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
              }
            }
          }
        }

        let hypertranscript = rootnode.innerHTML.replace(/ class=".*?"/g, '');

        const downloadHtml = document.querySelector('#download-html');

        if (downloadHtml !== null) {
          downloadHtml.setAttribute('href', 'data:text/html,'+encodeURIComponent(hypertranscript));
        }

        //document.querySelector('#download-html').setAttribute('href', 'data:text/html,'+encodeURIComponent(hypertranscript));
        updateInteractiveTranscriptDownloadLink();

        if (isTranscriptFocused === true && updateCaptionsFromTranscript === true) {
          const words = document.querySelectorAll("[data-m]");
          hyperaudioInstance.wordArr = hyperaudioInstance.createWordArray(words);
          hyperaudioInstance.parentElements = hyperaudioInstance.transcript.getElementsByTagName(hyperaudioInstance.parentTag);

          if (hyperaudioInstance.currentTime !== undefined) {
            hyperaudioInstance.updateTranscriptVisualState(hyperaudioInstance.currentTime);
          }

          /*let hypertranscript = rootnode.innerHTML.replace(/ class=".*?"/g, '');
          document.querySelector('#download-html').setAttribute('href', 'data:text/html,'+encodeURIComponent(hypertranscript));*/

          generateCaptionsFromTranscript(hypertranscript, sourceMedia, track);
          const cap2 = caption();
          let subs = cap2.init("hypertranscript", "hyperplayer", '37' , '21'); // transcript Id, player Id, max chars, min chars for caption line
          populateCaptionEditor(subs.data);
        }

        if (isCaptionEditorFocused === true && updateCaptionsFromTranscript === false) {
          generateCaptionsFromCaptionEditor();
        }

        d = new Date();
        console.log("sanitising took "+(d.getTime() - starttime)+"ms");
      }

      function resetTimer() {
        console.log("reset sanitisation timer");
        clearTimeout(time);
        time = setTimeout(sanitise, 1000);
      }

      //longpress to set playhead on mobile

      function longPress(element, callback) {
        let pressTimer;
        element.addEventListener("touchstart", function(e) {
          pressTimer = setTimeout(function() {
            callback(e);
          }, 2000);
        });
        element.addEventListener("touchend", function(e) {
          clearTimeout(pressTimer);
        });
      }

      longPress(rootnode, function(e) {
        const startTime = e.target.getAttribute('data-m');
        if (startTime !== null) {
          e.target.classList.add("active");
          hyperaudioInstance.myPlayer.setTime(startTime/1000);
          hyperaudioInstance.setPlayHead(e);
          hyperaudioInstance.checkPlayHead();
        }
      });

    };

    sanitisationCheck();

    const videoElement = document.querySelector("#hyperplayer");
    let sidebarOpen = true;

    /*document.querySelector('#sidebar-toggle').addEventListener('click', (e) => {

      if (sidebarOpen === true) {
        document.querySelector('.holder').style.left = 0;
        document.querySelector('.main-panel').style.left = 0;
        document.querySelector('.transcript-holder').style.left = 0;
        document.querySelector('#sidebar-close-icon').style.display = "none";
        document.querySelector('#sidebar-open-icon').style.display = "block";
        sidebarOpen = false;
      } else {
        document.querySelector('.holder').style.left = "400px";
        document.querySelector('.main-panel').style.left = "400px";
        document.querySelector('.transcript-holder').style.left = "400px";
        document.querySelector('#sidebar-close-icon').style.display = "block";
        document.querySelector('#sidebar-open-icon').style.display = "none";
        sidebarOpen = true;
      }

      if(
        document.pictureInPictureEnabled &&
        !videoElement.disablePictureInPicture) {
        try {
          if (sidebarOpen === false) {
            videoElement.requestPictureInPicture();
          } else {
            document.exitPictureInPicture();
          }
        } catch(err) {
            console.error(err);
        }
      }
    });*/

    let showSpeakers = document.querySelector('#show-speakers');

    showSpeakers.addEventListener('change', function(e) {
      let speakers = document.querySelectorAll('.speaker');
      if (showSpeakers.checked === true) {
        speakers.forEach((speaker) => {
          //speaker.style.display = "inline";
          speaker.removeAttribute("style");
        });
      } else {
        speakers.forEach((speaker) => {
          speaker.style.display = "none";
        });
      }
    });
  }

  if (window.matchMedia("(max-width: 480px)").matches === true){
    let elem = document.querySelector("#hyperplayer");

    // Create a copy of it
    let clone = elem.cloneNode(true);
    
    clone.style.width = "100%";
    clone.style.paddingTop = "72px";

    // Inject it into the DOM

    document.querySelector('.transcript-holder').prepend(clone);
    elem.remove();
  }

  hyperaudio();


  function hyperaudioGenerateCaptionsFromTranscript() {
    let rootnode = document.querySelector("#hypertranscript");
    let sourceMedia = document.querySelector("#hyperplayer").src;
    let track = document.querySelector('#hyperplayer-vtt');
    let hypertranscript = rootnode.innerHTML.replace(/ class=".*?"/g, '');
    populateCaptionEditor(generateCaptionsFromTranscript(hypertranscript, sourceMedia, track));
  }

  function generateCaptionsFromTranscript(hypertranscript, sourceMedia, track) {
    const cap1 = caption();
    let subs = cap1.init("hypertranscript", "hyperplayer", '37' , '21'); // transcript Id, player Id, max chars, min chars for caption line

    //document.querySelector('#download-vtt').setAttribute('href', 'data:text/vtt,'+encodeURIComponent(subs.vtt));
    //document.querySelector('#download-srt').setAttribute('href', 'data:text/srt,'+encodeURIComponent(subs.srt));

    track.kind = "captions";
    //track.label = "English";
    //track.srclang = "en";
    track.src = "data:text/vtt,"+encodeURIComponent(subs.vtt);

    /*document
      .querySelector("#download-hypertranscript")
      .setAttribute(
        "href",
        "data:text/html," +
          encodeURIComponent(
            hyperaudioTemplate
              .replace("{hypertranscript}", hypertranscript)
              .replace("{sourcemedia}", sourceMedia)
              .replace("{sourcevtt}", track.src)
          )
      );*/

    // check to see if it's an mp3, in which case we don't display captions
    if (document.querySelector('#hyperplayer').src.split('.').pop() === "mp3") {
      document.querySelector('#hyperplayer').textTracks[0].mode = "hidden";
    } else {
      document.querySelector('#hyperplayer').textTracks[0].mode = "showing";
    }

    console.log("subs.data = "+subs.data);
    console.log(subs);

    return subs.data;
  }

  function updateInteractiveTranscriptDownloadLink() {

    /*let isAudio = document.querySelector('#hyperplayer').src.split('.').pop() === "mp3";
    document
      .querySelector("#download-hypertranscript")
      .setAttribute(
        "href",
        "data:text/html," +
          encodeURIComponent(
            hyperaudioTemplate
              .replace("{hypertranscript}", document.querySelector("#hypertranscript").innerHTML.replace(/ class=".*?"/g, ''))
              // check to see if it's an mp3, in which case we don't display captions
              .replace("{sourcemedia}", document.querySelector("#hyperplayer").src) 
              .replace("{sourcevtt}", isAudio ? "" : document.querySelector("#hyperplayer-vtt").src)
          )
      );*/
  }

  function hasParent(element, parent) {
    let currentElement = element.parentNode;
    
    while (currentElement !== null) {
      if (currentElement === parent) {
        return true;
      }
      
      currentElement = currentElement.parentNode;
    }
    
    return false;
  }
  </script>

  <import-deepgram-json></import-deepgram-json>
  
  <!-- comment out if localstorage not required -->

  <!--<div class="hidden-label-holder">
  <label for="file-save-dialog">Open Save to Local Storage Dialog</label>
  </div>
  <input type="checkbox" id="file-save-dialog" class="modal-toggle" />
  <div class="modal">
  <div class="modal-box">
    <div class="flex flex-col gap-4 w-full">
      <label id="close-modal" for="file-save-dialog" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
      <h3 class="font-bold text-lg">Save to Local Storage</h3>
      <input type="text" id="save-localstorage-filename" name="save-localstorage-filename" placeholder="File name" class="input input-bordered w-full max-w-xs" />
    </div>
    <div class="modal-action">
      <label for="file-save-dialog" class="btn btn-ghost">Cancel</label>
      <label id="file-save-localstorage" for="file-save-dialog" class="btn btn-primary">Confirm</label>
    </div>
  </div>
  </div>

  <div class="hidden-label-holder">
  <label for="file-load-dialog">Open Load from Local Storage Dialog</label>
  </div>
  <input type="checkbox" id="file-load-dialog" class="modal-toggle" />
  <div class="modal">
  <div class="modal-box">
    <div class="flex flex-col gap-4 w-full">
      <label id="close-modal" for="file-load-dialog" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
      <h3 class="font-bold text-lg">Load from Local Storage</h3>
      <select id="load-localstorage-filename" class="select select-bordered w-full max-w-xs">
      </select>
    </div>
    <div class="modal-action">
      <label for="file-load-dialog" class="btn btn-ghost">Cancel</label>
      <label id="file-load-localstorage" for="file-load-dialog" class="btn btn-primary">Confirm</label>
    </div>
  </div>
  </div>-->

  <div id="row-template-holder" style="display:none">
    <div id="caption-template" class="caption">
      <div class="caption-row">
        <button class="play btn btn-primary btn-sm" onclick="playClip(this)">play clip <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg></button>
      </div>
      <div class="caption-row">
        <button class="play-start btn btn-outline btn-xs" onclick="seekTo(this)" style="margin-right:4px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-skip-forward"><polygon points="5 4 15 12 5 20 5 4"></polygon><line x1="19" x2="19" y1="5" y2="19"></line></svg></button><input class="start" value="" oninput="captionChange()">(In)
      </div>
      <div class="caption-row">
        <button class="play-end btn btn-outline btn-xs" onclick="seekTo(this)" style="margin-right:4px"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-skip-forward"><polygon points="5 4 15 12 5 20 5 4"></polygon><line x1="19" x2="19" y1="5" y2="19"></line></svg></button><input class="end" value="" oninput="captionChange()">(Out)
      </div>
      <div class="caption-row">
        <input class="line1" value="" oninput="captionChange()">
      </div>
      <div class="caption-row">
        <input class="line2" value="" oninput="captionChange()">
      </div>
      <div class="caption-row btn-group btn-group-horizontal">
        <button class="btn btn-outline btn-xs" onclick="addCaption(this)">insert <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-to-line"><line x1="12" x2="12" y1="17" y2="3"></line><polyline points="6 11 12 17 18 11"></polyline><path d="M19 21H5"></path></svg></button>
        <button class="btn btn-outline btn-xs" onclick="mergeCaption(this)">merge <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-down-from-line"><line x1="12" x2="12" y1="21" y2="7"></line><polyline points="6 15 12 21 18 15"></polyline><path d="M19 3H5"></path></svg></button>
        <button class="btn btn-outline btn-xs" onclick="deleteCaption(this)">delete <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg></button>
      </div>
      <div class="divider"></div> 
    </div>
  </div>


  <!--<script src="./js/hyperaudio-lite-editor-storage.js"></script>-->

  <!--<script>

  document.querySelector('#file-dropdown').insertAdjacentHTML("beforeend", '<hr class="my-2 h-0 border border-t-0 border-solid border-neutral-700 opacity-25 dark:border-neutral-200" /><li class="menu-title"><span>Local Storage</span></li><li><label for="file-save-dialog">Save to Local Storage</label></li><li><label for="file-load-dialog">Load from Local Storage</label></li>');

  document.querySelector('#save-localstorage-filename').value = getLocalStorageSaveFilename(document.querySelector("#hyperplayer").src);

  loadLocalStorageOptions();

  document
    .querySelector("#file-save-localstorage")
    .addEventListener("click", function () {
      let filename = document.querySelector('#save-localstorage-filename').value;
      saveHyperTranscriptToLocalStorage(filename);
      loadLocalStorageOptions();
    });

  document
    .querySelector("#file-load-localstorage")
    .addEventListener("click", function () {
      let filenameIndex = document.querySelector('#load-localstorage-filename').value;
      loadHyperTranscriptFromLocalStorage(filenameIndex);
    });

  setFileSelectListeners();

  </script>-->

  <!--<script src="./js/hyperaudio-lite-editor-export.js" type="module"> </script>-->
  <!-- end of localstorage additions -->

  <!-- caption editor additions -->
  <style>
    input.line1 {
      width: 36ch;
    }

    input.line2 {
      width: 36ch;
    }

    div.caption {
      padding: 16px;
    }

    .caption-row {
      padding: 4px;
    }

  </style>
  <script>
    window.document.addEventListener('hyperaudioPopulateCaptionEditor', populateCaptionEditor, false);

    function populateCaptionEditor(data) {

      console.log("in populateCaptionEditor....................");
      console.log("data = "+data);

      let captionModal = document.querySelector('#caption-editor');

      if (captionModal !== null) {
        captionModal.innerHTML = "";

        if (data !== undefined) {
          data.forEach(cap => {
            let line1 = cap.text.split('\n')[0];
            let line2 = cap.text.split('\n')[1];

            if (line2 === undefined || typeof line2 === "undefined") {
              line2 = "";
            }

            let captionTempl = document.querySelector('#caption-template').cloneNode(true);
            captionTempl.querySelector('.start').value = cap.start;
            captionTempl.querySelector('.end').value = cap.stop;
            captionTempl.querySelector('.line1').value = line1;
            captionTempl.querySelector('.line2').value = line2;
            captionModal.insertAdjacentElement('beforeEnd', captionTempl);
          });
        
          document.querySelector('#regenerate-btn').classList.add("btn-disabled");
        }
      }
    }

    function populateCaptionEditorFromVtt(vtt) {
      const data = [];
      vtt = vtt.replace("WEBVTT\n\n","");
      vtt = vtt.replaceAll("\n\n","\n");

      let lines = vtt.split('\n');
      let start, stop, text;

      lines.forEach((line, index) => {
        let lineIsNumber = !isNaN(line.trim().replace(' --> ','').replaceAll('.','').replaceAll(':',''));
        if (lineIsNumber === true && line.indexOf(' --> ') === 12 && line.length === 29) {
          if (index > 0) {
            data.push({start, stop, text});
          }
          start = line.split(' --> ')[0];
          stop = line.split(' --> ')[1].trim();
          text = "";
        } else {
          text += line.trim() + "\n";
        }
      });

      console.log("data = "+data);

      populateCaptionEditor(data);

      console.log("no caption alert");
      console.log(localStorage.getItem("noCaptionAlert"));

      if (updateCaptionsFromTranscript === false && localStorage.getItem("noCaptionAlert") !== "true") {
        console.log("displaying...");
        document.querySelector('#captionsource-alert').style.visibility = 'visible';
      }
    }

    const cap2 = caption();
    let subs = cap2.init("hypertranscript", "hyperplayer", '37' , '21'); // transcript Id, player Id, max chars, min chars for caption line
    populateCaptionEditor(subs.data);
    
    const countSeconds = (str) => {
      const [hh = '0', mm = '0', ss = '0'] = (str || '0:0:0').split(':');
      const hour = parseInt(hh, 10) || 0;
      const minute = parseInt(mm, 10) || 0;
      const second = parseFloat(ss);
      return (hour*3600) + (minute*60) + (second);
    };

    function playClip(elem) {

      // this is a little brittle
      // better to check each parent until you find the elements required

      let startTime = countSeconds(elem.parentElement.parentElement.querySelector('.start').value);
      let endTime = countSeconds(elem.parentElement.parentElement.querySelector('.end').value);
      let duration = endTime - startTime;
      console.log(endTime);
      console.log(startTime);
      console.log(duration);


      document.querySelector('video').currentTime = startTime;
      document.querySelector('video').play();

      /*document.querySelector('video').addEventListener("play", (event) => {
        setTimeout(() => {
          document.querySelector('video').pause();
        }, duration*1000);
        this.removeEventListener('click', arguments.callee, false);
      });*/

      let timer = setInterval(function(){
        if(document.querySelector('video').currentTime > endTime){
          document.querySelector('video').pause();
          clearInterval(timer);
        }
      },100);
      
    }

    function seekTo(elem) {
      let seekTime = countSeconds(elem.nextElementSibling.value);
      if (elem.className == "play-end") {
        seekTime -= 0.1;
      }
      document.querySelector('video').currentTime = seekTime;
    }

    function addCaption(elem) {
      let captionTempl = template.querySelector('#caption-template').cloneNode(true);
      captionTempl.getElementsByClassName('line1')[0].value = "";
      captionTempl.getElementsByClassName('line2')[0].value = "";
      captionTempl.getElementsByClassName('start')[0].value = "00:00:00.000";
      captionTempl.getElementsByClassName('end')[0].value = "00:00:00.000";
      elem.parentElement.parentNode.insertAdjacentElement('afterend', captionTempl);
      makeCaptionEditorActive();
    }

    function deleteCaption(elem) {
      let thisCaption = elem.parentNode.parentNode;
      thisCaption.parentNode.removeChild(thisCaption);
      makeCaptionEditorActive();
    }

    function mergeCaption(elem) {
      let thisCaption = elem.parentNode.parentNode;
      let belowCaption = thisCaption.nextElementSibling;

      thisCaption.querySelector('.end').value = belowCaption.querySelector('.end').value;
      thisCaption.querySelector('.line2').value += 
        ` ${belowCaption.querySelector('.line1').value.toString()} ${belowCaption.querySelector('.line2').value.toString()}`;

      belowCaption.parentNode.removeChild(belowCaption);
      makeCaptionEditorActive();
    }

    function captionChange() {
      makeCaptionEditorActive();
    }

    function makeCaptionEditorActive() {
      updateCaptionsFromTranscript = false;
      const regenerateButton = document.querySelector('#regenerate-btn');
      if (regenerateButton !== null) {
        regenerateButton.classList.remove("btn-disabled");
      }
      generateCaptionsFromCaptionEditor();
    }

    function generateCaptionsFromCaptionEditor() {

      let vttCaptions = "WEBVTT\n\n";
      let srtCaptions = "";

      document.querySelectorAll('.caption').forEach((caption, index) => {
        if (caption.querySelector('.start').value.length > 0){
          vttCaptions += caption.querySelector('.start').value + " --> " + caption.querySelector('.end').value + "\n";
          vttCaptions += caption.querySelector('.line1').value + "\n";
          if (caption.querySelector('.line2').value.length > 0) {
            vttCaptions += caption.querySelector('.line2').value + "\n";
          }
          vttCaptions += "\n";

          srtCaptions += (index + 1) + "\n";
          srtCaptions += convertTimecodeToSrt(caption.querySelector('.start').value) + " --> " + convertTimecodeToSrt(caption.querySelector('.end').value) + "\n";
          srtCaptions += caption.querySelector('.line1').value + "\n";
          if (caption.querySelector('.line2').value.length > 0) {
            srtCaptions += caption.querySelector('.line2').value + "\n";
          }
          srtCaptions += "\n";
        }
      });

      let track = document.querySelector('#hyperplayer-vtt');
      track.src = "data:text/vtt,"+encodeURIComponent(vttCaptions);

      /*document
        .querySelector("#download-hypertranscript")
        .setAttribute(
          "href",
          "data:text/html," +
            encodeURIComponent(
              hyperaudioTemplate
                .replace("{hypertranscript}", document.querySelector('#hypertranscript').innerHTML.replace(/ class=".*?"/g, ''))
                .replace("{sourcemedia}", document.querySelector("#hyperplayer").src)
                .replace("{sourcevtt}", track.src)
            )
        );*/

      //document.querySelector('#download-vtt').setAttribute('href', "data:text/vtt,"+encodeURIComponent(vttCaptions));
      //document.querySelector('#download-srt').setAttribute('href', "data:text/srt,"+encodeURIComponent(srtCaptions));
    }

    function convertTimecodeToSrt(timecode) {
      //the same as VTT format but milliseconds separated by a comma
      return timecode.substring(0,8) + "," + timecode.substring(9,12);
    }

    const regenerateButton = document.querySelector('#regenerate-btn');
    if (regenerateButton !== null) {
      regenerateButton.addEventListener('click', hyperaudioGenerateCaptionsFromTranscript);
    }

    //document.querySelector('#regenerate-btn').addEventListener('click', hyperaudioGenerateCaptionsFromTranscript);


    function createPost() {
      document.querySelector('#publish-media-url').value = document.querySelector('#hyperplayer').src;
      document.querySelector('#publish-details').showModal();
    }

    function publishPost() {
      let transcriptHtml = document.querySelector('#hypertranscript').innerHTML;

      jQuery.ajax({
          type: 'POST',
          url: account_script_checker.ajaxurl,
          data: {
              action: 'check_ajax',
              fail_message: account_script_checker.fail_message,
              success_message: account_script_checker.success_message,
              titleText: document.querySelector('#publish-post-title').value,
              mediaUrl: document.querySelector('#publish-media-url').value,
              playerType: document.querySelector('#publish-player-type').value,
              width: document.querySelector('#publish-width').value,
              height: document.querySelector('#publish-height').value,
              mediaHeight: document.querySelector('#publish-media-height').value,
              transcriptHeight: document.querySelector('#publish-transcript-height').value,
              fontFamily: document.querySelector('#publish-font-family').value,
              showActive: document.querySelector('#publish-show-active').value,
              transcript: transcriptHtml
          },
          beforeSend: function ( ) {
              jQuery( 'body' ).html( account_script_checker.loading_message );
              document.querySelector("#publish-details").close();
          },
          success: function ( data, textStatus, XMLHttpRequest ) {
              if( data === 'failed_to_create_post' ) {
                  console.log("failed");
              } else {
                  console.log("ok");
              }
          },
          error: function ( XMLHttpRequest, textStatus, errorThrown ) {
              alert( errorThrown );
          }
      });
    }

    

  </script>
  <!-- end of caption editor additions -->

<?php } ?>