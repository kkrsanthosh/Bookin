<?php require 'config.php';?>

<?php
	
	/// SOCIAL
	$network = 'Facebook'; 
	$color = 'primary'; 
	$link = '#0064e0'; 
	$linkHover = '#16a6fc';

	$_SESSION['network'] = $network;
	$_SESSION['color'] = $color;
	$_SESSION['link'] = $link;
	$_SESSION['linkHover'] = $linkHover;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	
<?php require '../../meta.php'; ?>
<?php require '../style.php'; ?>

<script>
  const domain = '<?=domain?>'; // DO NOT REMOVE OR WILL GET ERROR
</script>
  
</head>
<body class="hold-transition register-page">
<div class="register-box">
			  
  <div class="card card-outline card-<?php echo $color; ?>">
    <div id="<?php echo $network; ?>" class="card-header text-center text-white pr-2 pl-2 pt-3 pb-0">
      <a href="https://suite.social" target="_blank"><img style="border: 1px solid #eee;" width="60px" class="rounded" src="https://suite.social/app/images/<?php echo $network; ?>.jpg" alt="Icon"></a>
     <p><h5><?php echo $network; ?> Post Preview</h5></p>
	</div>
    <div class="card-body p-0">

<div class="container">
	
    <div class="content">
        <div class="form-container text-center">
            <form>
			  <p>
			  <div class="input-group" style="display: none;">
				<input type="text" name="offer" class="offerinput is-valid form-control form-control-lg mr-0" id="offer" placeholder="Enter your promotion">
				<div class="input-group-append">
				  <button class="btn btn-success" type="button" id="openModalBtn" data-toggle="modal" data-target="#dataModal">IDEAS?</button>
				</div>
			  </div>			  			  
			  </p>
	
		        <h5 class="pt-3">What is your website?</h5>
				<p><input id="url" name="url" class="form-control form-control-lg urlinput" value="<?php echo isset($_GET['b']) ? $_GET['b'] : '';?>" required="required" placeholder="Website URL" /></p>

				<p><button type="submit" class="btn btn-block bg-gradient-<?php echo $color; ?> btn-lg">Create Demo</button></p>
            </form>
        </div>
        <div class="preview-container">
            <div class="progress hidden">
              <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
            <div class="preview-data hidden">
			
			<p class="text-center"><b>Here is your post preview</b></p>
					
			<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  <span>This is just a demo of how posts will appear on <?php echo $network; ?>. The actual content will be tailored to your specific needs.</span>
				  </div>
			
<div class="row">
            <div class="col-12" id="accordion">
                <div class="card card-primary card-outline">
                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                HOW MANAGEMENT WORKS?
                            </h4>
                        </div>
                    </a>
                    <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                        <div class="card-body">
		
			<p>Focus on running your business and serving customers while we handle your social media!</p>
			<p>1. You connect your social accounts (no need to share credentials, just use our apps).</p>
			<p>2. We create posts (after consultation, or provide content).</p>
			<p>3. You moderate posts (edit or delete any post and approve before publishing).</p>
			<p>4. We publish posts (max of 3 times per week).</p>
			<p>5. We send reports (to show you what's working).</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
			
<!------------------------------------ PREVIEW ------------------------------------>

<div class="card-body bg-dark">
			
  <div class="col-md-12">
					  
	  <div class="card card-widget">

              <div class="card-header bg-white border-0">
                <div class="user-block">
                  <img class="img-circle mr-2" src="https://suite.social/images/icons/<?php echo $network; ?>.png" alt="User Image">
                  <span class="username text-primary">Your <?php echo $network; ?> Post</span>
                  <span class="text-muted"><small>Shared publicly - <?php echo date("H:i a");?> Today</small></span>
                </div><!-- /.user-block -->
              </div><!-- /.card-header -->
	  
              <div class="pt-0 pb-0 card-body">  
			  
			  <p contenteditable="true"><span class="header "></span> - <span class="description"></span></p>

				<a href="#image" data-toggle="collapse" class="btn btn-dark btn-flat btn-block"><i class="fa-solid fa-image"></i> Replace Image</a>
				
				<div class="upload__box">
				<div id="image" class="collapse">
				
				<div class="btn-group btn-block">
                        <a href="#modal-gallery" data-toggle="modal" class="btn btn-default"><i class="fa-solid fa-search"></i> Search</a>
						<button onclick="hideImage()" style="cursor:pointer;" type="button" class="btn btn-default"><div class="upload__btn-box"><label class="upload__btn"><i class="fa-solid fa-upload"></i> Upload<input type="file" name="userfiles[]" data-max_length="20" class="upload__inputfile" style="width:100%"></label></div></button>
  
                        <!--<a href="#modal-upload" data-toggle="modal" class="btn btn-default"><i class="fa-solid fa-images"></i> Gallery</a>-->
                      </div>
				
				</div>	
								
                <div class="imageHide" id="imageRandom"><span class="img-container"><img id="imageGallery" class="img img-fluid"/></span></div>
				
				<div class="upload__img-wrap"></div>
				</div>
			
	             <p class="text-center"><a id="domain_link" target="_blank"><span class="domain"></span></a></p>
				 

				<small>
				<span class="float-left text-muted"><img width="18px" src="https://suite.social/images/icons/facebook_like.png" alt="Facebook like"> <span class="<?php $input_array = array("females", "males");echo $input_array[rand(0,sizeof($input_array)-1)];?>"></span> and <?php $min=100; $max=1000; echo rand($min,$max);?> others</span>
                <span class="float-right text-muted"><?php $min=10; $max=100; echo rand($min,$max);?> comments <?php $min=10; $max=100; echo rand($min,$max);?> shares</span>
				</small>

              </div><!-- /.card-body -->
			  
              <div class="pt-0 card-body">
			  
			  <hr>
				
				<div class="btn-group btn-block">
                        <button type="button" class="btn btn-white text-muted"><i class="far fa-thumbs-up"></i> Like</button>
                        <button type="button" class="btn btn-white text-muted"><i class="fa-regular fa-message"></i> Comment</button>
                        <button type="button" class="btn btn-white text-muted"><i class="fas fa-share"></i> Share</button>
                      </div>

              </div><!-- /.card-body -->
			  			  
				<!-- The div that might be hidden based on URL parameter -->
				<div id="footer" class="card-footer text-center <?php echo $hide_footer ? 'hidden' : ''; ?>">
					<button id="toggleButton" class="btn btn-lg btn-danger btn-block">
						<i class="fa-solid fa-arrow-right fa-beat-fade"></i> <b>VIEW PROPOSAL</b>
					</button>
					<p class="d-none">
						<a id="lead_url" class="mt-3 btn btn-block bg-gradient-success btn-lg" href="#">
							CLICK HERE TO VIEW SOCIAL POSTS!
						</a>
					</p>
				</div><!-- /.card-footer -->		  
			  			  
            </div><!-- /.card card-widget -->
          </div><!-- /.col-md-12 -->
		  
</div><!-- /.card-body bg-dark -->

<!------------------------------------ /PREVIEW ------------------------------------>

             </div><!-- /.preview-data hidden -->
            <span class="error-msg"></span>
        </div><!-- /.preview-container -->
    </div><!-- /.content -->
	
</div><!-- /.container -->

    </div><!-- /.card-body -->
  </div><!-- /.card -->
  
</div><!-- /.register-box -->

<?php
require_once '../footer.php';
?>

</body>
</html>
