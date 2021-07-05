function validateform()
{
    var username=document.myform.username.value;
    var password=document.myform.password.value;
    var email=document.myform.email.value;
    var confirm_password=document.myform.confirm_password.value;

    if (username==null || username=="")
    {
        alert("Username can't be blank");
        return false;
    }
    else if (email==null || email=="")
    {
        alert("Email can't be blank");
        return false;
    }
    else if (password==null || password=="")
    {
        alert("Password can't be blank");
        return false;
    }
    else if(password.length<8)
    {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    else if (confirm_password==null || confirm_password=="")
    {
        alert("Confirm Password can't be blank");
        return false;
    }
    else if(confirm_password.length<8)
    {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    else if(confirm_password != password)
    {
        alert("Password does not match");
        return false;
    }
}
function checkEmpty()
{
    if (document.myform.username.value = "")
    {
        alert("Name can't be blank");
        return false;
    }
}
function checkName()
{
    if (document.getElementById("username").value == "")
    {
        document.getElementById("nameErr").innerHTML = "Username can't be blank";
        document.getElementById("nameErr").style.color = "red";
        document.getElementById("username").style.borderColor = "red";
    }
    else
    {
        document.getElementById("nameErr").innerHTML = "";
        document.getElementById("username").style.borderColor = "black";
    }
}
function checkEmail()
{
    if (document.getElementById("email").value == "")
    {
        document.getElementById("emailErr").innerHTML = "Email can't be blank";
        document.getElementById("emailErr").style.color = "red";
        document.getElementById("email").style.borderColor = "red";
    }
    else
    {
        document.getElementById("emailErr").innerHTML = "";
        document.getElementById("email").style.borderColor = "black";
    }
}
function checkPass()
{
    if (document.getElementById("password").value == "")
    {
        document.getElementById("passErr").innerHTML = "Password can't be blank";
        document.getElementById("passErr").style.color = "red";
        document.getElementById("password").style.borderColor = "red";
    }
    else if(document.getElementById("password").value.length<8)
    {
        document.getElementById("password").style.borderColor = "red";
        document.getElementById("passErr").style.color = "red";
        document.getElementById("passErr").innerHTML = "Password must be at least 8 characters long.";
    }
    else
    {
        document.getElementById("passErr").innerHTML = "";
        document.getElementById("passErr").style.color = "red";
        document.getElementById("password").style.borderColor = "black";
    }
}

function checkPassMatch()
{
    if (document.getElementById("password").value != document.getElementById("confirm_password").value)
    {
        document.getElementById("passCErr").innerHTML = "Confirm Password does not match";
        document.getElementById("passCErr").style.color = "red";
        document.getElementById("confirm_password").style.borderColor = "red";
    }
}

function checkCPass()
{
    if (document.getElementById("confirm_password").value == "")
    {
        document.getElementById("passCErr").innerHTML = "Confirm Password can't be blank";
        document.getElementById("passCErr").style.color = "red";
        document.getElementById("confirm_password").style.borderColor = "red";
    }
    else if(document.getElementById("confirm_password").value.length<8)
    {
        document.getElementById("confirm_password").style.borderColor = "red";
        document.getElementById("passCErr").style.color = "red";
        document.getElementById("passCErr").innerHTML = "Confirm Password must be at least 8 characters long.";
    }
    else
    {
        document.getElementById("passCErr").innerHTML = "";
        document.getElementById("passCErr").style.color = "red";
        document.getElementById("confirm_password").style.borderColor = "black";
    }
}
