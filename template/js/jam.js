<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function isodatetime()
	{
	var today = new Date();
	var year  = today.getYear();
	if (year < 2000)    // Y2K Fix, Isaac Powell
	year = year + 1900; // http://onyx.idbsu.edu/~ipowell

	var month = today.getMonth() + 1;
	var day  = today.getDate();
	var hour = today.getHours();
	var hourUTC = today.getUTCHours();
	var diff = hour - hourUTC;
	var hourdifference = Math.abs(diff);
	var minute = today.getMinutes();
	var minuteUTC = today.getUTCMinutes();
	var minutedifference;
	var second = today.getSeconds();
	var timezone;

	if (minute != minuteUTC && minuteUTC < 30 && diff < 0)
		{
		hourdifference--;
		}

	if (minute != minuteUTC && minuteUTC > 30 && diff > 0)
		{
		hourdifference--;
		}

	if (minute != minuteUTC)
		{
		minutedifference = ":30";
		}
	else
		{
		minutedifference = ":00";
		}

	if (hourdifference < 10)
		{
		timezone = "0" + hourdifference + minutedifference;
		}
	else
		{
		timezone = "" + hourdifference + minutedifference;
		}

	if (diff < 0)
		{
		timezone = "-" + timezone;
		}
	else
		{
		timezone = "+" + timezone;
		}


if (month <= 9) month = "0" + month;
if (day <= 9) day = "0" + day;
if (hour <= 9) hour = "0" + hour;
if (minute <= 9) minute = "0" + minute;
if (second <= 9) second = "0" + second;

time = hour + ":" + minute + ":" + second;

document.formx.display_jam.value = time;
window.setTimeout("isodatetime();", 500);
}
//  End -->
</script>
