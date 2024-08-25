var slideIndex = 1;

function loadLoginScripts() {
	document.getElementById("loginleftpanel").style.backgroundImage = "url('images/login_background.jpg')";
	document.getElementById("frmLogin").style.display="block";
    document.getElementById("frmSignup").style.display="none";
	document.getElementById("frmForgetPassword").style.display="none";
}

function loadLogoutScripts() {
	document.getElementById("loginleftpanel").style.backgroundImage = "url('images/login_background.jpg')";
}

function loadBookingsScripts() {
	if(!(sessionStorage.getItem("roomId") == "")) { 
		document.getElementById("frmTypeOfRoom").value = sessionStorage.getItem("roomId");
		sessionStorage.setItem("roomId", "");
	}
}

function loadHomeScripts() {
	sessionStorage.setItem("roomId", "");
	document.getElementById("sliderImage1").style.backgroundImage = "url('images/home/1.jpg')";
	document.getElementById("sliderImage2").style.backgroundImage = "url('images/home/2.jpg')";
	document.getElementById("sliderImage3").style.backgroundImage = "url('images/home/3.jpg')";
	document.getElementById("learnMoreImage").style.backgroundImage = "url('images/learn_more.jpg')";
	startSlideShow();
}

function calculateBill() {
	var frmTypeOfRoom = document.getElementById("frmTypeOfRoom");
	var frmBedType = document.getElementById("frmBedType");
	var nudNoOfAdults = document.getElementById("nudNoOfAdults");
	var nudNoOfChilds = document.getElementById("nudNoOfChilds");
	var dtCheckIn = document.getElementById("dtCheckIn");
	var dtCheckOut = document.getElementById("dtCheckOut");
	var txtNetAmount = document.getElementById("txtNetAmount");
	
	if(!(frmTypeOfRoom.value == "" || frmBedType.value == "" || dtCheckIn.value == "" || dtCheckOut.value == "")) {
		let checkInDate = new Date(dtCheckIn.value);
		let checkOutDate = new Date(dtCheckOut.value);
		if(checkInDate.getTime() > checkOutDate.getTime()) {
			txtNetAmount.innerHTML = "NET AMOUNT : -NILL";
		}
		else {
			var stayDuration = Math.round((checkOutDate.getTime() - checkInDate.getTime()) / (1000 * 3600));
			var lsRoomType = frmTypeOfRoom.options[frmTypeOfRoom.selectedIndex].text.split(" : ");
			var lsBeddingType = frmBedType.options[frmBedType.selectedIndex].text.split(" : ");
			lsRoomType[1] = lsRoomType[1].replace("$", "").trim();
			lsRoomType[2] = lsRoomType[2].replace("Persons", "").trim();
			document.getElementById("frmTypeOfRoomValue").value = lsRoomType[1];
			document.getElementById("frmTypeOfRoomName").value = lsRoomType[0];
			lsBeddingType[1] = lsBeddingType[1].replace("x", "").trim();
			document.getElementById("frmBedTypeValue").value = lsBeddingType[1];
			document.getElementById("frmBedTypeName").value = lsBeddingType[0];
			var noOfRooms = Math.round((parseInt(nudNoOfAdults.value) + parseInt(nudNoOfChilds.value)) / parseInt(lsRoomType[2]));
			if(noOfRooms == 0) { noOfRooms = 1; }
			var perDay = Math.round(parseInt(lsRoomType[1]) + (parseInt(lsRoomType[1]) / 100 * parseFloat(lsBeddingType[1]).toFixed(2)));
			var netAmount = Math.round(noOfRooms * (stayDuration * perDay / 24));
			txtNetAmount.innerHTML = "NET AMOUNT : $" + netAmount;
		}
	}
	else {
		txtNetAmount.innerHTML = "NET AMOUNT : -NILL";
	}
	document.getElementById("frmNetAmount").value = txtNetAmount.innerHTML;
}

function bookCurrentRoom(roomId) {
	sessionStorage.setItem("roomId", roomId);
	location.href = 'bookings.php';
}

window.onscroll = function() { scrollScreen() };

function updateSetting(input) {
	$.getJSON('getjson.php', {updatevalue: input.value, updatename: input.id}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			alert('Website settings updated successfully!');
		}
		else {
			alert('Some issues while updating website settings, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating website settings, please try again after some time!'); });
	location.href = 'admin.php#websitesettings';
}

function updateGallerySetting(input) {
	var curGalleryId = input.id.replace("gallery", "");
	$.getJSON('getjson.php', {updatecaption: input.value, imageid: curGalleryId}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			alert('Gallery caption updated successfully!');
		}
		else {
			alert('Some issues while updating gallery caption, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating gallery caption, please try again after some time!'); });
	document.getElementById("settingImage" + curGalleryId).alt = input.value;
	location.href = 'admin.php#gallerysettings';
}

function updateRoomPrice(input) {
	var newPrice = parseInt(input.value);
	if (isNaN(newPrice) || newPrice == null){
		alert('Please enter valid price!');
		location.href = 'admin.php';
		return;
	}
	if(newPrice < 1) {
		alert('Room price must be at-least $1!');
		location.href = 'admin.php';
		return;
	}
	$.getJSON('getjson.php', {updateprice: newPrice, updateroom: input.id.replace("room", "")}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			input.value = newPrice;
			alert('Room price updated successfully!');
		}
		else {
			alert('Some issues while updating room price, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating room price, please try again after some time!'); });
	location.href = 'admin.php#roomsettings';
}

function updateBeddingRatio(input) {
	var newRatio = parseFloat(input.value).toFixed(2);
	if (isNaN(newRatio) || newRatio == null){
		alert('Please enter valid percentage!');
		location.href = 'admin.php';
		return;
	}
	if(newRatio > 100) {
		alert('Ratio must be between 0-100%!');
		location.href = 'admin.php';
		return;
	}
	$.getJSON('getjson.php', {updateratio: newRatio, updatebedding: input.id.replace("bedding", "")}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			input.value = newRatio;
			alert('Bedding ratio updated successfully!');
		}
		else {
			alert('Some issues while updating bedding ratio, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating bedding ratio, please try again after some time!'); });
	location.href = 'admin.php#beddingsettings';
}

function updateCancelByUserBooking(bookingId) {
	$.getJSON('getjson.php', {cancelbyuserid: bookingId}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			alert('Booking details updated successfully!');
			updateStatusToUser(bookingId, "user");
		}
		else {
			alert('Some issues while updating booking details, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating booking details, please try again after some time!'); });
	location.href = 'bookings.php';
}

function updateCancelByAdminBooking(bookingId) {
	$.getJSON('getjson.php', {cancelbyadminid: bookingId}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			alert('Booking details updated successfully!');
			updateStatusToUser(bookingId, "admin");
		}
		else {
			alert('Some issues while updating booking details, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating booking details, please try again after some time!'); });
	location.href = 'admin.php';
}

function updateApproveBooking(bookingId) {
	$.getJSON('getjson.php', {approveid: bookingId}, function (data, textStatus, jqXHR) {
		if(data == "passed") {
			alert('Booking details updated successfully!');
			updateStatusToUser(bookingId, "admin");
		}
		else {
			alert('Some issues while updating booking details, please try again after some time!');
		}
	})
	.fail(function (jqxhr,settings,ex) { alert('Some issues while updating booking details, please try again after some time!'); });
	location.href = 'admin.php';
}

function updateStatusToUser(bookingId, sessionOf) {
	sessionStorage.setItem("bookingId", bookingId);
	sessionStorage.setItem("sessionOf", sessionOf);
	location.href = 'updateroomstatus.php';
}

function scrollScreen() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) { document.getElementById("scrollTop").style.display = "block"; } 
	else { document.getElementById("scrollTop").style.display = "none"; }
}

function checkInTimeChange() {
	document.getElementById("dtCheckOut").value = "";
	if(!(document.getElementById("dtCheckIn").value == "")) {
		let checkInMinTime = new Date(document.getElementById("dtCheckIn").value.substring(0, 10) + " " + document.getElementById("dtCheckIn").value.substring(11) + ":00");
		let checkInMaxTime = checkInMinTime;
		checkInMinTime = new Date(checkInMinTime.setTime(checkInMinTime.getTime() + (60 * 60 * 1000)));
		checkInMaxTime = new Date(checkInMaxTime.setTime(checkInMaxTime.getTime() + (60 * 60 * 1000) + (24 * 30 * 60 * 60 * 1000)));
		document.getElementById("dtCheckOut").min = checkInMinTime.toISOString().substring(0, 16);
		document.getElementById("dtCheckOut").max = checkInMaxTime.toISOString().substring(0, 16);
	}
}

function switchBookingTabs(switchToTab) {
	if(switchToTab == "left") {
		document.getElementById("leftSwitchTab").classList.add('active');
		document.getElementById("rightSwitchTab").classList.remove('active');
		document.getElementById("newbooking").style.display = "block";
		document.getElementById("mybookings").style.display = "none";
	}
	else if(switchToTab == "right") {
		document.getElementById("rightSwitchTab").classList.add('active');
		document.getElementById("leftSwitchTab").classList.remove('active');
		document.getElementById("newbooking").style.display = "none";
		document.getElementById("mybookings").style.display = "block";
	}
	document.getElementById("searchBookingId").value = "";
	searchBookingId("mybookings");
}

function searchBookingId(mainDivName) {
	var searchId = document.getElementById("searchBookingId").value;
	var lsBookings = document.getElementById(mainDivName).querySelectorAll('.bookingDiv,.mulColRow,.rightAligned');
	for (var i = 0, curDiv; curDiv = lsBookings[i]; i++) {
		if(curDiv.id && curDiv.id.startsWith("bookingId")) {
			var curId = curDiv.id.replace("bookingId", "");
			if(curId.includes(searchId)) { document.getElementById(curDiv.id).style.display = "block"; }
			else { document.getElementById(curDiv.id).style.display = "none"; }
		}
	}
}

function scrollToTopScreen() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

function popUpLearnMore() {
	document.getElementById("myModal").style.display = "block";
}

function popUpCloseLearnMore() {
	try { document.getElementById("myModal").style.display = "none"; } catch(err) { }
	try { document.getElementById("galleryModal").style.display = "none"; } catch(err) { }
}

function zoomImage(curImage) {
	document.getElementById("galleryModal").style.display = "block";
	document.getElementById("zoomImage").src = curImage.src;
	document.getElementById("galleryCaption").innerHTML = curImage.alt;
}

window.onclick = function(event) {
  if (event.target == document.getElementById("myModal")) {
    document.getElementById("myModal").style.display = "none";
  }
}

function startSlideShow() {
	slideIndex = 1;
	showCustomSlides(slideIndex);
	slideIndex = 0;
	showSlides();
}

function plusSlides(n) {
    showCustomSlides(slideIndex += n);
}

function currentSlide(n) {
    showCustomSlides(slideIndex = n);
}

function showCustomSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("sliderDot");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
		dots[i].className = dots[i].className.replace(" slideActive", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " slideActive";
}

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("sliderDot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
		dots[i].className = dots[i].className.replace(" slideActive", "");
    }
    slideIndex++;
    if (slideIndex > slides.length) { slideIndex = 1 }
    slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " slideActive";
    setTimeout(showSlides, 2000);
}

function switchForm(curForm) {
	if(curForm === "login") {
		document.getElementById("frmForgetPassword").style.display="none";
		document.getElementById("frmLogin").style.display="block";
		document.getElementById("frmSignup").style.display="none";
	}
	else if(curForm === "signup") {
		document.getElementById("frmForgetPassword").style.display="none";
		document.getElementById("frmLogin").style.display="none";
		document.getElementById("frmSignup").style.display="block";
	}
	else {
		document.getElementById("frmForgetPassword").style.display="block";
		document.getElementById("frmLogin").style.display="none";
		document.getElementById("frmSignup").style.display="none";
	}
	document.getElementById("username").value = "";
	document.getElementById("lgnpassword").value = "";
	document.getElementById("fname").value = "";
	document.getElementById("lname").value = "";
	document.getElementById("email").value = "";
	document.getElementById("telephone").value = "";
	document.getElementById("password").value = "";
	document.getElementById("femail").value = "";
	
	document.getElementById("lgnpassword").setAttribute('type', 'password');
	document.getElementById("loginPassword").setAttribute("class", "far fa-eye");
	document.getElementById("loginPassword").style.color = "black";
	
	document.getElementById("password").setAttribute('type', 'password');
	document.getElementById("togglePassword").setAttribute("class", "far fa-eye");
	document.getElementById("togglePassword").style.color = "black";
}

function filterName(itemName) {
	var filterName = document.getElementById(itemName).value.replace(/[^a-zA-Z]/gi, '');
	document.getElementById(itemName).value = filterName.toUpperCase();
}

function filterFullName(itemName) {
	var txtFullName = document.getElementById(itemName).value.replace(/[^a-zA-Z ]/gi, '');
	document.getElementById(itemName).value = txtFullName.toUpperCase();
}

function filterEmailID(itemName) {
	var txtEmailID = document.getElementById(itemName).value;
	document.getElementById(itemName).value = txtEmailID.toUpperCase();
}

function checkEmailID(itemName) {
	var txtEmailID = document.getElementById(itemName);
	if(txtEmailID.value == "") { return; }
	var re = new RegExp(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/);
	if (re.test(txtEmailID.value)) { txtEmailID.value = txtEmailID.value.toUpperCase(); }
	else { 
		alert("Please enter valid email-id!");
		txtEmailID.value = ""; 
	}
}

function checkPassword(itemName) {
	var txtPassword = document.getElementById(itemName);
	if(txtPassword.value == "") { return; }
	var re = new RegExp(/^[A-Za-z0-9@]{1,25}$/);
	if (re.test(txtPassword.value)) { txtPassword.value = txtPassword.value; }
	else { 
		alert("Please enter valid password!");
		txtPassword.value = ""; 
	}
}

function togglePassword(itemName, iconName) {
	var txtPassword = document.getElementById(itemName);
	var toggleIcon = document.getElementById(iconName);
	if(txtPassword.getAttribute('type') === 'password') {
		txtPassword.setAttribute('type', 'text');
		toggleIcon.setAttribute("class", "far fa-eye-slash");
		toggleIcon.style.color = "#11998e";
	}
	else {
		txtPassword.setAttribute('type', 'password');
		toggleIcon.setAttribute("class", "far fa-eye");
		toggleIcon.style.color = "black";
	}
}

function filterTelephone(itemName) {
	var txtTelephone = document.getElementById(itemName).value.replace(/[^0-9]/gi, '');
	document.getElementById(itemName).value = txtTelephone.toUpperCase();
}

function filterNumber(item) {
	var txtTelephone = item.value.replace(/[^0-9]/gi, '');
	item.value = txtTelephone.toUpperCase();
	if(item.value == 0) { item.value = ""; }
}

function filterDecimal(item) {
	var txtTelephone = item.value.replace(/[^0-9.]/gi, '');
	item.value = txtTelephone.toUpperCase();
	if(item.value == 0 || (item.value.match(RegExp('\\.', 'g')) || []).length > 1) { item.value = ""; }
}

function checkTelephone(itemName) {
	var txtTelephone = document.getElementById(itemName);
	if(txtTelephone.value == "") { return; }
	var re = new RegExp(/^[0-9]{10}$/);
	if (re.test(txtTelephone.value)) { txtTelephone.value = txtTelephone.value; }
	else { 
		alert("Please enter valid mobile number!");
		txtTelephone.value = ""; 
	}
}