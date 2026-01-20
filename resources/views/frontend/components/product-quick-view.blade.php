@php
    $firstVariant = $product->variants->first();
    $price = $firstVariant?->prices->first();
    $thumbnail = $product->thumbnail?->getUrl('large') ?? asset('brancy/images/shop/1.webp');
    $productName = $product->translateAttribute('name');
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ $productName }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="product-quick-view-image">
                <img src="{{ $thumbnail }}" alt="{{ $productName }}" class="img-fluid">
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-quick-view-content">
                @if($product->collections->isNotEmpty())
                    <p class="text-muted mb-2">{{ $product->collections->first()->translateAttribute('name') }}</p>
                @endif

                <h4 class="mb-3">{{ $productName }}</h4>

                @if($price)
                    <h3 class="text-primary mb-4">{{ $price->price->formatted }}</h3>
                @endif

                @if($product->description)
                    <p class="mb-4">{!! nl2br(e(Str::limit($product->translateAttribute('description'), 150))) !!}</p>
                @endif

                @if($product->variants->count() > 1)
                    <div class="mb-4">
                        @php
                            $options = [];
                            foreach($product->variants as $variant) {
                                foreach($variant->values as $value) {
                                    $optionName = $value->option->name ?? 'Option';
                                    if (!isset($options[$optionName])) {
                                        $options[$optionName] = [];
                                    }
                                    $options[$optionName][] = $value->name ?? '';
                                }
                            }
                            foreach($options as &$values) {
                                $values = array_unique($values);
                            }
                        @endphp

                        @foreach($options as $optionName => $values)
                            <div class="mb-3">
                                <label class="form-label"><strong>{{ $optionName }}</strong></label>
                                <select class="form-select form-select-sm">
                                    <option value="">Select {{ $optionName }}</option>
                                    @foreach($values as $value)
                                        @if($value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex align-items-center gap-3 mb-4">
                    <label class="form-label mb-0"><strong>Quantity:</strong></label>
                    <div class="pro-qty">
                        <button type="button" class="dec qtybtn">-</button>
                        <input type="text" class="quantity-input" value="1" min="1" readonly style="width: 50px; text-align: center;">
                        <button type="button" class="inc qtybtn">+</button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg add-to-cart-quickview" data-variant-id="{{ $firstVariant?->id }}" data-product-name="{{ $productName }}">
                        <i class="fa fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                    <a href="{{ route('shop.product.show', $product->defaultUrl?->slug ?? $product->id) }}" class="btn btn-outline-secondary">
                        View Full Details
                    </a>
                </div>

                @if($firstVariant)
                    <div class="mt-4 pt-4 border-top">
                        <p class="mb-1"><small class="text-muted"><strong>SKU:</strong> {{ $firstVariant->sku }}</small></p>
                        @if($product->collections->isNotEmpty())
                            <p class="mb-0">
                                <small class="text-muted"><strong>Category:</strong>
                                    @foreach($product->collections as $collection)
                                        {{ $collection->translateAttribute('name') }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </small>
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Quantity controls
    $('.qtybtn').on('click', function() {
        const $input = $('.quantity-input');
        let qty = parseInt($input.val()) || 1;

        if ($(this).hasClass('inc')) {
            qty++;
        } else if ($(this).hasClass('dec') && qty > 1) {
            qty--;
        }

        $input.val(qty);
    });

    // Add to cart from quick view
    $('.add-to-cart-quickview').on('click', function() {
        const $btn = $(this);
        const variantId = $btn.data('variant-id');
        const productName = $btn.data('product-name');
        const quantity = parseInt($('.quantity-input').val()) || 1;

        if (!variantId) {
            alert('Product variant not available');
            return;
        }

        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin me-2"></i>Adding...');

        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                variant_id: variantId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    $('.cart-count').text(response.cart_count).show();

                    // Close quick view modal first
                    $('#quickViewModal').modal('hide');
                    
                    // Show add to cart success modal
                    @php
                        $quickViewImage = $product->thumbnail ? $product->thumbnail->getUrl('medium') : asset('brancy/images/shop/1.webp');
                        $quickViewUrl = route('shop.product.show', $product->defaultUrl?->slug ?? $product->id);
                    @endphp
                    const productImage = '{{ $quickViewImage }}';
                    const productUrl = '{{ $quickViewUrl }}';
                    
                    // Small delay to ensure quick view modal is closed
                    setTimeout(function() {
                        showAddToCartModal(productName, productImage, productUrl);
                    }, 300);

                    // Reset button
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fa fa-shopping-cart me-2"></i>Add to Cart');
                }
            },
            error: function() {
                alert('Error adding product to cart');
                $btn.prop('disabled', false);
                $btn.html('<i class="fa fa-shopping-cart me-2"></i>Add to Cart');
            }
        });
    });
});
</script>
