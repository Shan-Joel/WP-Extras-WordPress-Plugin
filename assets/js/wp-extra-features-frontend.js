const list = document.querySelector('.listview');
const grid = document.querySelector('.gridview');
const wrapper = document.querySelector('.wp-extra-projects-wrapper');
const entry = document.querySelector('.entry-content');

entry.classList.add('entry-grid');

list.addEventListener('click', () => {
   wrapper.classList.remove('display-grid');
   wrapper.classList.add('display-list');
   entry.classList.add('entry-list');
   entry.classList.remove('entry-grid');
});

grid.addEventListener('click', () => {
   wrapper.classList.add('display-grid');
   wrapper.classList.remove('display-list');
   entry.classList.add('entry-grid');
   entry.classList.remove('entry-list');
});
