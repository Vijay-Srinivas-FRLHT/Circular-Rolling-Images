<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php /** Use your own Jquery file, in whichever folder it is saved in your system **/ ?>
<?php
/****This is depicted as an array here. This can also be taken from the database.***/
$SomeArrayTakenFromDatabaseZ = Array(
	Array("imageurlid" => "zurich/z1.jpg", "imagedescription" => "First zurich image"),
	Array("imageurlid" => "zurich/z2.jpg", "imagedescription" => "Second zurich image"),
	Array("imageurlid" => "zurich/z3.jpg", "imagedescription" => "Third zurich image"),
	Array("imageurlid" => "zurich/z4.jpg", "imagedescription" => "Fourth zurich image"),
	Array("imageurlid" => "zurich/z5.jpg", "imagedescription" => "Fifth zurich image"),
	Array("imageurlid" => "zurich/z6.jpg", "imagedescription" => "Sixth zurich image"),
	Array("imageurlid" => "zurich/z7.jpg", "imagedescription" => "Seventh zurich image"),
	Array("imageurlid" => "zurich/z8.jpg", "imagedescription" => "Eighth zurich image"),
	Array("imageurlid" => "zurich/z9.jpg", "imagedescription" => "Ninth zurich image"),
	Array("imageurlid" => "zurich/z10.jpg", "imagedescription" => "Tenth zurich image"),
	Array("imageurlid" => "zurich/z11.jpg", "imagedescription" => "Eleventh zurich image"),
	Array("imageurlid" => "zurich/z12.jpg", "imagedescription" => "Twelfth zurich image")
);
$SomeArrayTakenFromDatabaseG = Array(
	Array("imageurlid" => "geneva/g1.jpg", "imagedescription" => "First geneva image"),
	Array("imageurlid" => "geneva/g2.jpg", "imagedescription" => "Second geneva image"),
	Array("imageurlid" => "geneva/g3.jpg", "imagedescription" => "Third geneva image"),
	Array("imageurlid" => "geneva/g4.jpg", "imagedescription" => "Fourth geneva image")
);
?>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
/*** Variables for Zurich Image Width/Height and Geneva Image Width/Height are defined below. These can be changed/increased/decreased as per requirement ***/
var zurichImagesWidth, zurichImagesHeight;
var genevaImagesWidth, genevaImagesHeight;

var imgSriniPointer = new Object(), maxNoOfDisplayBoxes = new Object(), frontJumperImageNo = new Object(), backJumperImageNo = new Object(), FRONT_JUMPER_POSITION=0, BACK_JUMPER_POSITION = new Object();
var INNER_VERTICAL_MARGIN = 0.05;
var INNER_HORIZONTAL_MARGIN = 0.05;
var DURATION = 300;
var MAX_NO_OF_IMAGE_BOXES_PERMISSIBLE_EITHER_DIRECTION = 5;
var MAX_NO_OF_IMAGE_BOXES_PERMISSIBLE = MAX_NO_OF_IMAGE_BOXES_PERMISSIBLE_EITHER_DIRECTION * 2 - 1;
var theVar = 0;

/*** Here in imgSriniCounter, we have initialized the object in terms of zurichimages, genevaimages. These can also be changed. But the class name etc everywhere needs to be mentioned the same. */
var imgSriniCounter= {zurichimages:0, genevaimages:0};

/*** Pushing all the Zurich Images. Again, changes can apply ***/
var allTheZurichImages = new Array();
<?php foreach($SomeArrayTakenFromDatabaseZ as $dbkey => $dbval): ?>
<?php
$objectToPush = "";
foreach($dbval as $eachindikey => $eachindival)
{
	$objectToPush .= $eachindikey.":\"".$eachindival."\", ";
}
$objectToPush .= "TheEnd";
$objectToPush = str_replace(", TheEnd", "", $objectToPush);
?>
allTheZurichImages.push({<?php echo $objectToPush; ?>});
<?php endforeach; ?>

/*** Pushing all the Geneva Images. Again, changes can apply, and this may not be there also, if only one rolling images are there. ***/
var allTheGenevaImages = new Array();
<?php foreach($SomeArrayTakenFromDatabaseG as $dbkey => $dbval): ?>
<?php
$objectToPush = "";
foreach($dbval as $eachindikey => $eachindival)
{
	$objectToPush .= $eachindikey.":\"".$eachindival."\", ";
}
$objectToPush .= "TheEnd";
$objectToPush = str_replace(", TheEnd", "", $objectToPush);
?>
allTheGenevaImages.push({<?php echo $objectToPush; ?>});
<?php endforeach; ?>


$(document).ready(function() {
	zurichImagesWidth = $(".zurichimages").width();
	zurichImagesHeight = $(".zurichimages").height();
	
	putFullHtml("zurichimages", allTheZurichImages, zurichImagesWidth, zurichImagesHeight);
	
	genevaImagesWidth = $(".genevaimages").width();
	genevaImagesHeight = $(".genevaimages").height();
	
	putFullHtml("genevaimages", allTheGenevaImages, genevaImagesWidth, genevaImagesHeight);

	$("#backward").click(function() {
		rollImages("backward", "zurichimages", zurichImagesWidth, allTheZurichImages);	
	});
	$("#forward").click(function() {
		rollImages("forward", "zurichimages", zurichImagesWidth, allTheZurichImages);	
	});
	
	$("#backwardg").click(function() {
		rollImages("backward", "genevaimages", genevaImagesWidth, allTheGenevaImages);	
	});
	$("#forwardg").click(function() {
		rollImages("forward", "genevaimages", genevaImagesWidth, allTheGenevaImages);	
	});

	$(".viewwindow .fulldisplayplate").on("click", "img", function() {
		var theImageSource = $(this).parent().find("div.imagedescription").html();
		alert(theImageSource);
	});
});

function putFullHtml(specificClass, allTheImages, viewWindowWidth, viewWindowHeight)
{
	var fullDisplayPlateWidth, fullDisplayPlateHeight, eachDisplayWidth, eachDisplayHeight, eachDisplayTop, maxNoOfImageBoxes, beforeImageDecidingNo;
	var noOfSriniImages = allTheImages.length;
	var htmlPart = "<div class=\"frontjumper\">Front Jumper</div>";
	noOfSriniImages = allTheImages.length;
	if(noOfSriniImages + 2 >= MAX_NO_OF_IMAGE_BOXES_PERMISSIBLE)
	{
		maxNoOfImageBoxes = MAX_NO_OF_IMAGE_BOXES_PERMISSIBLE;
		imgSriniPointer[specificClass] = Math.ceil((maxNoOfImageBoxes - 2) / 3);
	}
	else
	{
		maxNoOfImageBoxes = noOfSriniImages + 2;
		imgSriniPointer[specificClass] = 0;
	}
	maxNoOfDisplayBoxes[specificClass] = maxNoOfImageBoxes - 2;
	BACK_JUMPER_POSITION[specificClass] = maxNoOfImageBoxes - 1;
	beforeImageDecidingNo = Math.max(noOfSriniImages, maxNoOfDisplayBoxes[specificClass]);
	for(var checkImagePointer = 0;checkImagePointer < maxNoOfDisplayBoxes[specificClass]; checkImagePointer++)
	{
		if(checkImagePointer == 0)
		{
			frontJumperImageNo[specificClass] = (beforeImageDecidingNo - imgSriniPointer[specificClass] + checkImagePointer - 1) % beforeImageDecidingNo;
		}
		if(checkImagePointer == maxNoOfDisplayBoxes[specificClass] - 1)
		{
			backJumperImageNo[specificClass] = (beforeImageDecidingNo - imgSriniPointer[specificClass] + checkImagePointer + 1) % beforeImageDecidingNo;
		}
		htmlPart += "<div class=\"eachdisplay\"><img src=\"" + allTheImages[(beforeImageDecidingNo - imgSriniPointer[specificClass] + checkImagePointer) % beforeImageDecidingNo].imageurlid + "\"><div class='imagedescription'>" + allTheImages[(beforeImageDecidingNo - imgSriniPointer[specificClass] + checkImagePointer) % beforeImageDecidingNo].imagedescription + ".</div></div>";
	}
	htmlPart += "<div class=\"backjumper\">Back Jumper</div>";
	$("." + specificClass + " .fulldisplayplate").append(htmlPart);
	fullDisplayPlateWidth = viewWindowWidth * maxNoOfImageBoxes;
	fullDisplayPlateHeight = viewWindowHeight;
	$("." + specificClass + " .fulldisplayplate").css({"width":fullDisplayPlateWidth, "height":fullDisplayPlateHeight});
	eachDisplayWidth = viewWindowWidth * (1 - 2 * INNER_HORIZONTAL_MARGIN);
	eachDisplayHeight = viewWindowHeight * (1 - 2 * INNER_VERTICAL_MARGIN);
	eachDisplayTop = viewWindowHeight * INNER_VERTICAL_MARGIN;
	$("." + specificClass + " .eachdisplay").css({width:eachDisplayWidth, height:eachDisplayHeight});	
	$("." + specificClass + " .frontjumper").css({width:eachDisplayWidth, height:eachDisplayHeight});	
	$("." + specificClass + " .backjumper").css({width:eachDisplayWidth, height:eachDisplayHeight});	
	$("." + specificClass).css({width:viewWindowWidth});
	$("." + specificClass + " .frontjumper").css({left:(INNER_HORIZONTAL_MARGIN * viewWindowWidth), top:eachDisplayTop});


	for(var i = 0; i < maxNoOfDisplayBoxes[specificClass]; i++)
	{
		$("." + specificClass + " .eachdisplay").eq(i).css({left:((i + 1 + INNER_HORIZONTAL_MARGIN) * viewWindowWidth), top:eachDisplayTop});
	}
	$("." + specificClass + " .backjumper").css({left:((maxNoOfDisplayBoxes[specificClass] + 1 + INNER_HORIZONTAL_MARGIN) * viewWindowWidth), top:eachDisplayTop});
	imgSriniPointer[specificClass]++;
	$("." + specificClass + " .fulldisplayplate").css({left:"-" + (imgSriniPointer[specificClass] * viewWindowWidth) + "px"});
	$("." + specificClass + " .frontjumper").html("<img src=\"" + allTheImages[frontJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedescription'>" + allTheImages[frontJumperImageNo[specificClass]].imagedescription + ".</div>");
	$("." + specificClass + " .backjumper").html("<img src=\"" + allTheImages[backJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedescription'>" + allTheImages[backJumperImageNo[specificClass]].imagedescription + ".</div>");

	
}

function rollImages(direction, specificClass, viewWindowWidth, allTheImages)
{
	var noOfSriniImages = allTheImages.length;
	if(noOfSriniImages <= 1) return;
	if(direction == "backward")
	{
		imgSriniPointer[specificClass]--;
		imgSriniCounter[specificClass] = (noOfSriniImages + imgSriniCounter[specificClass] - 1) % noOfSriniImages;
	}
	else
	{
		imgSriniPointer[specificClass]++;
		imgSriniCounter[specificClass] = (imgSriniCounter[specificClass] + 1) % noOfSriniImages;
	}
	$("." + specificClass + " .fulldisplayplate").animate({left:"-" + (imgSriniPointer[specificClass] * viewWindowWidth)}, DURATION, function()  {
		if(noOfSriniImages > maxNoOfDisplayBoxes[specificClass])
		{
			if(imgSriniPointer[specificClass] == BACK_JUMPER_POSITION[specificClass] && direction == "forward")
			{
		//		alert("Wait");
				frontJumperImageNo[specificClass] = (noOfSriniImages + backJumperImageNo[specificClass] - 1) % noOfSriniImages;
				$("." + specificClass + " .frontjumper").html("<img src=\"" + allTheImages[frontJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedescription'>" + allTheImages[frontJumperImageNo[specificClass]].imagedescription + ".</div>");
				for(var checkImagePointer = 0;checkImagePointer < maxNoOfDisplayBoxes[specificClass]; checkImagePointer++)
				{
					$("." + specificClass + " .eachdisplay").eq(checkImagePointer).html("<img src=\"" + allTheImages[(backJumperImageNo[specificClass] + checkImagePointer) % noOfSriniImages].imageurlid + "\"><div class='imagedescription'>" + allTheImages[(backJumperImageNo[specificClass] + checkImagePointer) % noOfSriniImages].imagedescription + ".</div>");
					if(checkImagePointer == maxNoOfDisplayBoxes[specificClass] - 1)
					{
						backJumperImageNo[specificClass] = (backJumperImageNo[specificClass] + checkImagePointer + 1) % noOfSriniImages;
					}
				}
			}
			if(imgSriniPointer[specificClass] == FRONT_JUMPER_POSITION && direction == "backward")
			{
				backJumperImageNo[specificClass] = (noOfSriniImages + frontJumperImageNo[specificClass] + 1) % noOfSriniImages;
				$("." + specificClass + " .backjumper").html("<img src=\"" + allTheImages[backJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedesciption'>" + allTheImages[backJumperImageNo[specificClass]].imagedescription + ".</div>");
				for(var checkImagePointer = 0;checkImagePointer < maxNoOfDisplayBoxes[specificClass]; checkImagePointer++)
				{
//					alert((maxNoOfDisplayBoxes[specificClass] - checkImagePointer - 1) + " display will have image no. " + ((noOfSriniImages + frontJumperImageNo[specificClass] - checkImagePointer) % noOfSriniImages));
					$("." + specificClass + " .eachdisplay").eq(maxNoOfDisplayBoxes[specificClass] - checkImagePointer - 1).html("<img src=\"" + allTheImages[(noOfSriniImages + frontJumperImageNo[specificClass] - checkImagePointer) % noOfSriniImages].imageurlid + "\"><div class='imagedescription'>" + allTheImages[(noOfSriniImages + frontJumperImageNo[specificClass] - checkImagePointer) % noOfSriniImages].imagedescription + ".</div>");
					if(checkImagePointer == maxNoOfDisplayBoxes[specificClass] - 1)
					{
						frontJumperImageNo[specificClass] = (noOfSriniImages + frontJumperImageNo[specificClass] - checkImagePointer - 1) % noOfSriniImages;
					}
				}
			}
		}
		
		if(imgSriniPointer[specificClass] == FRONT_JUMPER_POSITION){
			imgSriniPointer[specificClass] = BACK_JUMPER_POSITION[specificClass] - 1;
			$("." + specificClass + " .fulldisplayplate").css({left:"-" + (imgSriniPointer[specificClass] * viewWindowWidth) + "px"});
		}
		if(imgSriniPointer[specificClass] == BACK_JUMPER_POSITION[specificClass])
		{
			imgSriniPointer[specificClass] = FRONT_JUMPER_POSITION + 1;
			$("." + specificClass + " .fulldisplayplate").css({left:"-" + (imgSriniPointer[specificClass] * viewWindowWidth) + "px"});
		}
		if(noOfSriniImages > maxNoOfDisplayBoxes[specificClass])
		{
			if(direction == "forward")
			{
				$("." + specificClass + " .backjumper").html("<img src=\"" + allTheImages[backJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedescription'>" + allTheImages[backJumperImageNo[specificClass]].imagedescription + ".</div> ");
			}
			if(direction == "backward")
			{
				$("." + specificClass + " .frontjumper").html("<img src=\"" + allTheImages[frontJumperImageNo[specificClass]].imageurlid + "\"><div class='imagedescription'>" + allTheImages[frontJumperImageNo[specificClass]].imagedescription + ".</div> ");
			}
		}
	});
}


</script>

<title>Circular Rolling Images</title>
</head>

<body>
<div class="theBigLeft">
<!-- This is a set for Zurich -->
    <div class="viewwindow zurichimages">
    	<div class="fulldisplayplate">
    	</div>
    </div>
    <div class="buttonplacez">
    <button id="backward" style="float:left">Backward</button>&nbsp;<button id="forward" style="float:right">Forward</button>
    </div>
    <div style="clear:both"></div>
</div>

<div class="theBigRight">    
<!-- This is a set for Geneva -->    
    <div class="viewwindow genevaimages">
    	<div class="fulldisplayplate">
    	</div>
    </div>

    <div class="buttonplaceg">
    <button id="backwardg" style="float:left">Backward</button>&nbsp;<button id="forwardg" style="float:right">Forward</button>
    </div>
    <div style="clear:both"></div>
</div>
 
 </body>
</html>
