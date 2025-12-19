<div class="col-12">
    <div class="text-center py-5">
        <i class="lni lni-package" style="font-size: 48px; color: #ddd;"></i>
        <h4 class="mt-3">Товары не найдены</h4>
        <p class="text-muted">
            @if(isset($selectedCategory) && $selectedCategory)
                В категории "{{ $selectedCategory->name }}" пока нет товаров.
            @else
                В каталоге пока нет товаров.
            @endif
        </p>
        <a href="{{ route('shop.products') }}" class="btn btn-primary">Посмотреть все товары</a>
    </div>
</div>