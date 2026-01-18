document.addEventListener('DOMContentLoaded', () => {

    const block = document.querySelector('[data-team-block]');
    if (!block) return;

    const results = block.querySelector('.team-results');
    const buttons = block.querySelectorAll('[data-genre]');

    function loadMembers(genre = '') {

        let url = '/wp-json/team/v1/members';
        if (genre) {
            url += `?genre=${genre}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {

                results.innerHTML = data.map(member => `
                    <div class="team-card">
                        ${member.image ? `<img src="${member.image}" />` : ''}
                        <h3>${member.name}</h3>
                        <p>${member.position}</p>
                        <small>${member.genres.join(', ')}</small>
                    </div>
                `).join('');

            });
    }

    loadMembers();

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            loadMembers(button.dataset.genre);
        });
    });

});
