<!DOCTYPE html>
<html>
<head>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KTXZN3');</script>
<!-- End Google Tag Manager -->
	<title>Essay Editor</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
	.navbar-inverse{
		background-color: #720000;
    	border-color: #720000;
	}
	.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover{
		background-color: #b98a37;
	}
	.navbar-inverse .navbar-toggle:focus, .navbar-inverse .navbar-toggle:hover{
		background-color: #b98a37;
	}
	.navbar-inverse .navbar-toggle {
    	border-color: #b98a37;
	}
	.navbar-brand{
		padding: 0px;
		padding-left: 10px;
	}
	.btn-default {
		background-color: #b98a37;
		border-color: #b98a37;
		color: white;
		margin-bottom: 5px;
	}
	.btn-default:hover {
	    color: white;
	    background-color: #b98a37;
	    border-color: #b98a37;
		transition: 0.3s;
		opacity: 0.7;
	}
	.btn-default:focus {
	    background-color: #b98a37;
		border-color: #b98a37;
		color: white;
	}
	input[type=file]{
	    width:90px;
	    color:transparent;
	}
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
	    -webkit-appearance: none;
	    -moz-appearance: none;
	    appearance: none;
	    margin: 0; 
	}
</style>

</head>
<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KTXZN3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<div class="container" id="container">
	<form action="result.php" method="post" enctype="multipart/form-data">
	<!--<center><h1 style="font-size: 45px;">Essay Editor</h1></center>-->
	<div style="margin-top: 55px;">
	    <p>Certain your essay will help you crack a top school? ReachIvy Experts disagree.</p>

<p>Our Essay Editing tool helps you fix repetition, verbosity, punctuation and much more.</p>

<p>Revise your essay. Make it error-free. Get one step closer to your dream school.</p>
</p>
		<p><b> Fix Your Essay in Three Easy Steps</b></p>
		<ol>
			<li>Paste your essay in the text area below</li>
			<li>Click on "Get Report"</li>
			<li>Check our Expert tips and fix your essay!</li>
		</ol>
			
			<!-- <div><button type="submit" class="btn btn-default" name="submit" />UPLOAD</button></div> -->
<!-- <button type="button" class="btn btn-default">UPLOAD</button><span>&nbsp;&nbsp;(.doc or .txt)</span> -->
<!-- <label class="btn btn-default">
<input type="file" hidden>
</label> -->
	</div>
	<div class="row">
	  <div class="col-md-1" style="padding-right: 0px;"><label for="wordLimit" style="vertical-align: -webkit-baseline-middle; margin-bottom: 0px;">Word Limit:</label></div>
	  <div class="col-md-1"><input type="number" class="form-control" id="wordLimit" name="wordLimit" maxlength="5"></div>
	</div>
	<div style="margin-top: 15px;">
			<div class="form-group">
		  		<textarea class="form-control" rows="10" id="essayTextarea" name="essayTextarea" placeholder="paste your text here"></textarea>
			</div>
		
	</div>
	
	
	<center><button type="submit" class="btn btn-default" name="submit" id="getReport" />Get Report</button></center>
</form>
<div style="position: absolute;bottom: 10px;right: 10px;">
	<a href="#" data-toggle="modal" data-target="#myModal">Disclaimer</a>
</div>
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Disclaimer</h4>
        </div>
        <div class="modal-body">
          <p>The services provided by this tool is for general perusal purposes only. The service and information are provided by ReachIvy, a property of Reach Education Private Limited. While we endeavour to keep the tool and contained information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</p>
          <p>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website. Through this website you are able to link to other websites which are not under the control of Reach Education Private Limited. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them. Every effort is made to keep the tools up and running smoothly. However, Reach Education Private Limited takes no responsibility for, and will not be liable for, the tools being temporarily unavailable due to technical issues beyond our control.</p>
          <b>Privacy Concerns:</b>
          <p>Any and all data collected by the tool is not collected or used by Reach Education Private Limited in any manner.</p>
          <b>This Disclaimer applies to:</b>
          <p>Free tools available through the ReachIvy Website (Resume Builder, Essay Editor, Interview Preparation Tool).</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
</div> 
<script type="text/javascript">
	$('#getReport').click(function(){
		var essay = $('#essayTextarea').val();
		var wordLimit = $('#wordLimit').val();
		if (essay=='') {
			alert('Textarea can not be empty.');
			return false;
		}else if (wordLimit=='') {
			alert('Word limit can not be empty');
			return false;
		}
	});
	var iframe = window.parent.document.getElementById("iframe_essay");
    var container = document.getElementById('container');
    iframe.style.height = container.offsetHeight + 'px'; 
</script>
</body>
</html>