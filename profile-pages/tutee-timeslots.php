<h2>Tutee Timeslots</h2>


<?php

$thisUsername = $loggedInUsername;

if(isset($_GET['username']) && $isDeptAdmin==true)
{
	$thisUsername = $_GET['username'];
}
$myTutees = imperialTutorQueries::getMyTutees($thisUsername);

imperialNetworkUtils::restrictToStaff();

?>

<span class="imperial-button" id="bookingButton"><i class="far fa-calendar-plus"></i> Create New Appointment Slots</span>


<!----- Main Booking Form ----->
<div id="bookingForm" class="contentBox hidden">

<h3>Appointment Form</h3>
<form method="post" action="?view=tutee-timeslots&username=<?php echo $username;?>&action=createSlots" class="imperial-form">


<label for="singleSlot"><input type="radio" id="singleSlot" name="creationType" value="single" checked>Single Slot</label>
<label for="multipleSlots"><input type="radio" id="multipleSlots" name="creationType" value="multi">Multiple Slots</label>
<hr/>

<div id="singleSlotForm">
<label for="singleDatepicker">Appointment Date</label>
<input type="text" class="singleDatepicker" name="singleDatepicker" value=""/>
<script>
jQuery(function() {
	jQuery( ".singleDatepicker" ).datepicker({
		dateFormat : "yy-mm-dd"
	});
});
</script>

<?php
echo drawTimepicker("singleHour", "singleMin");
echo '<hr/><label for="whichTutee">Which Tutee (optional)</label>';

echo '<select name="whichTutee" id="whichTutee">';
echo '<option value="">Please select</option>';

foreach ($myTutees as $thisYOS => $tuteeYOS_array)
{
	echo '<option value="">Year '.$thisYOS.'</option>';
	
	foreach ($tuteeYOS_array as $tuteeInfo)
	{
		$tuteeUsername = $tuteeInfo['username'];
		$tuteeName = $tuteeInfo['first_name'].' '.$tuteeInfo['last_name'];
		echo '<option value="'.$tuteeUsername.'">- '.$tuteeName.'</option>';
	}
	
	
}
echo '</select>';

echo '<div class="smallText">It is advised you only add tutees for appointments that have already taken place.<br/>Adding tutees to appointments this way wlil NOT send out calendar invitations to you or your tutee.</div>';

?>
</div>

<div id="multipleSlotsForm" class="hidden">
<h4>Recurrence</h4>
Create slots
<select name="frequency">
<option value="day">Daily</option>
<option value="weekly">Weekly</option>
<option value="fortnightly">Fortnightly</option>
<option value="monthly">Monthly</option>
</select>
starting on
<br/>
<label for="multiDatepickerStart">Start Date</label>
<input type="text" class="multiDatepickerStart" name="multiDatepickerStart" value=""/>
and ending on <br/>
<label for="multiDatepickerEnd">End Date</label>
<input type="text" class="multiDatepickerEnd" name="multiDatepickerEnd" value=""/>
<br/><br/>
starting at 
<?php
echo drawTimepicker("multiHour", "multiMin");
?>

<script>
jQuery(function() {
	jQuery( ".multiDatepickerStart" ).datepicker({
		dateFormat : "yy-mm-dd"
	});
});

jQuery(function() {
	jQuery( ".multiDatepickerEnd" ).datepicker({
		dateFormat : "yy-mm-dd"
	});
});
</script>

<hr/>
<label for="slotCount">Number of slots</label>
<input type="text" size="3" name="slotCount" value="1"> slot(s)

<label for="timeBetween">Time between slots</label>
<input type="text" size="3" name="timeBetween" value="0"> mins
</div>

<hr/>
<label for="duration">Duration</label>
<input type="text" size="3" name="duration" value="45"> mins

<label for="location">Location</label>
<input type="text" name="location">


<input type="submit" value="Create Slots" class="imperial-button">


<?php
// Add nonce
wp_nonce_field('addSlotsNonce');    
?>

</form>
</div>

<hr/>
<?php






$tab='upcoming-booked';
if(isset($_GET['tab']) )
{
	$tab=$_GET['tab'];
}
$linkArray = array
(
	"upcoming-booked"=> "Upcoming meetings",
	"previous"=> "Previous meetings",
	"upcoming"=> "All Upcoming slots",
);

foreach ($linkArray as $linkURL => $linkText)
{
	echo '<a href="?view=tutee-timeslots&username='.$thisUsername.'&tab='.$linkURL.'" class="imperial-button ';
	if($tab==$linkURL)
	{
		echo  ' selected ';
	}
	
	
	echo '"> '.$linkText.'</a>  ';
}














switch ($tab)
{
	
	case "upcoming":
		echo '<h3>Upcoming Appointment Slots</h3>';	
		echo imperialTutorsDraw::drawTutorSlots($thisUsername);
	break;
	
	case "previous":
		echo '<h3>Previous Meetings</h3>';	
		echo imperialTutorsDraw::drawTutorSlots($thisUsername, "past");
	break;		
	
	
	default:
	
		// Draw alerts for unsigned off meeitngs
		$unsignedOffSlots = imperialTutorQueries::getUnsignedOffSlots($thisUsername);
		$pendingCount = count($unsignedOffSlots);
		
		if($pendingCount>=1) 
		{
			echo '<div class="imperial-feedback imperial-feedback-alert">';		
			echo 'Please sign off <a href="?view=tutee-timeslots&username='.$thisUsername.'&tab=previous">'.$pendingCount.' meeting(s)</a> that took place';
			echo '</div>';
		}
			
	
	
		echo '<h3>Upcoming Meetings</h3>';	
		echo imperialTutorsDraw::drawTutorSlots($thisUsername, "future");
	break;
	
}









?>
<script>
jQuery( "#bookingButton" ).click(function() {
	
	jQuery( "#bookingForm" ).toggle( "fast");

});


jQuery( "#singleSlot" ).click(function() {
  jQuery( "#singleSlotForm" ).show( "fast")
  jQuery( "#multipleSlotsForm" ).hide( "fast")
});

jQuery( "#multipleSlots" ).click(function() {
  jQuery( "#singleSlotForm" ).hide( "fast")
jQuery( "#multipleSlotsForm" ).show( "fast")  
});


</script>


<?php

function drawTimepicker($hourID="myHour", $minID="myMin", $hourValue="", $minValue="")
{
	
	if($hourValue==""){$hourValue=10;}
	$i=1;
	$html = '';
	$html.='<select name="'.$hourID.'" id="'.$hourID.'">';
	while($i<=23)
	{
		$iString = $i;
		if($i<10){$iString = '0'.$i;}
		$html.='<option value="'.$iString.'"';
		if($hourValue==$iString){$html.= ' selected ';}
		$html.='>'.$iString.'</option>';
		$i++;
	}
	$html.='</select>';
	
	$i=0;
	$html.='<select name="'.$minID.'" id="'.$minID.'">';
	
	while($i<=59)
	{
		$iValue=$i;
		if($iValue<10){$iValue = '0'.$i;}
		$html.='<option value="'.$iValue.'">'.$iValue.'</option>';
		$i = $i+5;
	}
	$html.='</select>';	
	
	//$html.='<select name="'.$minID.'_AMPM" id="'.$minID.'_AMPM">';
	//$html.='<option value="AM">AM</option>';
	//$html.='<option value="PM">PM</option>';
	//$html.='</select>';
	
	return $html;
	
}





?>