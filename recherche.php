<!DOCTYPE html>
<html>
<head>
<?php include 'header.php'; ?>
    <style>
        .search-form {
            margin: 20px auto;
            padding: 20px;
            width: 500px;
            border: 1px solid grey;
            border-radius: 40px;
            background-color: #595959;
            display: flex;
            align-items: center;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid grey;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #FFE937;
            color: grey;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            
        }

        .search-form button:hover {
            background-color: #E3D031;
        }       
    </style>

<body>
    <div class="wrapper">
	<?php include 'bandeau.php'; ?>
		
	<main>
        <form action="recherche_resultats.html" method="get" class="search-form">
            <input type="text" name="query" placeholder="Rechercher..." required>
            <button type="submit">Rechercher</button>
        </form>
    </main>
    <?php include 'footer.php'; ?>
    </div>
</body>
</html>
