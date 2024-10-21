<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REC Catlog</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .header-1 {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            width: 100%;
            padding: 5px;
            background-color: var(--bg-color);
            color: var(--text-color);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-top {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theme-button {
            display: block;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: transparent;
            border-radius: 50%;
            transition: .3s ease;   
        }

        .theme-button:hover {
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header-1">
        <div class="header-top">
            <h1 class="text-2xl font-bold">REC Catalog</h1>
            <button onclick="toggleTheme()" class="theme-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
            </button>
        </div>
    </header>
    <script>
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
    </script>