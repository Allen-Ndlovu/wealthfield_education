let body = document.body;

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   searchForm.classList.remove('active');
}

let searchForm = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   searchForm.classList.toggle('active');
   profile.classList.remove('active');
}

let sideBar = document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick = () =>{
   sideBar.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('.side-bar .close-side-bar').onclick = () =>{
   sideBar.classList.remove('active');
   body.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   searchForm.classList.remove('active');

   if(window.innerWidth < 1200){
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }

}


// JavaScript for exclusive toggle effect with on/off functionality
document.querySelectorAll('.box-container').forEach(container => {
    const boxTitle = container.querySelector('.box h3');
    
    boxTitle.addEventListener('click', () => {
       // Check if the clicked container is already active
       const isActive = container.classList.contains('active');
       
       // Remove the 'active' class from all containers
       document.querySelectorAll('.box-container').forEach(otherContainer => {
          otherContainer.classList.remove('active');
       });
       
       // If the clicked container wasn't active, make it active
       if (!isActive) {
          container.classList.add('active');
       }
    });
 });


 document.getElementById("pendingStudentsBtn").onclick = function() {
    window.location.href = "admin_panel.php"; // Navigate to pending students page
};

document.getElementById("potentialTutorsBtn").onclick = function() {
    window.location.href = "potential_tutors.php"; // Navigate to potential tutors page
};

 
document.addEventListener("DOMContentLoaded", function() {
    const notification = document.getElementById("admin-notification");
    if (notification) {
        setTimeout(() => {
            notification.style.display = "none";
        }, 5000);
    }
});

function validateName(input) {
   const nameError = document.getElementById('name-error');
   const regex = /^[A-Za-z\s]+$/; // Only allows letters and spaces

   if (!regex.test(input.value)) {
      nameError.style.display = 'block';
      input.setCustomValidity("Please enter letters only."); // For form submission validation
   } else {
      nameError.style.display = 'none';
      input.setCustomValidity(""); // Clear the validation error
   }
}
