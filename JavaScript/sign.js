const final = document.querySelector(".final");
const initial = document.querySelector(".initial");
const step = document.querySelector(".step");
function setRegister()
{
    final.style.display = "block";
    initial.style.display = "none";
    step.style.display = "none";
    
}
function setPage() {
    const yesRadio = document.getElementById("officer");
    
    if (yesRadio.checked) {
       document.querySelector('.p1').innerHTML = "Officer Details";
       document.querySelector('.p2').innerHTML = "Badge Number";
       document.querySelector('.p3').innerHTML = "Station Name";
       document.querySelector('.p4').innerHTML = "Create Password";
    } 
    else
    {
        document.querySelector('.p1').innerHTML = "Traffic police";
        document.querySelector('.p2').innerHTML = "Your Designation";
        document.querySelector('.p3').innerHTML = "State";
        document.querySelector('.p4').innerHTML = "Referal(Code provided by officials)";
    }
}
