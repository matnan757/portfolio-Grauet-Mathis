function openLightbox(element) {
    // 1. Récupérer les textes
    const title = element.querySelector('.product-info h3').innerText;
    const description = element.querySelector('.product-info p').innerText;
    
    // Détecter les éléments médias existants
    const videoElement = element.querySelector('video');
    const imgElement = element.querySelector('img');
    
    const mediaContainer = document.getElementById('lightboxMediaContainer');
    mediaContainer.innerHTML = ''; // Nettoyer l'ancienne image ou vidéo

    // 2. Gestion du média fluide
    if (videoElement) {
        // Au lieu de recréer une balise vide, on CLONE la vidéo existante
        // Cela conserve la mémoire tampon (le cache) déjà accumulée par le navigateur
        const clonedVideo = videoElement.cloneNode(true);
        
        // On lui applique les contrôles et les styles nécessaires pour le grand écran
        clonedVideo.setAttribute('controls', 'true');
        clonedVideo.style.maxWidth = '100%';
        clonedVideo.style.maxHeight = '60vh';
        clonedVideo.style.width = 'auto';
        clonedVideo.style.height = 'auto';
        clonedVideo.style.objectFit = 'contain';
        
        // On force la lecture immédiate du clone
        clonedVideo.play();
        
        // On l'injecte dans la modale
        mediaContainer.appendChild(clonedVideo);
    } else if (imgElement) {
        const imgSrc = imgElement.src;
        mediaContainer.innerHTML = `<img src="${imgSrc}" alt="${title}">`;
    }

    // 3. Injecter les informations textuelles
    document.getElementById('lightboxTitle').innerText = title;
    document.getElementById('lightboxDescription').innerText = description;

    // 4. Activer l'affichage de la Lightbox
    const lightbox = document.getElementById('productLightbox');
    lightbox.classList.add('active');
}

function closeLightbox() {
    const lightbox = document.getElementById('productLightbox');
    lightbox.classList.remove('active');
    
    // Coupe la vidéo de la modale quand on ferme pour couper le son
    document.getElementById('lightboxMediaContainer').innerHTML = '';
}

// Fermer aussi avec la touche Échap du clavier
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});