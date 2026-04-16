@php
    $dashboardGallery = $galleryImages ?? [];
@endphp

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>All Photos</h3>
        <span style="font-size: 0.85rem; color: #7a4d63;">{{ count($dashboardGallery) }} images</span>
    </div>
    <div class="card-body">
        @if(count($dashboardGallery) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.9rem;">
                @foreach($dashboardGallery as $image)
                    <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 6px 16px rgba(219, 39, 119, 0.15); background: #fff;">
                        <img src="{{ asset($image) }}" alt="Gallery Photo {{ $loop->iteration }}" loading="lazy" style="width: 100%; height: 140px; object-fit: cover; display: block;">
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #7a4d63;">No images found in public/images.</p>
        @endif
    </div>
</div>
