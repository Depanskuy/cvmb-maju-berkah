<section class="stats">
    <div class="container-public">
        <div class="stats__grid">

            <div class="stat-item">
                <span class="material-symbols-outlined">group</span>
                <div class="stat-item__value">500+</div>
                <div class="stat-item__label">Mitra Bisnis Aktif</div>
            </div>

            <div class="stat-item">
                <span class="material-symbols-outlined">schedule</span>
                <div class="stat-item__value">10+</div>
                <div class="stat-item__label">Tahun Pengalaman</div>
            </div>

            <div class="stat-item">
                <span class="material-symbols-outlined">location_on</span>
                <div class="stat-item__value">15</div>
                <div class="stat-item__label">Kota Jangkauan</div>
            </div>

            <div class="stat-item">
                <span class="material-symbols-outlined">work</span>
                <div class="stat-item__value">
                    {{ \App\Models\Job::publiclyVisible()->count() }}
                </div>
                <div class="stat-item__label">Lowongan Aktif</div>
            </div>

        </div>
    </div>
</section>