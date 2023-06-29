// Au début de chaque partie, ajoutez la classe "fade-in" au conteneur des mots
document.getElementById('word-list').classList.add('fade-in');

// À la fin du jeu, retirez la classe "fade-in" et ajoutez la classe "fade-out" pour l'animation de disparition
document.getElementById('word-list').classList.remove('fade-in');
document.getElementById('word-list').classList.add('fade-out');
