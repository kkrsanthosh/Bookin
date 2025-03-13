<!------------------------------------- GALLERY ------------------------------------->

<!-- Modal -->
<div class="modal fade" id="modal-gallery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Image Gallery</h5>
        <a href="javascript:void(0);" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </a>
      </div>
      <div class="modal-body text-center">

	<div class="container mt-5">
		<div class="row">
			<div class="col-12">
				<h1 class="text-center ">Find Post Image</h1>
				<label for="search" class="pb-3 lead">Search and replace post image with one click.</label>
				<div class="input-group mb-3">
					<input type="text" id="search" class="form-control" placeholder="Search for images..."
						onchange="page = 1" onkeydown="pressEnter(this)" autocomplete="off">
					<div class="input-group-append">
						<button class="btn btn-primary " type="button" id="search-btn" onclick="searchPhotos(this)">
							<svg class="text-center mx-4 mb-1" width="1em" height="1em" viewBox="0 0 16 16"
								class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
								<path fill-rule="evenodd"
									d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
							</svg>
						</button>
					</div>
				</div>
				<!-- </form> -->
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<p class="pl-2 pt-0 text-muted" id="num-results"></p>
				<div class="text-xs-center mx-auto" id="pagination"></div>
				<div id="unsplash">	
					<div class="flex-wrap align-items-start mb-5" id="img-results-unsplash">
					</div>
				</div>
				<div id="pixabay">
					<div class="flex-wrap align-items-start mb-5" id="img-results-pixabay">
					</div>
				</div>
				<div id="pexels">
					<div class="flex-wrap align-items-start mb-5" id="img-results-pexels">
					</div>
				</div>
				<div class="text-xs-center mx-auto" style="margin-bottom: 100px !important;">
					<ul class="pagination text-center mx-auto">
						<li class="page-item"><a class="page-link" onclick="prevPage()">Previous</a></li>
						<li class="page-item"><a class="page-link" onclick="nextPage()">Next</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

      </div>
      <div class="modal-footer">
        <a href="javascript:void(0);" class="btn btn-secondary" data-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery --
<script src="../src/plugins/jquery/jquery.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://suite.social/app/manage/src/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Image Search -->
<script src="https://suite.social/app/manage/src/js/FileSaver.min.js"></script>
<script src="https://suite.social/app/manage/src/js/photo-search.js"></script>
<!-- Image Uploads -->
<script  src="https://suite.social/app/manage/src/js/lazy-images.js"></script>
<!-- Dropzonejs -->
<script src="https://suite.social/app/manage/src/plugins/dropzone/min/dropzone.min.js"></script>
<!-- Upload Preview -->
<script  src="https://suite.social/app/manage/src/js/upload-preview.js"></script>
<!-- Grid -->
<script src="https://suite.social/app/manage/src/js/grid.js"></script>
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- AdminLTE App -->
<script src="https://suite.social/app/manage/src/dist/js/adminlte.min.js"></script>
<!-- Page specific script -->

<script>
function successIcon() {
  Swal.fire({
      html: '<h3 class="text-success">Post Image Updated</h3>',
      icon: 'success'
  })
  }
</script>

<script>
  $(document).ready(function(){
    $('#toggleButton').click(function(){
      let rawURL = document.getElementById('url').value;
      let cleanURL = rawURL.replace('http://', '');
      cleanURL = cleanURL.replace('https://', '');
      if (cleanURL.indexOf('/') > -1) cleanURL = (cleanURL.split('/'))[0];
      //window.location.href = 'https://suite.social/proposal/?b=' + cleanURL;
	  window.open('https://suite.social/proposal/?b=' + cleanURL, '_blank');
    });
  });
</script>

<script>
function showImage() {
    document.getElementsByClassName("imageHide")[0].style.display = 'block';
    document.getElementById('image').classList.remove('show');
}

function hideImage() {
    document.getElementsByClassName("imageHide")[0].style.display = 'none';
}

$(document).on('change', '.upload__inputfile', function () {
  let files = this.files;
  if (files.length == 0) {
    hideImage();
  } else {
    let imgFile = files[0];
    let fileURL = URL.createObjectURL(imgFile);
    document.getElementById('imageGallery').onload = function () {
      URL.revokeObjectURL(imgFile);
      showImage();
    }
    document.getElementById('imageGallery').src = fileURL;
  }
});
</script>


<script>
// Random image (remember it changes on any button click)
//$("button").on("click", function(){
//$('#imageRandom').load('#imageRandom');
//});
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
</script>

<script>
jQuery(document).ready(function($) {
    $.get('quotes.txt', function(data) {
        var quotes = data.split("\n");
        var idx = Math.floor(quotes.length * Math.random());
        $('.quotes').html(quotes[idx]);
    });
});
</script>

<script>
$(document).ready(function() {
	$('.urlinput').on('input paste change keyup keydown keypress', function(event) {
    var newText = event.target.value;
    $('.urlbox').text(newText);
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const urlInput = document.getElementById('url');
  const urlAlert = document.getElementById('urlAlert');
  const submitButton = document.getElementById('submitButton');
  
  // Array of forbidden URLs
  const forbiddenUrls = [
	'amazon.com',
	'blogger.com',
	'facebook.com',
	'instagram.com',
	'linkedin.com',
	'pinterest.com',
	'reddit.com', 
	'tumblr.com',
	'twitter.com',
	'x.com',
	'xing.com',
	// Add more forbidden URLs here as needed
  ];

  function checkURL() {
	const userInput = urlInput.value.trim().toLowerCase();
	
	// Check if the input contains any forbidden URL
	const isForbidden = forbiddenUrls.some(url => userInput.includes(url));
	
	// Show the alert if a forbidden URL is found
	urlAlert.classList.toggle('d-none', !isForbidden);
	
	// Disable the submit button if a forbidden URL is found
	submitButton.disabled = isForbidden;
  }

  // Event listeners for keyup, paste, and change events
  urlInput.addEventListener('keyup', checkURL);
  urlInput.addEventListener('paste', checkURL);
  urlInput.addEventListener('change', checkURL);	  
  urlInput.addEventListener('input', checkURL);
  urlInput.addEventListener('keydown', checkURL);  
});
</script>

<script>
$(document).ready(function() {
  // Function to update .offerbox text
  function updateOfferBoxText(text) {
    $('.offerbox').text(text);
  }

  // Event listener for offerinput
  $('.offerinput').on('input paste change keyup keydown keypress', function(event) {
    var newText = event.target.value;
    updateOfferBoxText(newText);
  });

  // Event listener for Use button
  $(document).on('click', '.use-btn', function () {
    var promotion = $(this).data('promotion');
    $('.offerinput').val(promotion); // Update input field with promotion text
    updateOfferBoxText(promotion); // Update .offerbox text
    $('#dataModal').modal('hide'); // Hide modal after updating text
  });
});
</script>

<script>
var count = 1; // MAX IS 10 OR WONT WORK
var query = 'frequency=common';
if (count > 0) {
	query += '&count=' + count
};
$(document).ready(function() {
	var female = '&type=female';
	var male = '&type=male';


	var femaleNames = [];
	var maleNames = [];


	for (var i = 0; i < count; i++) {
		$(".females").append("<span class='female'></span>");
		$(".males").append("<span class='male'></span>");
	}

	$.ajax({
		url: "https://namey.muffinlabs.com/name.json?" + query + female,
		method: "GET",
		success: function(response) {
			femaleNames = response;

			if (maleNames.length == count) {
				$(".female").each(function(i) {
					$(this).text(femaleNames[i]);
				});
				$(".male").each(function(i) {
					$(this).text(maleNames[i]);
				});
			}
		}
	});

	$.ajax({
		url: "https://namey.muffinlabs.com/name.json?" + query + male,
		method: "GET",
		success: function(response) {
			maleNames = response;

			if (femaleNames.length == count) {
				$(".female").each(function(i) {
					$(this).text(femaleNames[i]);
				});
				$(".male").each(function(i) {
					$(this).text(maleNames[i]);
				});
			}
		}
	});
});
</script>

