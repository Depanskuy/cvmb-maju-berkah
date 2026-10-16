{{-- resources/views/components/seeker-feedback-modal.blade.php --}}
<div id="seekerFeedbackModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xl w-full max-w-lg overflow-hidden animate-fadeIn">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">reviews</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg font-display text-on-surface">Ulasan &amp; Masukan Layanan</h3>
                    <p class="text-xs text-on-surface-variant">Suara Anda membantu kami meningkatkan kualitas rekrutmen.</p>
                </div>
            </div>
            <button type="button" onclick="closeFeedbackModal()" class="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('seeker.feedback.store') }}">
            @csrf

            <div class="p-6 space-y-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Kategori Masukan <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="Proses Rekrutmen">Proses Rekrutmen &amp; Seleksi</option>
                        <option value="Pelayanan Tim HR">Pelayanan Tim HR &amp; Interviewer</option>
                        <option value="Portal Website Pelamar">Kemudahan Portal Website Pelamar</option>
                        <option value="Saran Fasilitas & Pelatihan">Saran Fasilitas &amp; Pelatihan</option>
                        <option value="Lainnya">Lainnya / Kritik Membangun</option>
                    </select>
                </div>

                {{-- Rating Bintang --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Tingkat Kepuasan (Rating) <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-surface-container-low">
                        <input type="hidden" name="rating" id="feedbackRatingInput" value="5">
                        <div class="flex items-center gap-1 text-amber-400" id="starRatingContainer">
                            @for ($s = 1; $s <= 5; $s++)
                                <button type="button" onclick="setFeedbackRating({{ $s }})" class="p-1 hover:scale-110 transition-transform cursor-pointer">
                                    <span class="material-symbols-outlined text-[28px] star-icon" data-star="{{ $s }}">star</span>
                                </button>
                            @endfor
                        </div>
                        <span id="ratingTextLabel" class="text-xs font-bold text-on-surface ml-2">5 / 5 (Sangat Puas)</span>
                    </div>
                </div>

                {{-- Pesan Masukan --}}
                <div>
                    <label class="block text-xs font-semibold text-on-surface uppercase tracking-wider mb-1.5">
                        Pesan &amp; Saran Perbaikan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="message" rows="4" required minlength="5" maxlength="1000"
                              placeholder="Bagikan pengalaman Anda selama proses seleksi di CV Maju Sukses, apa yang sudah baik dan apa yang perlu ditingkatkan..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/30 text-xs text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
            </div>

            <div class="p-4 px-6 border-t border-outline-variant/20 bg-surface-container-low/50 flex items-center justify-end gap-3">
                <button type="button" onclick="closeFeedbackModal()" class="px-4 py-2 rounded-lg bg-surface-container-high text-xs font-semibold text-on-surface hover:bg-surface-container-highest transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary text-xs font-semibold hover:bg-primary-container shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">send</span>
                    <span>Kirim Ulasan Masukan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openFeedbackModal() {
    document.getElementById('seekerFeedbackModal').classList.remove('hidden');
}

function closeFeedbackModal() {
    document.getElementById('seekerFeedbackModal').classList.add('hidden');
}

function setFeedbackRating(stars) {
    document.getElementById('feedbackRatingInput').value = stars;
    const icons = document.querySelectorAll('#starRatingContainer .star-icon');
    icons.forEach(icon => {
        const val = parseInt(icon.getAttribute('data-star'));
        if (val <= stars) {
            icon.textContent = 'star';
            icon.classList.remove('text-outline');
            icon.classList.add('text-amber-400');
        } else {
            icon.textContent = 'star_border';
            icon.classList.remove('text-amber-400');
            icon.classList.add('text-outline');
        }
    });

    const labels = {
        1: '1 / 5 (Kurang Puas)',
        2: '2 / 5 (Cukup)',
        3: '3 / 5 (Baik)',
        4: '4 / 5 (Puas)',
        5: '5 / 5 (Sangat Puas)'
    };
    document.getElementById('ratingTextLabel').textContent = labels[stars] || (stars + ' / 5');
}
</script>
