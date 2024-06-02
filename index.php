<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 1em 0;
        }
        .welcome-section {
            padding: 2em;
            text-align: center;
        }
        .event-section {
            background-color: #f8f9fa;
            padding: 2em;
            text-align: center;
        }
        .carousel {
            display: flex;
            overflow: hidden;
            width: 80%;
            margin: 2em auto;
        }
        .carousel img {
            width: 100%;
            transition: transform 0.5s ease;
        }
        .carousel-buttons {
            text-align: center;
            margin-top: 1em;
        }
        .carousel-button {
            cursor: pointer;
            padding: 0.5em 1em;
            margin: 0 0.5em;
            background-color: #649FCB;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>


<body>
    <div class="wrapper">
	<?php include 'bandeau.php'; ?>
		
	<main>
    <div class="welcome-section">
        <p>Bienvenue sur le site de Medicare. Nous sommes dédiés à fournir les meilleurs soins de santé à notre communauté. Explorez notre site pour en savoir plus sur nos services et événements.</p>
    </div>

    <div class="event-section">
        <h2>L'Évènement de la semaine</h2>
        <p>Cette semaine, ne manquez pas notre porte ouverte le samedi pour découvrir nos services et rencontrer notre équipe. Nous espérons vous voir nombreux !</p>
    </div>

    <div class="carousel">
        <img src="carroussel1.jpg" alt="Spécialiste 1">
        <img src="carroussel2.jpg" alt="Spécialiste 2">
        <img src="carroussel3.jpg" alt="Spécialiste 3">
    </div>

    <div class="carousel-buttons">
        <button class="carousel-button" onclick="prevSlide()">Précédent</button>
        <button class="carousel-button" onclick="nextSlide()">Suivant</button>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel img');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(${(i - index) * 100}%)`;
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        showSlide(currentSlide);
    </script>
	</main>
	<?php include 'footer.php'; ?>
    </div>
</body>
</html>