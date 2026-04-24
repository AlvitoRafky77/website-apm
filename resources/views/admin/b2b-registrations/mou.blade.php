<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjanjian Kerja Sama - {{ $registration->company_name }}</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2.5cm 3cm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #111;
            line-height: 1.6;
        }

        /* === KOP SURAT === */
        .kop {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 12px;
            border-bottom: 3px double #333;
            margin-bottom: 24px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .kop-text { flex: 1; }
        .kop-text h1 {
            font-size: 16pt;
            font-weight: 900;
            margin: 0 0 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-text p { margin: 0; font-size: 10pt; color: #555; }
        .kop-text .tagline { font-style: italic; color: #888; }

        /* === JUDUL SURAT === */
        .surat-title {
            text-align: center;
            margin: 28px 0 24px;
        }
        .surat-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            display: inline-block;
            margin: 0 0 4px;
        }
        .surat-title p { margin: 0; font-size: 10pt; color: #555; }

        /* === NOMOR SURAT === */
        .nomor { margin-bottom: 20px; font-size: 11pt; }

        /* === PARA === */
        p.para {
            text-align: justify;
            margin: 0 0 14px;
        }

        /* === PARA PIHAK === */
        .pihak-box {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 16px 20px;
            margin: 20px 0;
            background: #fafafa;
        }
        .pihak-box h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0 0 10px;
            text-decoration: underline;
        }
        .pihak-row { display: flex; margin-bottom: 6px; font-size: 11pt; }
        .pihak-label { width: 200px; color: #555; }
        .pihak-colon { width: 20px; }
        .pihak-value { flex: 1; font-weight: bold; }

        /* === PASAL === */
        .pasal {
            margin: 22px 0 0;
        }
        .pasal h3 {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin: 0 0 8px;
        }
        .pasal ol { padding-left: 24px; margin: 0; }
        .pasal li { margin-bottom: 6px; text-align: justify; }

        /* === TTD === */
        .ttd-section {
            margin-top: 48px;
            page-break-inside: avoid;
        }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .ttd-label { font-weight: bold; font-size: 11pt; margin-bottom: 4px; }
        .ttd-company { font-size: 10pt; color: #555; margin-bottom: 70px; }
        .ttd-line {
            border-top: 1px solid #333;
            padding-top: 6px;
            margin-top: 4px;
        }
        .ttd-name { font-weight: bold; font-size: 11pt; }
        .ttd-position { font-size: 10pt; color: #555; }

        /* === MATERAI === */
        .materai-placeholder {
            display: inline-block;
            width: 80px;
            height: 80px;
            border: 2px dashed #bbb;
            border-radius: 8px;
            line-height: 80px;
            color: #bbb;
            font-size: 9pt;
            text-align: center;
            vertical-align: middle;
            margin: 10px auto 10px;
        }

        .footer-note {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <div class="kop-text">
            <h1>PT. Agro Pangan Maju</h1>
            <p>Pergudangan Panam Bizpark No. B-19, Jl. Raya Pekanbaru–Bangkinang,</p>
            <p>Kel. Tuah Madani, Kec. Tampan, Kota Pekanbaru, Riau 28291</p>
            <p>Telp: (0761) xxx-xxxx &nbsp;|&nbsp; Email: info@agropanganmaju.com</p>
            <p class="tagline">The Best Supplier on Earth</p>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="surat-title">
        <h2>Surat Perjanjian Kerja Sama</h2>
        <p>(Memorandum of Understanding / MOU)</p>
    </div>

    {{-- NOMOR --}}
    <div class="nomor">
        Nomor: <strong>MOU/APM/{{ strtoupper(substr(str_replace(' ','',$registration->company_type),0,2)) }}/{{ now()->format('Y') }}/{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</strong>
    </div>

    {{-- PEMBUKAAN --}}
    <p class="para">
        Pada hari ini, <strong>{{ $date }}</strong>, kami yang bertanda tangan di bawah ini telah menyepakati
        Perjanjian Kerja Sama (selanjutnya disebut "Perjanjian") ini dengan ketentuan sebagai berikut:
    </p>

    {{-- PIHAK PERTAMA --}}
    <div class="pihak-box">
        <h3>PIHAK PERTAMA</h3>
        <div class="pihak-row"><span class="pihak-label">Nama Perusahaan</span><span class="pihak-colon">:</span><span class="pihak-value">PT. Agro Pangan Maju</span></div>
        <div class="pihak-row"><span class="pihak-label">Diwakili oleh</span><span class="pihak-colon">:</span><span class="pihak-value">Direktur Utama</span></div>
        <div class="pihak-row"><span class="pihak-label">Jabatan</span><span class="pihak-colon">:</span><span class="pihak-value">Direktur Utama</span></div>
        <div class="pihak-row"><span class="pihak-label">Alamat</span><span class="pihak-colon">:</span><span class="pihak-value">Pergudangan Panam Bizpark No. B-19, Pekanbaru, Riau</span></div>
    </div>

    {{-- PIHAK KEDUA --}}
    <div class="pihak-box">
        <h3>PIHAK KEDUA</h3>
        <div class="pihak-row"><span class="pihak-label">Nama Perusahaan</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->company_name }}</span></div>
        <div class="pihak-row"><span class="pihak-label">Jenis Perusahaan</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->company_type }}</span></div>
        @if($registration->npwp)
        <div class="pihak-row"><span class="pihak-label">NPWP</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->npwp }}</span></div>
        @endif
        <div class="pihak-row"><span class="pihak-label">Diwakili oleh</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->pic_name }}</span></div>
        <div class="pihak-row"><span class="pihak-label">Jabatan</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->pic_position ?: 'Perwakilan Perusahaan' }}</span></div>
        <div class="pihak-row"><span class="pihak-label">Alamat</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->company_address }}{{ $registration->company_city ? ', ' . $registration->company_city : '' }}{{ $registration->company_province ? ', ' . $registration->company_province : '' }}</span></div>
        <div class="pihak-row"><span class="pihak-label">Email</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->email }}</span></div>
        <div class="pihak-row"><span class="pihak-label">No. Telepon</span><span class="pihak-colon">:</span><span class="pihak-value">{{ $registration->phone }}</span></div>
    </div>

    <p class="para">
        Pihak Pertama dan Pihak Kedua, yang selanjutnya secara bersama-sama disebut "Para Pihak", dengan ini
        menyatakan sepakat untuk mengadakan kerja sama dalam bidang pengadaan dan distribusi bahan baku pangan
        dengan ketentuan sebagai berikut:
    </p>

    {{-- PASAL 1 --}}
    <div class="pasal">
        <h3>Pasal 1 – Maksud dan Tujuan</h3>
        <ol>
            <li>Perjanjian ini bertujuan untuk menetapkan kerangka kerja kerja sama strategis antara Pihak Pertama dan Pihak Kedua dalam pengadaan produk pangan berkualitas secara berkelanjutan.</li>
            <li>Para Pihak sepakat untuk menjalankan transaksi secara transparan, akuntabel, dan profesional melalui platform digital yang disediakan oleh Pihak Pertama.</li>
        </ol>
    </div>

    {{-- PASAL 2 --}}
    <div class="pasal">
        <h3>Pasal 2 – Penggunaan Portal B2B</h3>
        <ol>
            <li>Pihak Kedua diberikan akses eksklusif ke Portal B2B PT. Agro Pangan Maju untuk melakukan pemesanan, pemantauan status pengiriman, dan manajemen pembayaran.</li>
            <li>Pihak Kedua bertanggung jawab penuh atas kerahasiaan akun dan setiap aktivitas transaksi yang dilakukan melalui portal tersebut.</li>
            <li>Harga yang tercantum dalam portal adalah harga khusus mitra B2B yang bersifat fluktuatif mengikuti harga pasar, kecuali disepakati lain secara tertulis.</li>
        </ol>
    </div>

    {{-- PASAL 3 --}}
    <div class="pasal">
        <h3>Pasal 3 – Prosedur Pemesanan dan Transaksi</h3>
        <ol>
            <li>Setiap pesanan yang diajukan oleh Pihak Kedua melalui portal akan divalidasi oleh sistem dan Admin Pihak Pertama untuk ketersediaan stok dan kelayakan logistik.</li>
            <li>Pesanan dianggap sah apabila Pihak Kedua telah melakukan pembayaran **Down Payment (DP)** sesuai persentase yang ditetapkan oleh sistem dan telah diverifikasi oleh Pihak Pertama.</li>
            <li>Pembatalan pesanan setelah verifikasi DP akan dikenakan sanksi biaya administrasi atau sesuai kebijakan yang berlaku pada saat transaksi.</li>
        </ol>
    </div>

    {{-- PASAL 4 --}}
    <div class="pasal">
        <h3>Pasal 4 – Mekanisme Pembayaran</h3>
        <ol>
            <li>Pihak Kedua wajib melakukan Pelunasan tagihan sebelum barang diserahterimakan atau sesuai dengan tenggat waktu yang disepakati dalam portal.</li>
            <li>Pembayaran dilakukan melalui kanal perbankan resmi Pihak Pertama yang terdaftar pada sistem (Bank BCA/Bank DKI).</li>
            <li>Keterlambatan pembayaran pelunasan dapat mengakibatkan penundaan pengiriman atau penangguhan akun mitra secara sementara.</li>
        </ol>
    </div>

    {{-- PASAL 5 --}}
    <div class="pasal">
        <h3>Pasal 5 – Pengiriman dan Logistik</h3>
        <ol>
            <li>Pihak Pertama menjamin pengiriman menggunakan armada dengan standar *Cold Chain* (Rantai Dingin) untuk menjaga kualitas produk tetap prima hingga ke lokasi tujuan.</li>
            <li>Risiko atas barang beralih dari Pihak Pertama ke Pihak Kedua pada saat barang telah diterima dan dikonfirmasi di lokasi pengiriman.</li>
            <li>Pihak Kedua wajib memastikan akses jalan dan area bongkar muat memadai untuk armada pengiriman Pihak Pertama.</li>
        </ol>
    </div>

    {{-- PASAL 6 --}}
    <div class="pasal">
        <h3>Pasal 6 – Penerimaan Barang dan Komplain</h3>
        <ol>
            <li>Pihak Kedua wajib memeriksa kondisi fisik, kuantitas, dan kualitas barang pada saat serah terima di hadapan Driver Pihak Pertama.</li>
            <li>Segala bentuk ketidaksesuaian wajib dilaporkan melalui fitur **Dispute/Komplain** di portal dalam waktu maksimal **1 x 24 jam** setelah barang diterima.</li>
            <li>Pihak Kedua wajib menandatangani Surat Penyerahan Barang (SPB) atau Surat Jalan fisik/digital sebagai bukti sah bahwa transaksi telah selesai dengan baik.</li>
        </ol>
    </div>

    {{-- PASAL 7 --}}
    <div class="pasal">
        <h3>Pasal 7 – Kerahasiaan Informasi</h3>
        <ol>
            <li>Para Pihak dilarang membocorkan rahasia dagang, daftar harga khusus, dan data operasional lainnya kepada pihak ketiga tanpa izin tertulis.</li>
            <li>Pelanggaran terhadap kerahasiaan data dapat berakibat pada pemutusan hubungan kerja sama secara sepihak dan tuntutan hukum sesuai perundang-undangan yang berlaku.</li>
        </ol>
    </div>

    {{-- PASAL 8 --}}
    <div class="pasal">
        <h3>Pasal 8 – Jangka Waktu dan Pengakhiran</h3>
        <ol>
            <li>Perjanjian ini berlaku untuk jangka waktu **1 (satu) tahun** dan akan dievaluasi secara berkala.</li>
            <li>Salah satu pihak berhak mengakhiri perjanjian ini dengan pemberitahuan tertulis minimal 30 (tiga puluh) hari kalender sebelumnya.</li>
        </ol>
    </div>

    {{-- PASAL 9 --}}
    <div class="pasal">
        <h3>Pasal 9 – Keadaan Memaksa (Force Majeure)</h3>
        <ol>
            <li>Para Pihak dibebaskan dari tanggung jawab atas kegagalan pelaksanaan kewajiban akibat kejadian di luar kendali manusia (Bencana alam, wabah, kebijakan darurat pemerintah, atau huru-hara).</li>
            <li>Pihak yang mengalami *Force Majeure* wajib memberitahukan kepada pihak lainnya dalam waktu maksimal 3 (tiga) hari kerja.</li>
        </ol>
    </div>

    {{-- PASAL 10 --}}
    <div class="pasal">
        <h3>Pasal 10 – Penyelesaian Perselisihan</h3>
        <ol>
            <li>Setiap perselisihan yang timbul akan diselesaikan secara musyawarah untuk mufakat.</li>
            <li>Apabila musyawarah tidak mencapai kesepakatan, maka Para Pihak sepakat untuk memilih kedudukan hukum yang tetap pada Kantor Kepaniteraan Pengadilan Negeri Pekanbaru.</li>
        </ol>
    </div>

    {{-- PENUTUP --}}
    <br>
    <p class="para">
        Perjanjian ini dibuat dalam rangkap <strong>2 (dua)</strong> eksemplar, masing-masing berkekuatan hukum yang sama,
        dan ditandatangani oleh Para Pihak di atas materai yang cukup.
    </p>

    {{-- TANDA TANGAN --}}
    <div class="ttd-section">
        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-label">PIHAK PERTAMA</div>
                    <div class="ttd-company">PT. Agro Pangan Maju</div>
                    <div style="text-align:center; margin-bottom: 8px;">
                        <div class="materai-placeholder">Materai<br>10.000</div>
                    </div>
                    <div class="ttd-line">
                        <div class="ttd-name">( __________________________ )</div>
                        <div class="ttd-position">Direktur Utama</div>
                    </div>
                </td>
                <td>
                    <div class="ttd-label">PIHAK KEDUA</div>
                    <div class="ttd-company">{{ $registration->company_name }}</div>
                    <div style="text-align:center; margin-bottom: 8px;">
                        <div class="materai-placeholder">Materai<br>10.000</div>
                    </div>
                    <div class="ttd-line">
                        <div class="ttd-name">( {{ $registration->pic_name }} )</div>
                        <div class="ttd-position">{{ $registration->pic_position ?: 'Perwakilan Perusahaan' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dokumen ini digenerate secara otomatis oleh sistem PT. Agro Pangan Maju &nbsp;|&nbsp; {{ $date }}
    </div>
</body>
</html>
