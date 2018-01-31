function changeinfotab(strid) 
{
	for(i=1;i<7;i++)
	{
		var obj = document.getElementById('newslisttabpg12'+i);
		if(obj!=null) obj.style.display="none";
		document.getElementById('newslist11'+i).style.color="#3883A6";
	}
	 var obj = document.getElementById('newslisttabpg12'+strid);
	 if(obj!=null) 
	 {
		obj.style.display="block";
		document.getElementById('newslist11'+strid).style.color="blue";
	}
}
function chgkeywordstab(strid) 
{
	for(i=0;i<3;i++)
	{
		var obj = document.getElementById('divtab'+i);
		if(obj!=null) obj.style.display="none";
		var obj = document.getElementById('tabtitle'+i);
		if(obj!=null) obj.style.color="#888888";
	}
	var obj = document.getElementById('divtab'+strid);
	if(obj!=null) 
	{
		obj.style.display="block";
		document.getElementById('tabtitle'+strid).style.color="#111111";
	}
}

