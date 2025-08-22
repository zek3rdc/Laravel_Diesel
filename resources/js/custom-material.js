document.addEventListener('livewire:load', function () {
    Livewire.on('confirming-logout-other-browser-sessions', () => {
        var myModal = new bootstrap.Modal(document.getElementById('confirmingLogoutModal'))
        myModal.show();
    });

    Livewire.on('confirming-delete-user', () => {
        var myModal = new bootstrap.Modal(document.getElementById('confirmingUserDeletionModal'))
        myModal.show();
    });

    window.addEventListener('close-modal', event => {
        var myModalEl = document.getElementById(event.detail.id);
        var modal = bootstrap.Modal.getInstance(myModalEl)
        if (modal) {
            modal.hide();
        }
    });
});
