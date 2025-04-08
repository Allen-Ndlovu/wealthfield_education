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






function addQuestion() {
   const container = document.getElementById('questions-container');
   const questionDiv = document.createElement('div');
   questionDiv.className = 'question';
   questionDiv.innerHTML = `
       <label>Question: <input type="text" name="questions[]" required></label><br>
       <label>Option A: <input type="text" name="option_a[]" required></label><br>
       <label>Option B: <input type="text" name="option_b[]" required></label><br>
       <label>Option C: <input type="text" name="option_c[]" required></label><br>
       <label>Option D: <input type="text" name="option_d[]" required></label><br>
       <label>Correct Answer: 
           <select name="correct_option[]" required>
               <option value="A">A</option>
               <option value="B">B</option>
               <option value="C">C</option>
               <option value="D">D</option>
           </select>
       </label>
       <button type="button" onclick="removeQuestion(this)">Remove</button>
       <hr>
   `;
   container.appendChild(questionDiv);
}

function removeQuestion(button) {
   const questionDiv = button.parentElement;
   questionDiv.remove();
}

function addMcqQuestion() {
   const mcqContainer = document.getElementById('mcq-container');
   const newMcq = `
       <div class="mcq-question">
           <label>Question:</label><br>
           <input type="text" name="mcq_questions[]" required><br>
           <label>Option A:</label><br>
           <input type="text" name="mcq_option_a[]" required><br>
           <label>Option B:</label><br>
           <input type="text" name="mcq_option_b[]" required><br>
           <label>Option C:</label><br>
           <input type="text" name="mcq_option_c[]" required><br>
           <label>Option D:</label><br>
           <input type="text" name="mcq_option_d[]" required><br>
           <label>Correct Answer:</label>
           <select name="mcq_correct_option[]" required>
               <option value="A">A</option>
               <option value="B">B</option>
               <option value="C">C</option>
               <option value="D">D</option>
           </select>
           <button type="button" onclick="removeQuestion(this)">Remove Question</button>
       </div>
   `;
   mcqContainer.insertAdjacentHTML('beforeend', newMcq);
}

function addShortQuestion() {
   const shortContainer = document.getElementById('short-container');
   const newShort = `
       <div class="short-question">
           <label>Question:</label><br>
           <textarea name="short_questions[]" rows="3" cols="50" required></textarea><br>
           <label>Solution:</label><br>
           <textarea name="short_solutions[]" rows="3" cols="50" required></textarea><br>
           <button type="button" onclick="removeQuestion(this)">Remove Question</button>
       </div>
   `;
   shortContainer.insertAdjacentHTML('beforeend', newShort);
}

function addLongQuestion() {
   const longContainer = document.getElementById('long-container');
   const newLong = `
       <div class="long-question">
           <label>Question:</label><br>
           <textarea name="long_questions[]" rows="4" cols="50" required></textarea><br>
           <label>Solution:</label><br>
           <textarea name="long_solutions[]" rows="4" cols="50" required></textarea><br>
           <button type="button" onclick="removeQuestion(this)">Remove Question</button>
       </div>
   `;
   longContainer.insertAdjacentHTML('beforeend', newLong);
}

function removeQuestion(button) {
   button.parentElement.remove();
}



