const form = document.getElementById('index-form');

new BakeKit.ui.Sortable(document.getElementById('categories'), {
    handle: '.drag-handle',
    animation: 150,
    onEnd(e) {
        form['id'].value = e.item.dataset.id;
        form['oldIndex'].value = e.oldIndex + 1;
        form['newIndex'].value = e.newIndex + 1;
        form.submit();
    }
});
