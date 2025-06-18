@extends('layouts.userLayout')

@section('content')
    <div class="col-md-12">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/slide_profil1.jpg') }}" class="d-block w-100" style="height: 600px; object-fit: fill;" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide_profil2.jpg') }}" class="d-block w-100" style="height: 600px; object-fit: fill;" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide_profil3.jpg') }}" class="d-block w-100" style="height: 600px; object-fit: fill;" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">Tentang Kami</h4>
                
                <!-- Visi Sekolah -->
                <div class="mb-4">
                    <h5 class="text-primary">Visi Sekolah</h5>
                    <p class="card-text">Mewujudkan insan yang cerdas, mandiri, berkecakapan hidup, dan Agamis melalui Proses Pembelajaran yang Efektif, Kreatif dan Menyenangkan menuju Pendidikan yang berkualitas dan berdaya Saing.</p>
                </div>
                
                <!-- Misi Sekolah -->
                <div class="mb-4">
                    <h5 class="text-primary">Misi Sekolah</h5>
                    <ul class="card-text">
                        <li>Menyelenggarakan Pendidikan yang bekesinambungan secara proposional, adil dan merata menuju pendidikan yang berkualitas</li>
                        <li>Memberikan jaminan dan kepercayaan kepada masyarakat terhadap layanan pengelolaan pendidikan</li>
                        <li>Mempersiapkan sekolah yang memilki kompetensi yang kualitatif</li>
                        <li>Mengembangkan Bakat, Minat, dan Potensi yang dimliki siswa</li>
                        <li>Menyiapkan Lulusan yang cerdas, mandiri, berkecakapan hidup, dan Agamis</li>
                        <li>Mewujudkan dan melengkapi Sarana prasarana yang dibutuhkan Sekolah dan siswa</li>
                        <li>Memberikan motivasi kepada siswa untuk berprestasi melalui Kurikuler, Ko-kurikuler dan Ekstrakurikuler</li>
                    </ul>
                </div>
                
                <!-- Tujuan Sekolah -->
                <div>
                    <h5 class="text-primary">Tujuan</h5>
                    <p class="card-text">Tujuan pendidikan dasar adalah meletakkan dasar kecerdasan, pengetahuan, kepribadian, akhlak mulia, serta keterampilan untuk hidup mandiri dan mengikuti pendidikan lebih lanjut, maka tujuan Sekolah Dasar Negeri Blok i Cilegon adalah sebagai berikut.</p>
                    <ul class="card-text">
                        <li> Meningkatkan kompetensi dan kulifikasi pendidik dan tenaga kependidikan</li>
                        <li> Melaksanakan Pembelajaran yang efektif, kreatif dan menyenangkan</li>
                        <li> Mengamalkan ajaran agama sebagai hasil proses pembelajaran dan kegiatan pembiasaan</li>
                        <li> Melaksanakan pendidikan yang berkarakter</li>
                        <li> Membiasakan siswa Bahasa Inggris dan Informasi serta Teknologi (Teknologi Informasi Komputer) pada proses pembelajaran dan kehidupan sehari-hari</li>
                        <li> Menguasai dasar-dasar ilmu pengetahuan dan teknologi sebagai bekal untuk melanjutkan ke sekolah yang lebih tinggi</li>
                        <li> Meraih prestasi akademik maupun nonakademik pada semua jenjang dan tingkatan</li>
                        <li> Menjadi sekolah yang mempelopori dan penggerak di lingkungan sekolah masyarakat sekitar</li>
                        <li> Menjadi sekolah yang terpilih dan diminati di masyarakat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection