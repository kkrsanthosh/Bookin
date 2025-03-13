<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<!-- Theme style -->
<link rel="stylesheet" href="https://suite.social/app/src/dist/css/adminlte.min.css">

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://suite.social/app/src/plugins/fontawesome/css/all.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

<!-- Styles -->
<link rel="stylesheet" href="https://suite.social/app/src/css/styles.css">
<link rel="stylesheet" href="https://suite.social/app/src/css/social-colors.css">

<!-- Select2 -->
<link rel="stylesheet" href="https://suite.social/app/src/plugins/select2/css/select2.min.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
	
body {
    margin:0;
}
	
.login-page, .register-page {
    /*display: block;*/
	height: auto;
    background-color: #fff;
	padding: 0;
}

.login-box, .register-box {
	width: 480px;
}

.btn-social.btn-lg :first-child {
    line-height: 45px;
}

.result-item .result-title {
    color: <?php echo $link; ?>;
    text-decoration: none;
    cursor: pointer;
}

.result-item .result-title:hover {
    color: <?php echo $linkHover; ?>;
    text-decoration: none;
}

a {
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.dynamic-content {
    display:none;
}

/**************************************** RESULTS ****************************************/

.result-item {
	display: flex;
	align-items: flex-start;
	margin-top: 1rem;
}
.result-item img {
	margin-right: 1rem;
	min-width: 150px;
	max-width: 150px;
	max-height: 150px;
	object-fit: cover;
}

.result-item div {
	overflow: hidden;
}

.result-item .direct-chat-messages {
  overflow-y: auto;
}

.result-item .direct-chat-messages .direct-chat-img {
  margin-right: auto;
  min-width: auto;
  min-height: auto;
}

.result-item .direct-chat-text .highlight {
    background-color: yellow;
}

.result-comment .direct-chat {
  width: 100%;
}

.nested-ctrl {
  position: relative;
  margin: 5px 0 0 10px;
  font-weight: 700;
  color: #666;
  text-align: right;
  cursor: pointer;
}

.nested-ctrl:hover {
  color: #333;
}

 /**************************************** FORMS ****************************************/

.form-control.is-valid, .was-validated .form-control:valid {
    background-image: url() !important;
}

.select2-container .select2-selection--single {
    height: calc(2.875rem + 2px);
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    margin-top: 3px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-left: -4px;
    margin-top: 8px;
}

.swalbtn-content {
    display: grid;
    justify-content: center;
    gap: 12px;
}

.swal-item {
    display: flex;
    align-items: center;
    gap: 20px;
}

 /**************************************** TABLE ****************************************/

.table td, .table th {
    vertical-align: middle;
}

.active .bs-stepper-circle {
    background-color: #ff99cc;
}

 /**************************************** WIDGET ****************************************/

#load_more {
  margin-top: 1rem;
}

#load_more.none {
  display: none;
}

#embedCode {
    text-wrap: wrap;
    word-break: break-all;
}

#copyButton {
    width: 100%;
    margin-bottom: 10px;
}

 /**************************************** MEDIA ****************************************/

/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
  .login-box, .register-box {width: 100%;}
}

</style>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2YT913FL5H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2YT913FL5H');
</script>

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "fkepp8sj7h");
</script>