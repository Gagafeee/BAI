document.addEventListener('DOMContentLoaded', function () {

    const selectTri = document.getElementById('trie');

    const catalogue = document.querySelector('.catalogue');

    selectTri.addEventListener('change', function () {

        const tri = selectTri.value;

        fetch('actions/tri_ideas.php?tri=' + tri)

            .then(function (response) {
                return response.text();
            })

            .then(function (html) {

                catalogue.innerHTML = html;

            })

            .catch(function (error) {
                console.error('Erreur lors du tri :', error);
            });

    });

});
