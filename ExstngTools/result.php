<?php

define('PATH', __DIR__ . DIRECTORY_SEPARATOR);

require PATH . 'phproofreading.class.php';

$phproofreading = new PHProofReading();
$phproofreading->languages_cache_file = PATH . $phproofreading->languages_cache_file;
$languages = $phproofreading->languages();

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	if (isset($_FILES['fileToUpload'])) {
		$path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
		if (!empty($path_parts['filename'])) {
			$target_dir = "uploads/";
			// $path_parts = pathinfo($_FILES["fileToUpload"]["name"]);
			$file_path = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
			$target_file = $target_dir . $file_path;
			// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if($FileType != "txt" && $FileType != "docx") {
			    echo "Sorry, only txt & docx files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			        $result = exec("/usr/bin/python main.py $target_file");
					// echo $result;
					$result_array = json_decode($result);
					foreach ($result_array as $key => $value) {
						// echo '<br>'.$key.' : '.$value;
						if ($key=='sentences') {
							$sentenceNum = $value;
						}elseif ($key == 'words') {
							$wordNum = $value;
						}elseif ($key == 'paragraphs'){
							$paragraphNum = $value;
						}elseif ($key == 'sentences2') {
							$sentences = $value;
						}elseif ($key=='exclamation') {
							$exclamation = $value;
						}elseif ($key=='longSentence') {
							$longSentence=$value;
						}elseif ($key=='repeatWords') {
							$repeatWords=$value;
						}elseif ($key=='commaSentences') {
							$commaSentences=$value;
						}elseif ($key=='commaError') {
							$commaError=$value;
						}elseif ($key=='synSentence') {
							$synSentence=$value;
						}elseif ($key=='longPara') {
							$longPara=$value;
						}
					}
			    } else {
			        echo "Sorry, there was an error uploading your file.";
			    }
			}
		}
	}

	if (isset($_POST['essayTextarea'])) {
		$essayTextarea = $_POST['essayTextarea'];
		if (!empty($essayTextarea)) {
			$myfile = fopen("uploads/newfile.txt", "w") or die("Unable to open file!");
			$txt = $essayTextarea;
			fwrite($myfile, $txt);
			$target_dir = "uploads/";
			$target_file = $target_dir . 'newfile.txt';
			$result = exec("/usr/bin/python main.py $target_file");
			// echo $result;
			$result_array = json_decode($result);
			foreach ($result_array as $key => $value) {
				// echo '<br>'.$key.' : '.$value;
				if ($key=='sentences') {
					$sentenceNum = $value;
				}elseif ($key == 'words') {
					$wordNum = $value;
				}elseif ($key == 'paragraphs'){
					$paragraphNum = $value;
				}elseif ($key == 'sentences2') {
					$sentences = $value;
					// print_r($sentences);
				}elseif ($key=='exclamation') {
					$exclamation = $value;
				}elseif ($key=='longSentence') {
					$longSentence=$value;
				}elseif ($key=='repeatWords') {
					$repeatWords=$value;
				}elseif ($key=='commaSentences') {
					$commaSentences=$value;
				}elseif ($key=='commaError') {
					$commaError=$value;
				}elseif ($key=='synSentence') {
					$synSentence=$value;
				}elseif ($key=='longPara') {
					$longPara=$value;
				}
			}
		}
		$text = trim($_POST['essayTextarea']);
		$check = $phproofreading->check($text, 'en-US');
		$count = count($check['matches']);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Essay Editor</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css"> -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    @import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
	.btn-default {
		background-color: #b98a37;
		border-color: #b98a37;
		color: white;
		margin-bottom: 15px;
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
	.card:focus, .tooltip:focus {outline:none;}
	.card:focus{border:1px solid rgba(0,0,0,0.5);}
	.card{
	    -webkit-tap-highlight-color: transparent;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.5;
        color: #292b2c;
        box-sizing: inherit;
        position: relative;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        flex-direction: column;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
	}
	.card-block{
	    -webkit-tap-highlight-color: transparent;
        font-weight: 400;
        line-height: 1.5;
        color: #292b2c;
        -webkit-box-direction: normal;
        box-sizing: inherit;
        -webkit-box-flex: 1;
        flex: 1 1 auto;
        padding: 1.25rem;
	}
	.btn-success{background:#b98137!important;border-color:#b98137!important;}
	mark { cursor: pointer; background:#b98a377d !important; }
	.tooltip-inner { text-align: inherit; cursor:context-menu; }
	.replacements .btn { margin-bottom: 5px; }
	div.advanced { display: none; }
	.numerrorstext{margin-bottom:20px; background:#f3eddf;display:flex;justify-content:space-evenly;align-items:center;}.numerrorstext i{margin-right:10px;font-size:25px;}
	.panel-title > a:before {
    float: right !important;
    font-family: FontAwesome;
    content:"\f068";
    padding-right: 5px;
}
.panel-title > a.collapsed:before {
    float: right !important;
    content:"\f067";
}
.panel-heading{
    background:#f3eddf !important;cursor:pointer;
}
.active-panel{background:#b98a37 !important; color:#fff !important;}

</style>
</head>
<body>
<div class="hideme"></div>
<div class="container" id="container" style="margin-top: 50px;">
	<div class="col-md-8">
		<div class="numerrorstext alert alert-warning"><i class="fa fa-exclamation"></i><span><?php echo $count. " spelling and grammatical errors found. Click on the highlighted text to see suggestions to fix the error.</span>" ?></div>
				<div class="card bg-light text-dark" contenteditable="true">
					<div class="card-block" id="my-content">
						<?php

						krsort($check['matches']);
						mb_internal_encoding('UTF-8');

						foreach ($check['matches'] as $key => $match) {
							if (isset($_POST['auto_fix']) && count($match['replacements']) > 0) {
								$text = mb_substr($text, 0, $match['offset']) . '<em>' . $match['replacements'][0]['value'] . '</em>' . mb_substr($text, $match['offset'] + $match['length']);
							} else {
							    $tooltip = ' ';
								$tooltip .= '<p>' . $match['message'] . '</p><div class="replacements">';

								foreach ($match['replacements'] as $replacement)
									$tooltip .= '<button type="button" class="btn-correct btn btn-sm btn-success" data-mark="' . $key . '">' . $replacement['value'] . '</button> ';

								$tooltip .= '</div>';

								$text = mb_substr($text, 0, $match['offset']) . '<mark id="mark_' . $key . '" data-toggle="tooltip" data-html="true" title="' . htmlspecialchars($tooltip) . '">' . mb_substr($text, $match['offset'], $match['length']) . '</mark>' . mb_substr($text, $match['offset'] + $match['length']);
							}
						}

						print nl2br($text);

						?>
					</div>
				</div>
				<form action="result.php" onsubmit="return getContent()" method="post" enctype="multipart/form-data">
				    <input style="display:none" type="number" class="form-control" id="wordLimit" name="wordLimit" value="<?php if(isset($_POST['wordLimit']) && !empty($_POST['wordLimit'])){echo $_POST['wordLimit'];} ?>">
			        <textarea name="essayTextarea" id="essayTextarea" style="display:none"></textarea>
				    <center><button style="margin-top:25px;" type="submit" class="btn btn-default" name="submit" id="getReport" />Review Again</button></center>
				</form>
				
				<script>
                    function getContent(){
                        document.getElementById("essayTextarea").value = document.getElementById("my-content").innerText;
                        console.log(document.getElementById("essayTextarea").value)
                    }
                </script>
			
				<div style="text-align:center;margin-top:20px;">
				    <h4 style="color:#920000">Want expert help on your essay?</h4>
				    <div class="btn btn-default"><a style="color:white; text-decoration:none" href="/contact-us.html" target="_blank">Reach Out to Us</a></div>
				    </div>
	</div>
	<div class="col-md-4">
		<div class="panel-group" id="accordion">
		  <div class="panel panel-default ">
		    <div class="panel-heading first_collapse" data-toggle="collapse" data-target="#collapse6" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Number of Words</a>
		      </h4>
		    </div>
		    <div id="collapse6" class="panel-collapse collapse">
		      <div class="panel-body ">
		      <?php 
		      echo 'Word count = '.$wordNum;
		      echo "<br>Word limit = ".$_POST['wordLimit']."<br>";
		      $percentage = ($wordNum - $_POST['wordLimit'])*100/$_POST['wordLimit'];
		      $percentage = round($percentage,2);
		      if ($percentage>=0) {
		      	echo "<strong>You exceded word limit by = ";
		      	echo $percentage.'%</strong>';
		      }elseif ($percentage<0) {
		      	echo "<strong>You fall short of word limit by = ".abs($percentage)."%</strong>";
		      }
		      ?>
		      </div>
		    </div>
		  </div>	
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse1" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion" >
		        Repeated Words</a>
		      </h4>
		    </div>
		    <div id="collapse1" class="panel-collapse collapse" data-parent="#accordion">
		      <div class="panel-body">
		      	Repetition feels monotonous and is indicative of a lack of understanding of the subject matter.<br><br><em>Tip: Fix this by using another, more specific word in its place.</em><br>			
		      	<!-- <b>Consider using synonyms</b> -->
		      	<?php
		      	if (empty((array) $repeatWords)) {
		      			echo "<strong>There are no repeated words in your essay.</strong>";
		      	}else{
		      	    echo "<strong>Following are the repeated words in your essay: </strong><br>";
			      	foreach ($repeatWords as $key => $value) {
			      		echo $value;
			      		echo "<br>";
			      	}
		      	}
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse11" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion" >
		        Vocabulary Check</a>
		      </h4>
		    </div>
		    <div id="collapse11" class="panel-collapse collapse">
		      <div class="panel-body">
		      	<b>While writing an essay, try to use words that convey your intended meaning vividly and accurately. 
		      	By using  a variety of words, you breathe life into your writing. Always strive to make your essay sound unique and not boring!<br>
Words like nice, unique, interesting are overused. Consider replacing them with these:</b><br>
		      		<em>Nice</em>: stunning, exquisite, pleasant, perfect<br>
		      		Interesting: Intriguing, fascinating, absorbing, amusing<br>
		      		<em>Unique</em>: Unequalled, unparalleled, unusual
		      	<br><br>
		      	<?php
		      		if (empty((array) $synSentence)) {
		      			echo "No words that match the above list.";
		      		}else{
			      		foreach ($synSentence as $key => $value) {
			      			echo $value;
			      			echo "<br><br>";
			      		}
		      		}
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse5" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Use of Exclamation</a>
		      </h4>
		    </div>
		    <div id="collapse5" class="panel-collapse collapse">
		      <div class="panel-body">
		      	Exclamation marks are used to express a strong emotion or an emphatic declaration. However, they have a limited place in formal writing.<br><br><em>Tip: Keep exclamation marks to a minimum in a formal essay.</em><br>
		      	<?php
		      		if (empty((array) $exclamation)) {
		      			echo "<strong>There are no exclamation marks in your essay</strong><br>";
		      		}else{
		      		    echo "<strong>Following are the instances where exclamation marks are used </strong><br>";
			      		foreach ($exclamation as $key => $value) {
			      			echo $value;
			      			echo "<br><br>";
			      		}
		      		}	
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse9" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Use of Comma</a>
		      </h4>
		    </div>
		    <div id="collapse9" class="panel-collapse collapse">
		      <div class="panel-body">
		      	Commas are used to introduce relevant pauses in sentences; overusing them makes the sentence-flow feel jerky.<br><br><em>Tip: Revisit your comma usage to ensure they are only used where necessary.</em><br>
		      	<?php
		      		if (empty((array) $commaSentences)) {
		      			echo "<strong>No excess use of comma in your essay</strong><br>";
		      		}else{
		      		    echo "<strong>Following are the instances in your essay where excessive commas are used: </strong><br>";
			      		foreach ($commaSentences as $key => $value) {
			      			echo $value;
			      			echo "<br><br>";
			      		}
		      		}
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse10" data-parent="#accordion">
		      <h4 class="panel-title">
		        <adata-parent="#accordion">
		        Space Before Comma</a>
		      </h4>
		    </div>
		    <div id="collapse10" class="panel-collapse collapse">
		      <div class="panel-body">
		      	<em>Tip: Never insert a space before using a comma in the English language.</em> <br>
		      	<?php
		      		if (empty((array) $commaError)) {
		      			echo "<strong>No instances of space before comma in your essay</strong><br>";
		      		}else{
		      		    echo "<strong>Following are instances of space before comma in your essay: </strong><br>";
			      		foreach ($commaError as $key => $value) {
			      			echo $value;
			      			echo "<br><br>";
			      		}
		      		}
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading"  data-toggle="collapse" data-target="#collapse7" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Number of Sentences</a>
		      </h4>
		    </div>
		    <div id="collapse7" class="panel-collapse collapse">
		      <div class="panel-body">
		      	There are <?php echo $sentenceNum; ?> sentences in your essay.
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse3" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion" >
		        Long Sentences</a>
		      </h4>
		    </div>
		    <div id="collapse3" class="panel-collapse collapse">
		      <div class="panel-body">
		      	As you add on words and subsequent meaning to the same sentence, it becomes difficult to read.<br><br><em>Tip: Keep the sentences short (less than 18 words) to increase the readability of your essay.</em><br>
		      	<!-- <b>These sentences exceed 18 words, consider rephrasing by using transition words such as Additionally, Furthermore, Moreover etc.</b><br> -->
		      	<?php
		      	if (empty((array) $longSentence)) {
		      		echo "<br><strong>There are no long sentences in your essay.</strong><br>";
		      	}else{
		      	    echo "<strong>Consider shortening (less than 18 words) the following long sentence(s)</strong><br>";
		      		foreach ($longSentence as $key => $value) {
			      		echo $value;
			      		echo "<br><br>";
			      	}
		      	}
		      	
		      	?>
		      </div>
		    </div>
		  </div>
		  <!-- <div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
		        Average Length of sentences</a>
		      </h4>
		    </div>
		    <div id="collapse2" class="panel-collapse collapse">
		      <div class="panel-body">
		      	Average length of sentence = 
		      	<?php
		      	//echo round($wordNum/$sentenceNum);
		      	?>
		      </div>
		    </div>
		  </div> -->
		  
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse4" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Number of Paragraphs</a>
		      </h4>
		    </div>
		    <div id="collapse4" class="panel-collapse collapse">
		      <div class="panel-body">
		          The body of the essay can be divided into paragraphs based on the arguments or points you wish to present in your essay.<br><br><em>Tip: Use one paragraph each to explain and argue for a single concept.</em><br>
		      	  <strong>There are <?php echo $paragraphNum; ?> paragraph(s) in your essay.</strong></div>
		    </div>
		  </div>		  
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse12" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a data-parent="#accordion">
		        Long Paragraphs</a>
		      </h4>
		    </div>
		    <div id="collapse12" class="panel-collapse collapse">
		      <div class="panel-body">
		          Although there is no definitive rule to follow when writing a paragraph for an essay, it is important that you maintain the length of a paragraph to match the length of your essay.<br> 
		          <em>The basic rule for determining paragraph length is to restrict each paragraph to only one main idea.</em><br>
		      	<?php
		      		if (empty((array) $longPara)) {
		      			echo "<strong>There are no long paragraphs in your essay.</strong>";
		      		}else{
			      		foreach ($longPara as $key => $value) {
			      			echo '<strong>Following are the long paragraphs in your essay (more than 8 sentences):</strong> <br>'.$value.' .....';
			      			echo "<br><br>";
			      		}
		      		}	
		      	?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-default">
		    <div class="panel-heading" data-toggle="collapse" data-target="#collapse8" data-parent="#accordion">
		      <h4 class="panel-title">
		        <a>
		        Replace Numbers Between 0-9</a>
		      </h4>
		    </div>
		    <div id="collapse8" class="panel-collapse collapse">
		      <div class="panel-body">
		      	There are several rules on how to handle numbers, but here’s the standard one:<br>Spell out numbers under 10 (zero - nine) and use numeric symbols for the number 10 and above. 
		      	<?php
		      		if (empty((array) $sentences)) {
		      			echo "There are no instances of numbers in your essay.";
		      		}else{
		      		    echo "<br><strong>Following are the instances of numbers in your essay:</strong>";
		      			foreach ($sentences as $key => $value) {
			      			echo "<br>";
			      			// print_r($value[0]);
			      			foreach ($value as $key2 => $value2) {
			      				echo $value2.' ,';
			      			}
			      		 	echo ' -> '.$key;
			      		}
		      		}
		      	?>
		      </div>
		    </div>
		  </div>
		</div>

	</div>
</div>

<?php
unlink($target_file) or die("Couldn't delete file");
?>

<script type="text/javascript">
	$(function () {
	    
        $('.first_collapse').click();
        
        $('.first_collapse').addClass("active-panel");
	    
		$('[data-toggle="tooltip"]').tooltip( { trigger: "click focus" } );

		$("mark").click(function(){
			$("mark").not(this).tooltip("hide");
		});

		$("body").on("click", ".btn-correct", function(){
			var mark_id = $(this).attr("data-mark").toString();
			var mark = $("#mark_" + mark_id);
			
			mark.html($(this).html());
			mark.tooltip("destroy");
			mark.replaceWith(mark.html());
		});

		$(".panel-group").click(function(){
			$('[data-toggle="tooltip"]').tooltip("hide");
		});
		
		$('#accordion > .panel').on('show.bs.collapse', function (e) {
            $(this).find('.panel-heading').addClass("active-panel");
        });
		$('#accordion > .panel').on('hide.bs.collapse', function (e) {
            $(this).find('.panel-heading').removeClass("active-panel");
        });
	});
</script>

<script>
	var iframe = window.parent.document.getElementById("iframe_essay");
    var container = document.getElementById('container');
    iframe.style.height = (container.offsetHeight+50) + 'px'; 
</script>
</body>
</html>