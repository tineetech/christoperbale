@extends('layouts.dashboard')

@section('title', 'Wishlist — CHRISBALE')

@section('dashboard-content')
                    <div class="dash-panel" id="panel-wishlist">
                        <div class="dash-section-head" style="margin-bottom:24px;">
                            <h2 class="dash-section-title">Wishlist Saya</h2>
                            <span style="font-size:12px;color:var(--ink-muted);">5 item tersimpan</span>
                        </div>
                        <div class="grid-prod" style="grid-template-columns:repeat(3,1fr);">
                            <article class="prod-card" style="position:relative;">
                                <span class="badge-tag badge-new">Baru</span>
                                <button class="wish-remove-btn" title="Hapus dari wishlist"
                                    onclick="removeWish(this)">✕</button>
                                <div class="prod-img-wrap">
                                    <img src="https://images.unsplash.com/photo-1584735175315-9d5df23be1f2?w=600&q=80&auto=format&fit=crop"
                                        alt="Gold Edition" loading="lazy">
                                    <div class="prod-overlay"><button class="add-btn">+ Keranjang</button></div>
                                </div>
                                <div class="prod-info">
                                    <h3>Signature Gold Edition</h3>
                                    <div class="price-row"><span class="current">Rp4.489.000</span></div>
                                </div>
                            </article>
                            <article class="prod-card" style="position:relative;">
                                <button class="wish-remove-btn" title="Hapus dari wishlist"
                                    onclick="removeWish(this)">✕</button>
                                <div class="prod-img-wrap">
                                    <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600&q=80&auto=format&fit=crop"
                                        alt="Midnight Black" loading="lazy">
                                    <div class="prod-overlay"><button class="add-btn">+ Keranjang</button></div>
                                </div>
                                <div class="prod-info">
                                    <h3>Midnight Black</h3>
                                    <div class="price-row"><span class="current">Rp3.889.000</span></div>
                                </div>
                            </article>
                            <article class="prod-card" style="position:relative;">
                                <span class="badge-tag badge-sale">-25%</span>
                                <button class="wish-remove-btn" title="Hapus dari wishlist"
                                    onclick="removeWish(this)">✕</button>
                                <div class="prod-img-wrap">
                                    <img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&q=80&auto=format&fit=crop"
                                        alt="Canvas High Top" loading="lazy">
                                    <div class="prod-overlay"><button class="add-btn">+ Keranjang</button></div>
                                </div>
                                <div class="prod-info">
                                    <h3>Canvas High Top</h3>
                                    <div class="price-row">
                                        <span class="current">Rp1.890.000</span>
                                        <span class="old">Rp2.520.000</span>
                                    </div>
                                </div>
                            </article>
                            <article class="prod-card" style="position:relative;">
                                <button class="wish-remove-btn" title="Hapus dari wishlist"
                                    onclick="removeWish(this)">✕</button>
                                <div class="prod-img-wrap">
                                    <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=600&q=80&auto=format&fit=crop"
                                        alt="Cream Canvas" loading="lazy">
                                    <div class="prod-overlay"><button class="add-btn">+ Keranjang</button></div>
                                </div>
                                <div class="prod-info">
                                    <h3>Cream Canvas</h3>
                                    <div class="price-row"><span class="current">Rp2.089.000</span></div>
                                </div>
                            </article>
                            <article class="prod-card" style="position:relative;">
                                <button class="wish-remove-btn" title="Hapus dari wishlist"
                                    onclick="removeWish(this)">✕</button>
                                <div class="prod-img-wrap">
                                    <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600&q=80&auto=format&fit=crop"
                                        alt="Tan Chelsea" loading="lazy">
                                    <div class="prod-overlay"><button class="add-btn">+ Keranjang</button></div>
                                </div>
                                <div class="prod-info">
                                    <h3>Tan Chelsea Boot</h3>
                                    <div class="price-row"><span class="current">Rp2.839.000</span></div>
                                </div>
                            </article>
                        </div>
                    </div><!-- /panel-wishlist -->
@endsection