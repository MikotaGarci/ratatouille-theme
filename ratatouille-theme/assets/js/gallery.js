jQuery(document).ready(function($) {
    // Просто добавляем класс loaded для анимации
    $('.gallery-grid').addClass('is-loaded');

    // Инициализация лайтбокса
    if ($('.gallery-lightbox').length) {
        $('.gallery-lightbox').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 2],
                tCounter: '%curr% из %total%'
            },
            image: {
                titleSrc: function(item) {
                    return item.el.find('img').attr('alt') || '';
                },
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out'
            }
        });
    }

    // Обработка фильтрации категорий
    $('.category-filter').on('click', function() {
        const filterValue = $(this).attr('data-category');
        
        $('.category-filter').removeClass('active').attr('aria-pressed', 'false');
        $(this).addClass('active').attr('aria-pressed', 'true');
        
        if (filterValue === 'all') {
            $('.gallery-item').show();
        } else {
            $('.gallery-item').hide();
            $(`.gallery-item[data-category="${filterValue}"]`).show();
        }
    });
});