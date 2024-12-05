function showNotification(type, title, text) {
    Swal.fire({
        icon: type,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 3000
    });
}
