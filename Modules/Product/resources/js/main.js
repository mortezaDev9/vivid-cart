document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.querySelector('#product-detail-main-image');
    const thumbnails = document.querySelectorAll('#product-detail-thumbnail');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            mainImage.src = this.dataset.imageSource;

            thumbnails.forEach(img => img.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });
});
