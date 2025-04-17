{
    "use strict";

    new Sortable(document.getElementById('categories'), {
        handle: '.drag-handle', // handle's class
        animation: 150,
        onEnd: sort
    });

    function sort(e) {
        let form = document.getElementById('index-form');
        form['id'].value = e.item.dataset.id;
        form['oldIndex'].value = (e.oldIndex + 1);
        form['newIndex'].value = (e.newIndex + 1);

        form.submit();
    }

}