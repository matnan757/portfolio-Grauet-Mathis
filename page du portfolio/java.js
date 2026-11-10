const txt = "Bienvenue sur mon portfolio";
const target = document.getElementById("typewriter");
let i = 0;

function typewriter() {
    if (i < txt.length) {
        target.innerHTML += txt.charAt(i);
        i++;
        setTimeout(typewriter, 150);
    }
}

// Lance l'effet au chargement
window.onload = typewriter;

// Mode jour / nuit
const boutonTheme = document.getElementById("theme-toggle");

function appliquerTheme(nuit) {
    document.documentElement.classList.toggle("nuit", nuit);
    document.body.classList.toggle("nuit", nuit);
    boutonTheme.setAttribute("aria-checked", nuit);
    boutonTheme.setAttribute("aria-label", nuit ? "Activer le mode jour" : "Activer le mode nuit");
}

// Reprend le choix de la dernière visite (si le navigateur le permet)
try {
    appliquerTheme(localStorage.getItem("theme") === "nuit");
} catch (e) {}

boutonTheme.addEventListener("click", function () {
    const nuit = !document.body.classList.contains("nuit");
    appliquerTheme(nuit);
    try {
        localStorage.setItem("theme", nuit ? "nuit" : "jour");
    } catch (e) {}
});

// Apparition des éléments au scroll
const observateurReveal = new IntersectionObserver(function (entrees) {
    entrees.forEach(function (entree) {
        if (!entree.isIntersecting) return;
        const el = entree.target;
        el.classList.add("visible");
        observateurReveal.unobserve(el);
        // Une fois apparu, on rend la main aux effets de survol d'origine
        el.addEventListener("transitionend", function fin(e) {
            if (e.target !== el) return; // ignore les transitions des éléments enfants
            el.classList.remove("reveal", "visible");
            el.style.transitionDelay = "";
            el.removeEventListener("transitionend", fin);
        });
    });
}, { threshold: 0.15 });

document.querySelectorAll(".titre, .intro-text, .competences-lead, .skill-card, .bts-option, .projet-card, .veille-explain, .contact-card, .btn-cv").forEach(function (el, i) {
    el.classList.add("reveal");
    el.style.transitionDelay = (i % 4) * 0.1 + "s"; // décalage entre les éléments voisins
    observateurReveal.observe(el);
});

// Timeline : la ligne se dessine puis les étapes apparaissent une par une
const timeline = document.querySelector(".timeline");
if (timeline) {
    timeline.classList.add("anime");
    const observateurTimeline = new IntersectionObserver(function (entrees) {
        if (entrees[0].isIntersecting) {
            timeline.classList.add("visible");
            observateurTimeline.disconnect();
        }
    }, { threshold: 0.4 });
    observateurTimeline.observe(timeline);
}

// Lien actif dans la sidebar selon la section affichée
const liensMenu = document.querySelectorAll(".sidebar-text a");
const observateurMenu = new IntersectionObserver(function (entrees) {
    entrees.forEach(function (entree) {
        if (!entree.isIntersecting) return;
        const id = entree.target.classList.contains("hero") ? "top" : entree.target.id;
        liensMenu.forEach(function (lien) {
            lien.classList.toggle("actif", lien.getAttribute("href") === "#" + id);
        });
    });
}, { rootMargin: "-45% 0px -50% 0px" }); // déclenche quand la section passe au milieu de l'écran

document.querySelectorAll(".hero, section[id]").forEach(function (section) {
    observateurMenu.observe(section);
});
