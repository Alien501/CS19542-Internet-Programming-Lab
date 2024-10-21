document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        const title = form.querySelector('input[name="title"]').value;
        const course = form.querySelector('input[name="course"]').value;
        const file = form.querySelector('input[name="file"]').files[0];
        
        if (!title || !course || !file) {
            alert('All fields must be filled out and a file selected!');
            event.preventDefault();
        }
    });
});


function toggleTheme() {
    document.body.classList.toggle('dark-theme');
    const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', theme);
    document.cookie = `theme=${theme};path=/;max-age=31536000`;
}

if (document.cookie.includes('theme=dark')) {
    document.body.classList.add('dark-theme');
}

function filterUploads() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const uploads = document.querySelectorAll('.card');

    uploads.forEach(upload => {
        const title = upload.querySelector('h4').textContent.toLowerCase();
        if (title.includes(searchInput)) {
            upload.style.display = 'block';
        } else {
            upload.style.display = 'none';
        }
    });
}