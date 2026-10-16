@php
    $featuredJobs = \App\Models\JobListing::publiclyVisible()->latest()->take(3)->get();
@endphp

<section class="featured-jobs">
    <div class="container-public">

        <div class="featured-jobs__header">
            <div class="featured-jobs__header-text">
                <span class="section-tag">Karir</span>
                <h2 class="section-title">Lowongan Terbaru</h2>
                <p class="section-desc">Bergabunglah dengan tim kami dan tumbuh bersama CV Maju Sukses</p>
            </div>
            <a href="{{ route('careers.index') }}" class="section-link">
                <span>Lihat Semua</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        @if ($featuredJobs->isEmpty())
            <div class="empty-state">
                <span class="material-symbols-outlined">work_off</span>
                <p>Belum ada lowongan aktif saat ini.</p>
            </div>
        @else
            <div class="featured-jobs__grid">

                @foreach ($featuredJobs as $job)
                    <a href="{{ route('careers.show', $job) }}" class="job-card">

                        <div class="job-card__badges">
                            <span class="job-card__badge">
                                <span class="job-card__badge-dot"></span>
                                {{ $job->employment_type_label }}
                            </span>
                        </div>

                        <h3 class="job-card__title">{{ $job->title }}</h3>
                        <span class="job-card__category">{{ $job->category }}</span>

                        <div class="job-card__meta">
                            <span class="job-card__meta-item">
                                <span class="material-symbols-outlined">location_on</span>
                                {{ $job->location }}
                            </span>
                            <span class="job-card__meta-item job-card__meta-item--deadline">
                                <span class="material-symbols-outlined">hourglass_top</span>
                                Batas: {{ $job->deadline->format('d M Y') }}
                            </span>
                        </div>

                        <p class="job-card__desc">
                            {{ \Illuminate\Support\Str::limit($job->description, 120) }}
                        </p>

                        <div class="job-card__footer">
                            <span class="job-card__action">
                                Lihat Detail
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </span>
                        </div>

                    </a>
                @endforeach

            </div>
        @endif

    </div>
</section>