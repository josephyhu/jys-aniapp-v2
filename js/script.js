const animeButton = document.querySelector('.anime-button');
const currentAnimeButton = document.querySelector('.current-anime-button');
const completedAnimeButton = document.querySelector('.completed-anime-button');
const planningAnimeButton = document.querySelector('.planning-anime-button');
const pausedAnimeButton = document.querySelector('.paused-anime-button');
const droppedAnimeButton = document.querySelector('.dropped-anime-button');
const repeatingAnimeButton = document.querySelector('.repeating-anime-button');
const mangaButton = document.querySelector('.manga-button');
const currentMangaButton = document.querySelector('.current-manga-button');
const completedMangaButton = document.querySelector('.completed-manga-button');
const planningMangaButton = document.querySelector('.planning-manga-button');
const pausedMangaButton = document.querySelector('.paused-manga-button');
const droppedMangaButton = document.querySelector('.dropped-manga-button');
const repeatingMangaButton = document.querySelector('.repeating-manga-button');

let anime = document.querySelector('.anime');
let currentAnime = document.querySelector('.current-anime');
let completedAnime = document.querySelector('.completed-anime');
let planningAnime = document.querySelector('.planning-anime');
let pausedAnime = document.querySelector('.paused-anime');
let droppedAnime = document.querySelector('.dropped-anime');
let repeatingAnime = document.querySelector('.repeating-anime');
let manga = document.querySelector('.manga');
let currentManga = document.querySelector('.current-manga');
let completedManga = document.querySelector('.completed-manga');
let planningManga = document.querySelector('.planning-manga');
let pausedManga = document.querySelector('.paused-manga');
let droppedManga = document.querySelector('.dropped-manga');
let repeatingManga = document.querySelector('.repeating-manga');

anime.style.display = 'none';
currentAnime.style.display = 'none';
completedAnime.style.display = 'none';
planningAnime.style.display = 'none';
pausedAnime.style.display = 'none';
droppedAnime.style.display = 'none';
repeatingAnime.style.display = 'none';
manga.style.display = 'none';
currentManga.style.display = 'none';
completedManga.style.display = 'none';
planningManga.style.display = 'none';
pausedManga.style.display = 'none';
droppedManga.style.display = 'none';
repeatingManga.style.display = 'none';

animeButton.addEventListener('click', () => {
    if (anime.style.display === 'none') {
        anime.style.display = 'block';
    } else {
        anime.style.display = 'none';
    }
    manga.style.display = 'none';
});

currentAnimeButton.addEventListener('click', () => {
    if (currentAnime.style.display === 'none') {
        currentAnime.style.display = 'block';
    } else {
        currentAnime.style.display = 'none';
    }
    completedAnime.style.display = 'none';
    planningAnime.style.display = 'none';
    pausedAnime.style.display = 'none';
    droppedAnime.style.display = 'none';
    repeatingAnime.style.display = 'none';
});

completedAnimeButton.addEventListener('click', () => {
    currentAnime.style.display = 'none';
    if (completedAnime.style.display === 'none') {
        completedAnime.style.display = 'block';
    } else {
        completedAnime.style.display = 'none';
    }
    planningAnime.style.display = 'none';
    pausedAnime.style.display = 'none';
    droppedAnime.style.display = 'none';
    repeatingAnime.style.display = 'none';
});

planningAnimeButton.addEventListener('click', () => {
    currentAnime.style.display = 'none';
    completedAnime.style.display = 'none';
    if (planningAnime.style.display === 'none') {
        planningAnime.style.display = 'block';
    } else {
        planningAnime.style.display = 'none';
    }
    pausedAnime.style.display = 'none';
    droppedAnime.style.display = 'none';
    repeatingAnime.style.display = 'none';
});

pausedAnimeButton.addEventListener('click', () => {
    currentAnime.style.display = 'none';
    completedAnime.style.display = 'none';
    planningAnime.style.display = 'none';
    if (pauseedAnime.style.display === 'none') {
        pausedAnime.style.display = 'block';
    } else {
        pausedAnime.style.display = 'none';
    }
    droppedAnime.style.display = 'none';
    repeatingAnime.style.display = 'none';
});

droppedAnimeButton.addEventListener('click', () => {
    currentAnime.style.display = 'none';
    completedAnime.style.display = 'none';
    planningAnime.style.display = 'none';
    pausedAnime.style.display = 'none';
    if (droppedAnime.style.display === 'none') {
        droppedAnime.style.display = 'block';
    } else {
        droppedAnime.style.display = 'none';
    }
    repeatingAnime.style.display = 'none';
});

repeatingAnimeButton.addEventListener('click', () => {
    currentAnime.style.display = 'none';
    completedAnime.style.display = 'none';
    planningAnime.style.display = 'none';
    pausedAnime.style.display = 'none';
    droppedAnime.style.display = 'none';
    if (repeatingAnime.style.display === 'none') {
        repeatingAnime.style.display = 'block';
    } else {
        repeatingAnime.style.display = 'none';
    }
});

mangaButton.addEventListener('click', () => {
    anime.style.display = 'none';
    if (manga.style.display === 'none') {
        manga.style.display = 'block';
    } else {
        manga.style.display = 'none';
    }
});

currentMangaButton.addEventListener('click', () => {
    if (currentManga.style.display === 'none') {
        currentManga.style.display = 'block';
    } else {
        currentManga.style.display = 'none';
    }
    completedManga.style.display = 'none';
    planningManga.style.display = 'none';
    pausedManga.style.display = 'none';
    droppedManga.style.display = 'none';
    repeatingManga.style.display = 'none';
});

completedMangaButton.addEventListener('click', () => {
    currentManga.style.display = 'none';
    if (completedManga.style.display === 'none') {
        completedManga.style.display = 'block';
    } else {
        completedManga.style.display = 'none';
    }
    planningManga.style.display = 'none';
    pausedManga.style.display = 'none';
    droppedManga.style.display = 'none';
    repeatingManga.style.display = 'none';
});

planningMangaButton.addEventListener('click', () => {
    currentManga.style.display = 'none';
    completedManga.style.display = 'none';
    if (planningManga.style.display === 'none') {
        planningManga.style.display = 'block';
    } else {
        planningManga.style.display = 'none';
    }
    pausedManga.style.display = 'none';
    droppedManga.style.display = 'none';
    repeatingManga.style.display = 'none';
});

pausedMangaButton.addEventListener('click', () => {
    currentManga.style.display = 'none';
    completedManga.style.display = 'none';
    planningManga.style.display = 'none';
    if (pausedManga.style.display === 'none') {
        pausedManga.style.display = 'block';
    } else {
        pausedManga.style.display = 'none';
    }
    droppedManga.style.display = 'none';
    repeatingManga.style.display = 'none';
});

droppedMangaButton.addEventListener('click', () => {
    currentManga.style.display = 'none';
    completedManga.style.display = 'none';
    planningManga.style.display = 'none';
    pausedManga.style.display = 'none';
    if (droppedManga.style.display === 'none') {
        droppedManga.style.display = 'block';
    } else {
        droppedManga.style.display = 'none';
    }
    repeatingManga.style.display = 'none';
});

repeatingMangaButton.addEventListener('click', () => {
    currentManga.style.display = 'none';
    completedManga.style.display = 'none';
    planningManga.style.display = 'none';
    pausedManga.style.display = 'none';
    droppedManga.style.display = 'none';
    if (repeatingManga.style.display === 'none') {
        repeatingManga.style.display = 'block';
    } else {
        repeatingManga.style.display = 'none';
    }
});