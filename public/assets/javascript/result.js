const result = {
    init: function() {
        // optional chaining: the arrows for consistency are not displayed on qualification result table
        document.getElementById('consistency-sort-up')?.addEventListener('click', result.sortRaceConsistencyUp);
        document.getElementById('consistency-sort-down')?.addEventListener('click', result.sortRaceConsistencyDown);
        document.getElementById('position-sort-up').addEventListener('click', result.sortPositionUp);
        document.getElementById('position-sort-down').addEventListener('click', result.sortPositionDown);
        result.putColorsForTopThreeResults();
    },

    sortRaceConsistencyUp: function() {
        const tbody = document.querySelector('tbody');
        [...tbody.children]
        .sort((a, b) => a.getAttribute('data-consistency') < b.getAttribute('data-consistency') ? 1 : -1)
        .forEach(node => tbody.appendChild(node))
    },

    sortRaceConsistencyDown: function() {
        const tbody = document.querySelector('tbody');
        [...tbody.children]
        .sort((a, b) => a.getAttribute('data-consistency') < b.getAttribute('data-consistency') ? -1 : 1)
        .forEach(node => tbody.appendChild(node))
    },

    sortPositionUp: function() {
        const tbody = document.querySelector('tbody');
        [...tbody.children]
        .sort((a, b) => parseInt(a.getAttribute('data-position')) < parseInt(b.getAttribute('data-position')) ? 1 : -1)
        .forEach(node => tbody.appendChild(node))
    },

    sortPositionDown: function() {
        const tbody = document.querySelector('tbody');
        [...tbody.children]
        .sort((a, b) => parseInt(a.getAttribute('data-position')) < parseInt(b.getAttribute('data-position')) ? -1 : 1)
        .forEach(node => tbody.appendChild(node))
    },

    putColorsForTopThreeResults: function() {
        const rows = Array.from(document.querySelectorAll('tr'));
        rows.forEach(row => {
            const position = row.getAttribute('data-position');
            switch(position) {
                case "1": row.style.backgroundColor = "#F1E5AC"; break;
                case "2": row.style.backgroundColor = "#d8d8d8"; break;
                case "3": row.style.backgroundColor = "#DAAA5E"; break;
            }
        })
    }
}

document.addEventListener('DOMContentLoaded', result.init)