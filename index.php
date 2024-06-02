<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
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
    .event-image {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 20px auto;
    }
    .carousel {
        position: relative;
        width: 80%; 
        max-width: 900px; 
        margin: auto;
        overflow: hidden;
    }
    .carousel img {
        width: 100%;
        display: none;
    }
    .carousel img.active {
        display: block;
    }
    .carousel-controls {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }
    .carousel-button {
        background-color: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        font-size: 18px;
        padding: 10px;
        cursor: pointer;
    }
</style>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
        <main>
            <div class="welcome-section">
                <p>Bienvenue sur le site de Medicare!!</p>
                <p>Nous sommes dédiés à fournir les meilleurs soins de santé à notre communauté. Explorez notre site pour en savoir plus sur nos services et nos différents médecins.</p>
                <p>N'hésitez pas à prendre rendez-vous avec nos experts et à les contacter pour toutes questions</p> 
            </div>

            <div class="event-section">
                <h2>L'Évènement de la semaine</h2>
                <p>Cette semaine, ne manquez pas notre porte ouverte le samedi pour découvrir nos services et rencontrer notre équipe. Nous espérons vous voir nombreux !</p>
                <img src="event.jpg" alt="Health Fair Event" class="event-image">
            </div>

            <div class="carousel">
                <img src="carroussel1.jpg" class="active" alt="Carrousel Image 1">
                <img src="carroussel2.jpg" alt="Carrousel Image 2">
                <img src="carroussel3.jpg" alt="Carrousel Image 3">
                <img src="carroussel4.jpg" alt="Carrousel Image 4">
                <div class="carousel-controls">
                    <button class="carousel-button prev" onclick="changeSlide(-1)">&#10094;</button>
                    <button class="carousel-button next" onclick="changeSlide(1)">&#10095;</button>
                </div>
            </div>

        </main>
        
        <?php include 'footer.php'; ?>
    </div>

    <script>
        let slideIndex = 0;
        const slides = document.querySelectorAll('.carousel img');
        const changeSlide = (n) => {
            slides[slideIndex].classList.remove('active');
            slideIndex = (slideIndex + n + slides.length) % slides.length;
            slides[slideIndex].classList.add('active');
        }
        const autoSlide = () => {
            changeSlide(1);
        }
        setInterval(autoSlide, 3000); // Change image every 3 seconds
    </script>
</body>
</html>
