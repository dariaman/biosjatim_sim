var xmlhttp = createRequestObject();
function createRequestObject() {
var ro;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer"){
ro = new ActiveXObject("Microsoft.XMLHTTP");
}else{
ro = new XMLHttpRequest();
}
return ro;
}

function mametBroker(combobox)
{
var kode = combobox.value;
if (!kode) return;
xmlhttp.open('get', 'plugins/reData.php?mp=metclient&kode='+kode, true);
xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
return false;
}
xmlhttp.send(null);
}

function mametBrokerMedical(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metclientmedical&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClient(combobox)
{
var kode = combobox.value;
if (!kode) return;
xmlhttp.open('get', 'plugins/reData.php?mp=metpolicy&kode='+kode, true);
xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
return false;
}
xmlhttp.send(null);
}

function mametClientMedical(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyMedical&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClientRateClaim(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyClaim&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}
/* MULTIPLE SET FIELD EXCEL */
function mametBrokerExcel(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metclientExl&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClientExcel(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyExl&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}
/* MULTIPLE SET FIELD EXCEL */

/* MULTIPLE SET UPLOAD EXCEL */
function mametBrokerUploadExcel(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metclientUploadExl&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClientUploadExcel(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyUploadExl&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}
/* MULTIPLE SET UPLOAD EXCEL */

function mametInsurance(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyIns&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClient(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicy&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}


//SETUP RATE INSURANCE
function mametBrokerRateIns(combobox)
{
var kode = combobox.value;
if (!kode) return;
xmlhttp.open('get', 'plugins/reData.php?mp=metclientRateIns&kode='+kode, true);
xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
	return false;
}
xmlhttp.send(null);
}

function mametClientProdukRateIns(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metproductRateIns&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coProduct").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}


function mametInsuranceName(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=mametInsuranceName_&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicyInsurance").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}


function mametInsuranceRate(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyInsRate&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicyInsRate").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametBrokerDocClaim(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metclientDocClaim&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coClient").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}

function mametClientDocClaim(combobox)
{
	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=metpolicyDocClaim&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{
	document.getElementById("coPolicy").innerHTML = xmlhttp.responseText;
}
		return false;
	}
	xmlhttp.send(null);
}


function UserPartner(combobox)
{ 	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=_userproduct&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{ 	document.getElementById("coProduk").innerHTML = xmlhttp.responseText;
}	return false;
	}
	xmlhttp.send(null);
}


function UserProduk(combobox)
{ 	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=_userRegional&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{ 	document.getElementById("coRegional").innerHTML = xmlhttp.responseText;
}	return false;
	}
	xmlhttp.send(null);
}

function UserRegional(combobox)
{ 	var kode = combobox.value;
	if (!kode) return;
	xmlhttp.open('get', 'plugins/reData.php?mp=_userCabang&kode='+kode, true);
	xmlhttp.onreadystatechange = function() {
if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
{ 	document.getElementById("coCabang").innerHTML = xmlhttp.responseText;
}	return false;
	}
	xmlhttp.send(null);
}
